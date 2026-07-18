<?php

declare(strict_types=1);

define('NTM_DB_MIGRATION_TEST', true);

require __DIR__ . '/../046-readiness-quiz-page-template.php';
require __DIR__ . '/../049-draft-duplicate-uniq-controller.php';

final class WP_Error
{
    public function __construct(private string $message)
    {
    }

    public function get_error_message(): string
    {
        return $this->message;
    }
}

final class Ntm_Test_Wpdb
{
    public string $posts = 'wp_posts';

    /** @var array<int, array{table: string, data: array<string, string>, where: array<string, int>}> */
    public array $updates = [];

    public function update(string $table, array $data, array $where): int|false
    {
        $this->updates[] = [
            'table' => $table,
            'data' => $data,
            'where' => $where,
        ];

        if ($GLOBALS['ntm_test_wpdb_update_fails']) {
            return false;
        }

        $id = (int) ($where['ID'] ?? 0);
        if (isset($GLOBALS['ntm_test_posts'][$id]) && array_key_exists('post_content', $data)) {
            $GLOBALS['ntm_test_posts'][$id]->post_content = (string) $data['post_content'];
        }

        return 1;
    }
}

function ntm_test_reset(): void
{
    $GLOBALS['ntm_test_posts'] = [];
    $GLOBALS['ntm_test_meta'] = [];
    $GLOBALS['ntm_test_update_post_meta_calls'] = [];
    $GLOBALS['ntm_test_update_post_meta_fails'] = false;
    $GLOBALS['ntm_test_wp_update_post_calls'] = [];
    $GLOBALS['ntm_test_wp_update_post_error'] = null;
    $GLOBALS['ntm_test_wp_update_post_no_persist'] = false;
    $GLOBALS['ntm_test_wpdb_update_fails'] = false;
    $GLOBALS['wpdb'] = new Ntm_Test_Wpdb();
}

function ntm_test_post(array $overrides): object
{
    return (object) array_merge([
        'ID' => 0,
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => '',
        'post_name' => '',
        'post_content' => '',
    ], $overrides);
}

function ntm_test_seed_046(array $overrides = [], string $template = ''): void
{
    $GLOBALS['ntm_test_posts'][20405] = ntm_test_post(array_merge([
        'ID' => 20405,
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'Panel Machine Readiness Quiz',
        'post_name' => 'portable-rollforming-machine-readiness-assessment',
        'post_content' => '',
    ], $overrides));
    $GLOBALS['ntm_test_meta'][20405]['_wp_page_template'] = $template;
}

function ntm_test_seed_049(array $duplicate = [], array $keeper = []): void
{
    $GLOBALS['ntm_test_posts'][18732] = ntm_test_post(array_merge([
        'ID' => 18732,
        'post_type' => 'product',
        'post_status' => 'publish',
        'post_title' => 'UNIQ™ Automatic Control System UNQ-SSQ3-A',
        'post_name' => 'uniq-automatic-control-system',
    ], $keeper));
    $GLOBALS['ntm_test_meta'][18732]['_regular_price'] = $keeper['price'] ?? '22500.00';

    $GLOBALS['ntm_test_posts'][2799] = ntm_test_post(array_merge([
        'ID' => 2799,
        'post_type' => 'product',
        'post_status' => 'publish',
        'post_title' => 'UNIQ™ Automatic Control System',
        'post_name' => 'uniq-control-system',
    ], $duplicate));
    $GLOBALS['ntm_test_meta'][2799]['_regular_price'] = $duplicate['price'] ?? '21700.00';
}

function get_post(int $id): ?object
{
    return $GLOBALS['ntm_test_posts'][$id] ?? null;
}

function get_post_meta(int $id, string $key, bool $single = true): mixed
{
    return $GLOBALS['ntm_test_meta'][$id][$key] ?? '';
}

function update_post_meta(int $id, string $key, mixed $value): bool
{
    $GLOBALS['ntm_test_update_post_meta_calls'][] = compact('id', 'key', 'value');

    if ($GLOBALS['ntm_test_update_post_meta_fails']) {
        return false;
    }

    $GLOBALS['ntm_test_meta'][$id][$key] = $value;

    return true;
}

function get_post_field(string $field, int $id, string $context = ''): mixed
{
    return $GLOBALS['ntm_test_posts'][$id]->{$field} ?? null;
}

function clean_post_cache(int $id): void
{
}

function wp_update_post(array $args, bool $wp_error = false): int|WP_Error
{
    $GLOBALS['ntm_test_wp_update_post_calls'][] = $args;

    if ($GLOBALS['ntm_test_wp_update_post_error'] instanceof WP_Error) {
        return $GLOBALS['ntm_test_wp_update_post_error'];
    }

    $id = (int) ($args['ID'] ?? 0);
    if (!$GLOBALS['ntm_test_wp_update_post_no_persist'] && isset($GLOBALS['ntm_test_posts'][$id])) {
        foreach ($args as $key => $value) {
            if ($key === 'ID') {
                continue;
            }

            $GLOBALS['ntm_test_posts'][$id]->{$key} = $value;
        }
    }

    return $id;
}

function is_wp_error(mixed $value): bool
{
    return $value instanceof WP_Error;
}

function ntm_test_capture(callable $callback): array
{
    ob_start();
    $status = $callback();
    $output = (string) ob_get_clean();

    return [$status, $output];
}

function ntm_test_assert_same(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.');
    }
}

function ntm_test_assert_contains(string $needle, string $haystack, string $message): void
{
    if (!str_contains($haystack, $needle)) {
        throw new RuntimeException($message . " Missing '{$needle}' in output: {$haystack}");
    }
}

function ntm_test_assert_no_writes(string $message): void
{
    ntm_test_assert_same([], $GLOBALS['ntm_test_update_post_meta_calls'], "{$message}: unexpected post meta write");
    ntm_test_assert_same([], $GLOBALS['ntm_test_wp_update_post_calls'], "{$message}: unexpected wp_update_post call");
    ntm_test_assert_same([], $GLOBALS['wpdb']->updates, "{$message}: unexpected direct DB update");
}

$tests = [
    '046 rejects missing page before writing' => function (): void {
        ntm_test_reset();

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(1, $status, '046 should fail missing page');
        ntm_test_assert_contains('missing page', $output, '046 should report missing page');
        ntm_test_assert_no_writes('046 missing page');
    },

    '046 rejects non-published page before writing' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046(['post_status' => 'draft']);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(1, $status, '046 should fail non-published page');
        ntm_test_assert_contains('status mismatch', $output, '046 should report status mismatch');
        ntm_test_assert_no_writes('046 non-published page');
    },

    '046 rejects unknown non-empty content before writing' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046(['post_content' => '<p>Keep this human copy.</p>']);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(1, $status, '046 should fail dangerous content');
        ntm_test_assert_contains('unknown non-empty content', $output, '046 should explain dangerous content');
        ntm_test_assert_same('<p>Keep this human copy.</p>', $GLOBALS['ntm_test_posts'][20405]->post_content, '046 should leave content untouched');
        ntm_test_assert_no_writes('046 unknown content');
    },

    '046 rejects lookalike iframe hosts before writing' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046([
            'post_content' => '<iframe src="https://readinessassessment.b.abacusai.app.evil.test/quiz"></iframe>',
        ]);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(1, $status, '046 should fail lookalike iframe host');
        ntm_test_assert_contains('readinessassessment.b.abacusai.app.evil.test', $output, '046 should report the hostile host');
        ntm_test_assert_no_writes('046 lookalike iframe');
    },

    '046 verifies template write before clearing iframe content' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046([
            'post_content' => '<iframe src="https://readinessassessment.b.abacusai.app/quiz"></iframe>',
        ]);
        $GLOBALS['ntm_test_update_post_meta_fails'] = true;

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(1, $status, '046 should fail when template write does not stick');
        ntm_test_assert_contains('_wp_page_template write did not stick', $output, '046 should report template write failure');
        ntm_test_assert_same('<iframe src="https://readinessassessment.b.abacusai.app/quiz"></iframe>', $GLOBALS['ntm_test_posts'][20405]->post_content, '046 should not clear iframe after template write failure');
        ntm_test_assert_same([], $GLOBALS['wpdb']->updates, '046 should not direct-update content after template write failure');
    },

    '046 accepts normalized legacy iframe host and clears content after template verification' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046([
            'post_content' => '<p><iframe src="HTTPS://readinessassessment.b.abacusai.app./quiz"></iframe></p>',
        ]);

        [$status] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(0, $status, '046 should accept normalized expected iframe host');
        ntm_test_assert_same('templates/template-readiness-quiz.php', $GLOBALS['ntm_test_meta'][20405]['_wp_page_template'], '046 should set template');
        ntm_test_assert_same('', $GLOBALS['ntm_test_posts'][20405]->post_content, '046 should clear known legacy iframe');
        ntm_test_assert_same(1, count($GLOBALS['wpdb']->updates), '046 should clear content exactly once');
    },

    '046 dry-run reports pending template and content changes without writing' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046([
            'post_content' => '<iframe src="https://readinessassessment.b.abacusai.app/quiz"></iframe>',
        ]);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(true));

        ntm_test_assert_same(0, $status, '046 dry-run should succeed on safe pending write');
        ntm_test_assert_contains('DRY id=20405', $output, '046 dry-run should identify dry output');
        ntm_test_assert_contains('template: (default) -> templates/template-readiness-quiz.php', $output, '046 dry-run should report template change');
        ntm_test_assert_contains('content : legacy iframe host readinessassessment.b.abacusai.app -> (empty)', $output, '046 dry-run should report content clear');
        ntm_test_assert_same('', $GLOBALS['ntm_test_meta'][20405]['_wp_page_template'], '046 dry-run should leave template untouched');
        ntm_test_assert_same('<iframe src="https://readinessassessment.b.abacusai.app/quiz"></iframe>', $GLOBALS['ntm_test_posts'][20405]->post_content, '046 dry-run should leave content untouched');
        ntm_test_assert_no_writes('046 dry-run pending write');
    },

    '046 is idempotent when template and content are already correct' => function (): void {
        ntm_test_reset();
        ntm_test_seed_046([], 'templates/template-readiness-quiz.php');

        [$status, $output] = ntm_test_capture(fn (): int => ntm_046_run(false));

        ntm_test_assert_same(0, $status, '046 should no-op clean state');
        ntm_test_assert_contains('already on quiz template', $output, '046 should report idempotent state');
        ntm_test_assert_no_writes('046 idempotent state');
    },

    '049 rejects missing keeper before drafting duplicate' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049();
        unset($GLOBALS['ntm_test_posts'][18732]);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail missing keeper');
        ntm_test_assert_contains('missing product', $output, '049 should report missing keeper product');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 should leave duplicate published when keeper is missing');
        ntm_test_assert_no_writes('049 missing keeper');
    },

    '049 rejects unsafe keeper identity before drafting duplicate' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049([], ['price' => '22500']);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail unsafe keeper price');
        ntm_test_assert_contains('price mismatch', $output, '049 should report keeper mismatch');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 should leave duplicate published');
        ntm_test_assert_no_writes('049 unsafe keeper');
    },

    '049 rejects missing duplicate before drafting' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049();
        unset($GLOBALS['ntm_test_posts'][2799]);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail missing duplicate');
        ntm_test_assert_contains('missing product', $output, '049 should report missing duplicate product');
        ntm_test_assert_no_writes('049 missing duplicate');
    },

    '049 rejects unsafe duplicate identity before drafting' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049(['post_name' => 'uniq-control-system-copy']);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail unsafe duplicate slug');
        ntm_test_assert_contains('slug mismatch', $output, '049 should report duplicate mismatch');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 should leave duplicate published');
        ntm_test_assert_no_writes('049 unsafe duplicate');
    },

    '049 returns nonzero when draft write returns WP_Error' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049();
        $GLOBALS['ntm_test_wp_update_post_error'] = new WP_Error('database refused update');

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail WP_Error write');
        ntm_test_assert_contains('database refused update', $output, '049 should report write error');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 should not mutate duplicate on WP_Error');
    },

    '049 verifies duplicate status after wp_update_post' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049();
        $GLOBALS['ntm_test_wp_update_post_no_persist'] = true;

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(1, $status, '049 should fail when status does not persist');
        ntm_test_assert_contains('status was not draft after update', $output, '049 should report non-persistent write');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 duplicate should still be publish in no-persist scenario');
    },

    '049 dry-run reports pending draft without writing' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049();

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(true));

        ntm_test_assert_same(0, $status, '049 dry-run should succeed on safe pending write');
        ntm_test_assert_contains('DRY id=2799', $output, '049 dry-run should identify dry output');
        ntm_test_assert_contains('would set to draft', $output, '049 dry-run should report draft action');
        ntm_test_assert_contains('keeper verified published', $output, '049 dry-run should report verified keeper');
        ntm_test_assert_same('publish', $GLOBALS['ntm_test_posts'][2799]->post_status, '049 dry-run should leave duplicate published');
        ntm_test_assert_no_writes('049 dry-run pending write');
    },

    '049 is idempotent when duplicate is already draft and keeper is safe' => function (): void {
        ntm_test_reset();
        ntm_test_seed_049(['post_status' => 'draft']);

        [$status, $output] = ntm_test_capture(fn (): int => ntm_049_run(false));

        ntm_test_assert_same(0, $status, '049 should no-op already-draft duplicate');
        ntm_test_assert_contains('already draft', $output, '049 should report idempotent state');
        ntm_test_assert_same([], $GLOBALS['ntm_test_wp_update_post_calls'], '049 should not update already-draft duplicate');
    },
];

$failures = 0;

foreach ($tests as $name => $test) {
    try {
        $test();
        echo "PASS {$name}\n";
    } catch (Throwable $exception) {
        $failures++;
        echo "FAIL {$name}: {$exception->getMessage()}\n";
    }
}

if ($failures > 0) {
    exit(1);
}

echo 'DB migration guard tests OK (' . count($tests) . ")\n";
