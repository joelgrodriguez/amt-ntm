<?php
/**
 * Section Header — shared
 *
 * One header block, one rhythm. Replaces the per-section reinvention of
 * eyebrow → title → lede → cta spacing across the front page. Front-page
 * sections vary in alignment (some left, one centered) and chrome (some
 * have a red-dot eyebrow, some plain), but the *vertical gaps* between
 * elements should be identical.
 *
 * Slots
 *   $args = [
 *     'id'           => 'why-own-title',          // required when used with aria-labelledby
 *     'align'        => 'left' | 'center',        // default 'left'
 *     'eyebrow'      => 'Why Own',                // optional
 *     'eyebrow_dot'  => true,                     // default true — red dot + mono label
 *     'title'        => 'Why own an NTM rollformer?',
 *     'title_tag'    => 'h2',                     // default 'h2'
 *     'lede'         => 'Buying panels …',        // optional plain string
 *     'lede_html'    => '<p>…</p>',               // optional escaped html (overrides lede)
 *     'cta'          => [                          // optional
 *       'label' => 'Talk to a Specialist',
 *       'url'   => '/contact/',
 *       'class' => 'btn btn-primary',             // default
 *     ],
 *     'max_width'    => 'max-w-xl',               // optional wrapper width cap
 *   ];
 *
 * Spacing rhythm
 *   - Internal gap (eyebrow → title → lede → cta): gap-6
 *   - One value, no -mt-2/-mt-4 push-up hacks. The CTA gets the same gap
 *     as everything else because the eye reads it as the next step, not
 *     a postscript.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$args = wp_parse_args($args ?? [], [
    'id'          => '',
    'align'       => 'left',
    'eyebrow'     => '',
    'eyebrow_dot' => true,
    'title'       => '',
    'title_tag'   => 'h2',
    'lede'        => '',
    'lede_html'   => '',
    'cta'         => null,
    'max_width'   => '',
]);

if ($args['title'] === '') {
    return;
}

$align        = $args['align'] === 'center' ? 'center' : 'left';
$wrapper_base = 'grid gap-6';
$wrapper_align = $align === 'center' ? 'text-center justify-items-center' : '';
$wrapper_width = $args['max_width']
    ?: ($align === 'center' ? 'max-w-2xl mx-auto' : '');

$wrapper_class = trim("{$wrapper_base} {$wrapper_align} {$wrapper_width}");

$title_tag = preg_match('/^h[1-6]$/', $args['title_tag']) ? $args['title_tag'] : 'h2';
$title_id  = $args['id'] !== '' ? sprintf(' id="%s"', esc_attr($args['id'])) : '';

$cta = is_array($args['cta']) && !empty($args['cta']['label']) && !empty($args['cta']['url'])
    ? wp_parse_args($args['cta'], ['class' => 'btn btn-primary'])
    : null;
?>

<div class="<?php echo esc_attr($wrapper_class); ?>">

    <?php if ($args['eyebrow'] !== '') : ?>
        <?php if ($args['eyebrow_dot']) : ?>
            <div class="flex items-center gap-3 <?php echo $align === 'center' ? 'justify-center' : ''; ?>">
                <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                <p class="font-mono uppercase tracking-wider text-xs text-blue-700 m-0">
                    <?php echo esc_html($args['eyebrow']); ?>
                </p>
            </div>
        <?php else : ?>
            <p class="section-eyebrow m-0">
                <?php echo esc_html($args['eyebrow']); ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <<?php echo $title_tag; ?><?php echo $title_id; ?> class="section-title m-0">
        <?php echo wp_kses($args['title'], ['br' => ['class' => []]]); ?>
    </<?php echo $title_tag; ?>>

    <?php if ($args['lede_html'] !== '') : ?>
        <div class="font-sans text-blue-600 text-base lg:text-lg leading-relaxed <?php echo $align !== 'center' ? 'max-w-xl' : ''; ?>">
            <?php echo wp_kses_post($args['lede_html']); ?>
        </div>
    <?php elseif ($args['lede'] !== '') : ?>
        <p class="font-sans text-blue-600 text-base lg:text-lg leading-relaxed m-0 <?php echo $align !== 'center' ? 'max-w-xl' : ''; ?>">
            <?php echo esc_html($args['lede']); ?>
        </p>
    <?php endif; ?>

    <?php if ($cta) : ?>
        <div class="flex <?php echo $align === 'center' ? 'justify-center' : ''; ?>">
            <a
                href="<?php echo esc_url(\Standard\Url\internal($cta['url'])); ?>"
                class="<?php echo esc_attr($cta['class']); ?>"
            >
                <?php echo esc_html($cta['label']); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
            </a>
        </div>
    <?php endif; ?>

</div>
