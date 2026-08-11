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
        ($status['state'] ?? '') === 'sunsetting',
        'The SSQ II should remain in its final-sale period until the published deadline.'
    );
    ntm_assert(
        ($status['deadline'] ?? '') === '2026-09-30',
        'The SSQ II final-sale deadline should be September 30, 2026.'
    );
    ntm_assert(
        ($status['label'] ?? '') === 'Will Be Discontinued September 30, 2026',
        'The SSQ II status label should use the approved customer-facing message.'
    );
    ntm_assert(
        \Standard\MachineStatus\is_sunsetting('ssqii'),
        'The legacy configurator slug should resolve as sunsetting.'
    );
    ntm_assert(
        !\Standard\MachineStatus\is_discontinued('ssqii'),
        'The SSQ II must not be marked discontinued before its final-sale deadline.'
    );
    ntm_assert(
        \Standard\MachineStatus\get_configurator_url('ssq-ii-multipro')
            === 'https://newtechmachinery.com/configurator/ssqii/',
        'The final-sale CTA should route to the open SSQ II configurator.'
    );
    ntm_assert(
        \Standard\MachineStatus\get_replacement_url('ssq-ii-multipro')
            === 'https://newtechmachinery.com/machines/roof-wall-panel-machines/ssq3-multipro/',
        'The sunsetting machine should also link to the canonical SSQ3 product page.'
    );

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
