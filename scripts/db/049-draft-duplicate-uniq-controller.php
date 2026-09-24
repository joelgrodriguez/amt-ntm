<?php

function ntm_049_decode_text($value): string
{
    return trim(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function ntm_049_product_identity_error(
    $post,
    int $id,
    string $expected_title,
    string $expected_slug,
    string $expected_price,
    ?string $expected_status = null
): string {
    if (!$post) {
        return "id={$id}: missing product";
    }

    if ((string) ($post->post_type ?? '') !== 'product') {
        return "id={$id}: expected product, found " . (string) ($post->post_type ?? 'unknown');
    }

    if ($expected_status !== null && (string) ($post->post_status ?? '') !== $expected_status) {
        return "id={$id}: status mismatch - expected {$expected_status}, found " . (string) ($post->post_status ?? 'unknown');
    }

    $title = ntm_049_decode_text($post->post_title ?? '');
    if ($title !== $expected_title) {
        return "id={$id}: title mismatch - expected \"{$expected_title}\", found \"{$title}\"";
    }

    $slug = (string) ($post->post_name ?? '');
    if ($slug !== $expected_slug) {
        return "id={$id}: slug mismatch - expected \"{$expected_slug}\", found \"{$slug}\"";
    }

    $price = (string) get_post_meta($id, '_regular_price', true);
    if ($price !== $expected_price) {
        return "id={$id}: price mismatch - expected {$expected_price}, found \"{$price}\"";
    }

    return '';
}

function ntm_049_fail(string $message): int
{
    echo "FAIL {$message}\n";

    return 1;
}

function ntm_049_run(bool $dry): int
{
    $duplicate_id = 2799;
    $duplicate_title = 'UNIQ™ Automatic Control System';
    $duplicate_slug = 'uniq-control-system';
    $duplicate_price = '21700.00';

    $keeper_id = 18732;
    $keeper_title = 'UNIQ™ Automatic Control System UNQ-SSQ3-A';
    $keeper_slug = 'uniq-automatic-control-system';
    $keeper_price = '22500.00';

    // Prove the catalog will still have the one correct UNIQ controller before
    // drafting anything. That is the destructive-operation identity guard.
    $keeper = get_post($keeper_id);
    $keeper_error = ntm_049_product_identity_error($keeper, $keeper_id, $keeper_title, $keeper_slug, $keeper_price, 'publish');
    if ($keeper_error !== '') {
        return ntm_049_fail($keeper_error . '. Refusing to draft duplicate.');
    }

    $duplicate = get_post($duplicate_id);
    $duplicate_error = ntm_049_product_identity_error($duplicate, $duplicate_id, $duplicate_title, $duplicate_slug, $duplicate_price);
    if ($duplicate_error !== '') {
        return ntm_049_fail($duplicate_error . '. Refusing to draft.');
    }

    if (!in_array((string) $duplicate->post_status, ['publish', 'draft'], true)) {
        return ntm_049_fail("id={$duplicate_id}: status mismatch - expected publish or draft, found {$duplicate->post_status}. Refusing to draft.");
    }

    $duplicate_label = ntm_049_decode_text($duplicate->post_title);
    $keeper_label = ntm_049_decode_text($keeper->post_title);

    if ($duplicate->post_status === 'draft') {
        echo "OK id={$duplicate_id}: already draft - {$duplicate_label}\n";
        echo "     keeper verified published: id={$keeper_id} {$keeper_label} (\${$keeper_price})\n";

        return 0;
    }

    if ($dry) {
        echo "DRY id={$duplicate_id}: would set to draft - {$duplicate_label} (\${$duplicate_price})\n";
        echo "     keeper verified published: id={$keeper_id} {$keeper_label} (\${$keeper_price})\n";

        return 0;
    }

    $result = wp_update_post(['ID' => $duplicate_id, 'post_status' => 'draft'], true);
    if (is_wp_error($result)) {
        return ntm_049_fail("id={$duplicate_id}: " . $result->get_error_message());
    }

    if ((int) $result !== $duplicate_id) {
        return ntm_049_fail("id={$duplicate_id}: wp_update_post returned unexpected result \"{$result}\".");
    }

    clean_post_cache($duplicate_id);

    $after = get_post($duplicate_id);
    if (!$after || (string) $after->post_status !== 'draft') {
        $after_status = $after ? (string) $after->post_status : 'missing';

        return ntm_049_fail("id={$duplicate_id}: status was not draft after update (found {$after_status}).");
    }

    echo "WROTE id={$duplicate_id}: set to draft - {$duplicate_label}\n";
    echo "     keeper verified published: id={$keeper_id} {$keeper_label}\n";

    return 0;
}

if (!defined('NTM_DB_MIGRATION_TEST')) {
    exit(ntm_049_run(getenv('NTM_DRY_RUN') !== '0'));
}
