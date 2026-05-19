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
    'title'     => __('Find the machine that fits your shop.', 'standard'),
    'submit'    => __('See My Machine', 'standard'),
    'help'      => __('Or talk to a specialist', 'standard'),
    'form_aria' => __('Find your machine', 'standard'),
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

$fields = [
    [
        'name'        => 'profile',
        'label'       => __('Panel profile', 'standard'),
        'placeholder' => __('What do you roll?', 'standard'),
        'options'     => $profiles,
    ],
    [
        'name'        => 'width',
        'label'       => __('Coil width', 'standard'),
        'placeholder' => __('What stock do you use?', 'standard'),
        'options'     => $widths,
    ],
    [
        'name'        => 'volume',
        'label'       => __('Volume', 'standard'),
        'placeholder' => __('How busy are you?', 'standard'),
        'options'     => $volumes,
    ],
];
?>

<section class="hero-router" aria-labelledby="hero-router-title">
    <div class="container hero-router__inner">
        <form
            action="<?php echo esc_url(\Standard\Url\internal('/configurator/')); ?>"
            method="get"
            class="hero-router__form"
            aria-label="<?php echo esc_attr($content['form_aria']); ?>"
        >
            <div class="hero-router__header">
                <div class="hero-router__heading">
                    <h2 id="hero-router-title" class="hero-router__title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                </div>
                <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="hero-router__help">
                    <?php echo esc_html($content['help']); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="hero-router__grid">
                <?php foreach ($fields as $field) : ?>
                    <label class="hero-router__field">
                        <span class="hero-router__label">
                            <?php echo esc_html($field['label']); ?>
                        </span>
                        <select name="<?php echo esc_attr($field['name']); ?>" class="hero-router__select" required>
                            <option value="" disabled selected><?php echo esc_html($field['placeholder']); ?></option>
                            <?php foreach ($field['options'] as $v => $l) : ?>
                                <option value="<?php echo esc_attr($v); ?>"><?php echo esc_html($l); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <?php endforeach; ?>

                <div class="hero-router__submit-cell">
                    <button type="submit" class="btn btn-primary hero-router__submit">
                        <?php echo esc_html($content['submit']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
