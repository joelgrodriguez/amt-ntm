<?php
/**
 * Hero Router — Mini-Configurator Strip
 *
 * Sits directly below the hero slider. Three dropdowns (profile,
 * coil width, volume) submit GET params to /configurator/.
 *
 * Light surface (bg-blue-50) breaks rhythm cleanly under the dark
 * hero. Hairline-bordered controls, mono labels, no decoration.
 * Reads as a spec-sheet picker, not a marketing widget.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Not sure which machine?', 'standard'),
    'title'   => __('Three answers. One machine.', 'standard'),
    'submit'  => __('See My Machine', 'standard'),
    'help'    => __('Or talk to a specialist', 'standard'),
];

$profiles = [
    'standing-seam'   => __('Standing Seam', 'standard'),
    'snap-lock'       => __('Snap-Lock', 'standard'),
    'mechanical-lock' => __('Mechanical Lock', 'standard'),
    'corrugated'      => __('Corrugated / R-Panel', 'standard'),
    'box-gutter'      => __('Box / Seamless Gutter', 'standard'),
];
$widths = [
    '12-16'   => __('12 to 16 inches', 'standard'),
    '16-20'   => __('16 to 20 inches', 'standard'),
    '20-plus' => __('20 inches or wider', 'standard'),
];
$volumes = [
    'small'  => __('Up to 5 jobs / month', 'standard'),
    'medium' => __('5 to 15 jobs / month', 'standard'),
    'large'  => __('15+ jobs / month', 'standard'),
];
?>

<section class="hero-router bg-blue-50 border-y border-blue-200" aria-labelledby="hero-router-title">
    <div class="container py-8 lg:py-10">
        <form action="<?php echo esc_url(\Standard\Url\internal('/configurator/')); ?>" method="get" class="grid gap-6">

            <!-- Header row: eyebrow + title -->
            <div class="grid gap-2 md:flex md:items-baseline md:justify-between md:gap-6">
                <div class="grid gap-1">
                    <span class="font-mono uppercase tracking-wider text-blue-500" style="font-size: var(--text-caption);">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </span>
                    <h2 id="hero-router-title" class="font-sans font-medium text-blue-700" style="font-size: var(--text-heading); line-height: var(--leading-heading);">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                </div>
                <a href="#contact" class="hero-router__help font-mono uppercase tracking-wider text-blue-500 hover:text-blue-700 transition-colors self-start md:self-end" style="font-size: var(--text-caption);">
                    <?php echo esc_html($content['help']); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Fields row -->
            <div class="grid gap-px bg-blue-200 border border-blue-200 md:grid-cols-[1fr_1fr_1fr_auto]">

                <label class="hero-router__field bg-white">
                    <span class="hero-router__label">
                        <?php esc_html_e('01 / Panel profile', 'standard'); ?>
                    </span>
                    <select name="profile" class="hero-router__select">
                        <option value=""><?php esc_html_e('What do you roll?', 'standard'); ?></option>
                        <?php foreach ($profiles as $v => $l) : ?>
                            <option value="<?php echo esc_attr($v); ?>"><?php echo esc_html($l); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="hero-router__field bg-white">
                    <span class="hero-router__label">
                        <?php esc_html_e('02 / Coil width', 'standard'); ?>
                    </span>
                    <select name="width" class="hero-router__select">
                        <option value=""><?php esc_html_e('What stock do you use?', 'standard'); ?></option>
                        <?php foreach ($widths as $v => $l) : ?>
                            <option value="<?php echo esc_attr($v); ?>"><?php echo esc_html($l); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="hero-router__field bg-white">
                    <span class="hero-router__label">
                        <?php esc_html_e('03 / Volume', 'standard'); ?>
                    </span>
                    <select name="volume" class="hero-router__select">
                        <option value=""><?php esc_html_e('How busy are you?', 'standard'); ?></option>
                        <?php foreach ($volumes as $v => $l) : ?>
                            <option value="<?php echo esc_attr($v); ?>"><?php echo esc_html($l); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <div class="bg-white p-1 flex">
                    <button type="submit" class="btn btn-primary w-full md:w-auto md:h-full">
                        <?php echo esc_html($content['submit']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
