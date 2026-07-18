<?php

function ntm_046_decode_text($value): string
{
    return trim(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function ntm_046_normalized_iframe_hosts(string $content): array
{
    if (!preg_match_all('~<iframe\b[^>]*\bsrc\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))~i', $content, $matches, PREG_SET_ORDER)) {
        return [];
    }

    $hosts = [];
    foreach ($matches as $match) {
        $src = html_entity_decode(trim((string) ($match[1] ?: ($match[2] ?: $match[3]))), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $host = parse_url($src, PHP_URL_HOST);

        $hosts[] = is_string($host) ? rtrim(strtolower($host), '.') : '';
    }

    return $hosts;
}

function ntm_046_content_is_known_legacy_iframe(string $content, string $expected_host): bool
{
    $hosts = ntm_046_normalized_iframe_hosts($content);

    if (count($hosts) !== 1 || $hosts[0] !== $expected_host) {
        return false;
    }

    $without_iframe = preg_replace('~<iframe\b[^>]*>.*?</iframe>~is', '', $content);
    $without_iframe = preg_replace('~<!--.*?-->~s', '', (string) $without_iframe);
    $text = ntm_046_decode_text(strip_tags((string) $without_iframe));

    return $text === '';
}

function ntm_046_expected_page_error($post, int $id, string $expected_title, string $expected_slug): string
{
    if (!$post) {
        return "id={$id}: missing page";
    }

    if ((string) ($post->post_type ?? '') !== 'page') {
        return "id={$id}: expected page, found " . (string) ($post->post_type ?? 'unknown');
    }

    if ((string) ($post->post_status ?? '') !== 'publish') {
        return "id={$id}: status mismatch - expected publish, found " . (string) ($post->post_status ?? 'unknown');
    }

    $title = ntm_046_decode_text($post->post_title ?? '');
    if ($title !== $expected_title) {
        return "id={$id}: title mismatch - expected \"{$expected_title}\", found \"{$title}\"";
    }

    $slug = (string) ($post->post_name ?? '');
    if ($slug !== $expected_slug) {
        return "id={$id}: slug mismatch - expected \"{$expected_slug}\", found \"{$slug}\"";
    }

    return '';
}

function ntm_046_current_template(int $id): string
{
    $template = get_post_meta($id, '_wp_page_template', true);

    return is_string($template) ? $template : '';
}

function ntm_046_fail(string $message): int
{
    echo "FAIL {$message}\n";

    return 1;
}

function ntm_046_run(bool $dry): int
{
    $id = 20405;
    $expected_title = 'Panel Machine Readiness Quiz';
    $expected_slug = 'portable-rollforming-machine-readiness-assessment';
    $expected_iframe_host = 'readinessassessment.b.abacusai.app';
    $template = 'templates/template-readiness-quiz.php';

    $post = get_post($id);
    $identity_error = ntm_046_expected_page_error($post, $id, $expected_title, $expected_slug);

    if ($identity_error !== '') {
        return ntm_046_fail($identity_error . '. Refusing to write.');
    }

    $content = (string) ($post->post_content ?? '');
    $content_empty = trim($content) === '';
    $content_is_known_iframe = !$content_empty && ntm_046_content_is_known_legacy_iframe($content, $expected_iframe_host);

    if (!$content_empty && !$content_is_known_iframe) {
        $hosts = ntm_046_normalized_iframe_hosts($content);
        $host_note = $hosts === [] ? 'no iframe host found' : 'iframe host(s): ' . implode(', ', $hosts);

        return ntm_046_fail("id={$id}: unknown non-empty content ({$host_note}). Refusing to clear it.");
    }

    $current_template = ntm_046_current_template($id);
    $title = ntm_046_decode_text($post->post_title);

    if ($current_template === $template && $content_empty) {
        echo "OK id={$id}: already on quiz template with empty content - {$title}\n";

        return 0;
    }

    if ($dry) {
        echo "DRY id={$id}: {$title}\n";
        echo '      template: ' . ($current_template ?: '(default)') . " -> {$template}\n";
        echo '      content : ' . ($content_empty ? '(already empty)' : "legacy iframe host {$expected_iframe_host}") . " -> (empty)\n";

        return 0;
    }

    // Template meta must be verified before content is cleared; that ordering is
    // the guardrail against losing the legacy iframe while the page still renders
    // through the wrong template.
    if ($current_template !== $template) {
        update_post_meta($id, '_wp_page_template', $template);
    }

    $verified_template = ntm_046_current_template($id);
    if ($verified_template !== $template) {
        return ntm_046_fail("id={$id}: _wp_page_template write did not stick (found \"{$verified_template}\"). Content left untouched.");
    }

    if (!$content_empty) {
        global $wpdb;

        $updated = $wpdb->update($wpdb->posts, ['post_content' => ''], ['ID' => $id]);
        if ($updated === false) {
            return ntm_046_fail("id={$id}: could not clear known legacy iframe content.");
        }

        clean_post_cache($id);

        $after_content = (string) get_post_field('post_content', $id, 'raw');
        if (trim($after_content) !== '') {
            return ntm_046_fail("id={$id}: post_content was not empty after update.");
        }
    }

    echo "WROTE id={$id}: template verified as {$template}, content " . ($content_empty ? 'left empty' : 'cleared') . " - {$title}\n";

    return 0;
}

if (!defined('NTM_DB_MIGRATION_TEST')) {
    exit(ntm_046_run(getenv('NTM_DRY_RUN') !== '0'));
}
