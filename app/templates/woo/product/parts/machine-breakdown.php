<?php
/**
 * Machine Product — Machine Breakdown
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? [];
$breakdown = $machine['breakdown'] ?? [];

if (empty($breakdown)) {
    return;
}
?>

<section id="machine-breakdown" class="section" aria-labelledby="breakdown-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">Inside the Machine</p>
            <div class="section-divider-center"></div>
            <h2 id="breakdown-title" class="section-title">Built to Perform</h2>
        </div>

        <?php foreach ($breakdown as $idx => $sub) :
            $is_reversed = $idx % 2 !== 0;
            $image       = $sub['image'] ?? '';
            $title       = $sub['title'] ?? '';
            $headline    = $sub['headline'] ?? '';
            $copy        = $sub['copy'] ?? '';
            $specs       = $sub['specs'] ?? [];
        ?>
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center <?php echo $is_reversed ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
                <?php if (!empty($image)) : ?>
                    <div class="aspect-video overflow-hidden">
                        <img src="<?php echo esc_url($image); ?>"
                             alt="<?php echo esc_attr($title); ?>"
                             class="w-full h-full object-cover">
                    </div>
                <?php else : ?>
                    <div class="bg-slate-100 aspect-video flex items-center justify-center">
                        <span class="text-slate-400 text-sm font-mono"><?php echo esc_html($title); ?></span>
                    </div>
                <?php endif; ?>
                <div class="grid gap-4">
                    <p class="text-sm font-semibold uppercase tracking-wider text-secondary"><?php echo esc_html($title); ?></p>
                    <h3 class="text-2xl font-bold text-slate-900 lg:text-3xl"><?php echo esc_html($headline); ?></h3>
                    <p class="text-slate-600"><?php echo esc_html($copy); ?></p>
                    <?php if (!empty($specs)) : ?>
                        <ul class="grid gap-2 mt-2">
                            <?php foreach ($specs as $spec) : ?>
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>
                                    <?php echo esc_html($spec); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>
