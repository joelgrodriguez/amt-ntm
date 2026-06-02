<?php
/**
 * Roof Panel vs Gutter — Keep Reading
 *
 * Curated internal-link rail. Pure SEO/topical-authority play: hand-
 * picked, live Learning Center articles that go deeper on each lane, so
 * a researching buyer (and a crawler) can follow the cluster. All slugs
 * verified against the live database.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$groups = [
    [
        'heading' => __('Roof &amp; wall panel machines', 'standard'),
        'links'   => [
            ['title' => __('SSH™ vs. SSR™: residential &amp; light commercial roof panel machines', 'standard'), 'slug' => 'ssh-vs-ssr-roof-panel-machines'],
            ['title' => __('Best residential roof panel machines for starting a business', 'standard'), 'slug' => 'best-residential-roof-panel-machines-for-starting-a-business'],
            ['title' => __('Standing seam profiles: snap lock vs. mechanical seam', 'standard'), 'slug' => 'standing-seam-metal-roof-profiles-snap-lock-vs-mechanical-seam'],
        ],
    ],
    [
        'heading' => __('Seamless gutter machines', 'standard'),
        'links'   => [
            ['title' => __('DIY prefab gutters vs. a portable seamless gutter machine', 'standard'), 'slug' => 'diy-prefab-gutters-vs-a-portable-seamless-gutter-machine'],
            ['title' => __('Best portable seamless gutter machines', 'standard'), 'slug' => 'best-portable-seamless-gutter-machines-in-2025'],
            ['title' => __('Which is the best gutter metal? Aluminum vs. steel vs. copper', 'standard'), 'slug' => 'which-is-the-best-gutter-metal-aluminum-vs-steel-vs-copper-vs-zinc-vs-galvalume'],
        ],
    ],
    [
        'heading' => __('New to rollforming', 'standard'),
        'links'   => [
            ['title' => __('What is a portable rollforming machine? Types &amp; uses', 'standard'), 'slug' => 'portable-rollforming-machine-equipment-types-uses'],
            ['title' => __('Top 5 misconceptions about portable rollforming', 'standard'), 'slug' => 'portable-rollforming-misconceptions'],
            ['title' => __('Browse the full Learning Center', 'standard'), 'slug' => null, 'url' => '/learning-center/'],
        ],
    ],
];
?>

<section class="section bg-blue-50 border-t border-blue-200" aria-labelledby="vs-keep-reading-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Keep reading', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="vs-keep-reading-title" class="section-title">
                <?php esc_html_e('Go Deeper on Either Machine', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($groups as $group) : ?>
                <div class="flex flex-col gap-5 bg-blue-50 p-6 lg:p-8">
                    <h3 class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                        <?php echo wp_kses_post($group['heading']); ?>
                    </h3>
                    <ul class="grid gap-4">
                        <?php foreach ($group['links'] as $link) :
                            $href = !empty($link['url']) ? $link['url'] : '/' . $link['slug'] . '/';
                        ?>
                            <li>
                                <a
                                    href="<?php echo esc_url(\Standard\Url\internal($href)); ?>"
                                    class="group flex items-start gap-3 text-base text-blue-700 transition-colors hover:text-blue-500"
                                >
                                    <span class="mt-1 shrink-0 text-blue-400 transition-colors group-hover:text-blue-500" aria-hidden="true">
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </span>
                                    <span class="text-pretty"><?php echo wp_kses_post($link['title']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
