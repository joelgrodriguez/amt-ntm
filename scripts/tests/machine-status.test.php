<?php
/**
 * Regression checks for machine lifecycle routing and content targeting.
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    /** @var array<string, array<int, callable|string>> */
    $ntm_actions = [];

    function add_action(string $hook, callable|string $callback, int $priority = 10): bool
    {
        $GLOBALS['ntm_actions'][$hook][$priority] = $callback;
        return true;
    }

    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }

    function get_the_ID(): int
    {
        return 0;
    }

    function get_post_type(int $post_id): string
    {
        return $post_id === 311 ? 'product' : 'post';
    }

    function get_the_title(int $post_id = 0): string
    {
        return $post_id === 16
            ? 'Review of the SSQ II MultiPro'
            : 'Standing Seam Profiles: SSQ200 and SSQ210A';
    }

    function ntm_assert(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException($message);
        }
    }
}

namespace Standard\Url {
    function internal(string $path): string
    {
        return 'https://newtechmachinery.com' . $path;
    }
}

namespace {
    require __DIR__ . '/../../app/inc/machine-status.php';

    $status = \Standard\MachineStatus\get_status('ssq-roof-panel-machine');
    ntm_assert($status !== null, 'The WooCommerce SSQ II slug should resolve to a lifecycle status.');
    ntm_assert(
        ($status['state'] ?? '') === 'discontinued',
        'The SSQ II should be discontinued now that its final-sale deadline has passed.'
    );
    ntm_assert(
        ($status['deadline'] ?? '') === '2026-09-30',
        'The SSQ II retirement date should be September 30, 2026.'
    );
    ntm_assert(
        \Standard\MachineStatus\is_discontinued('ssqii'),
        'The legacy configurator slug should resolve as discontinued.'
    );
    ntm_assert(
        \Standard\MachineStatus\get_replacement_url('ssq-ii-multipro')
            === 'https://newtechmachinery.com/machines/roof-wall-panel-machines/ssq3-multipro/',
        'The discontinued machine should link to the canonical SSQ3 product page.'
    );
    ntm_assert(
        \Standard\MachineStatus\get_replacement_configurator_url('ssq-ii-multipro')
            === 'https://newtechmachinery.com/configurator/ssq3-multi-pro/',
        'The discontinued machine should send buyers to the SSQ3 configurator.'
    );

    foreach (['/configurator/ssqii/', '/configurator/ssqii', '/Configurator/SSQII/'] as $path) {
        ntm_assert(
            \Standard\MachineStatus\get_retired_configurator_redirect($path, [])
                === 'https://newtechmachinery.com/configurator/ssq3-multi-pro/',
            $path . ' should redirect to the SSQ3 configurator.'
        );
    }
    ntm_assert(
        \Standard\MachineStatus\get_retired_configurator_redirect(
            '/configurator/ssqii/',
            ['utm_source' => 'email', 'utm_campaign' => 'fall sale', 'gclid' => 'abc', 'nested' => ['x']]
        ) === 'https://newtechmachinery.com/configurator/ssq3-multi-pro/?utm_source=email&utm_campaign=fall%20sale&gclid=abc',
        'The retired configurator redirect should keep scalar tracking parameters.'
    );
    foreach (['/configurator/ssq3-multi-pro/', '/configurator/ssh/', '/configurator/', '/configurator/ssqii-accessories/'] as $path) {
        ntm_assert(
            \Standard\MachineStatus\get_retired_configurator_redirect($path, []) === '',
            $path . ' must keep serving its own configurator.'
        );
    }

    foreach (['SSQ II', 'SSQII', 'SSQ2 MultiPro'] as $title) {
        ntm_assert(
            \Standard\MachineStatus\title_mentions_discontinued_machine($title),
            $title . ' should trigger the focused resource notice.'
        );
    }

    foreach (['SSQ200 Profile', 'SSQ210A Profile', 'SSQ275 NewLock'] as $title) {
        ntm_assert(
            !\Standard\MachineStatus\title_mentions_discontinued_machine($title),
            $title . ' must not be mistaken for the SSQ II machine.'
        );
    }

    ntm_assert(
        \Standard\MachineStatus\is_focused_content(16),
        'An SSQ II-focused article should receive the resource notice.'
    );
    ntm_assert(
        !\Standard\MachineStatus\is_focused_content(311),
        'Product pages should use their dedicated sales notice instead of the resource notice.'
    );

    echo "Machine status tests passed.\n";
}
