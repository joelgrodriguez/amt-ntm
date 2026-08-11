<?php
/**
 * Compact machine lifecycle notice with primary and secondary actions.
 *
 * @package Standard
 * @var array{machine_slug?:string,context?:string,contained?:bool} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine_slug = (string) ($args['machine_slug'] ?? '');
$contained    = !empty($args['contained']);
$status       = \Standard\MachineStatus\get_status($machine_slug);

if ($status === null) {
    return;
}

$root_classes = $contained
    ? 'border-x border-b border-blue-200 bg-white p-5 lg:p-6'
    : 'border-b border-blue-200 bg-white';
$inner_classes = $contained
    ? 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between'
    : 'container flex flex-col gap-4 py-5 sm:flex-row sm:items-center sm:justify-between';
?>

<aside class="<?php echo esc_attr($root_classes); ?>" aria-label="<?php esc_attr_e('Machine status', 'standard'); ?>">
    <div class="<?php echo esc_attr($inner_classes); ?>">
        <div class="grid gap-1.5 max-w-3xl">
            <p class="m-0 inline-flex items-center gap-2 font-mono font-medium uppercase tracking-wider text-red" style="font-size: var(--text-caption);">
                <span class="inline-block size-1.5 bg-red" aria-hidden="true"></span>
                <?php echo esc_html($status['label']); ?>
            </p>
            <p class="m-0 font-sans text-blue-700" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('This is your last chance to purchase an SSQ II MultiPro before it is discontinued.', 'standard'); ?>
                <span class="lg:block"><?php esc_html_e('Build and request your quote today.', 'standard'); ?></span>
            </p>
        </div>
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
            <a href="<?php echo esc_url(\Standard\MachineStatus\get_configurator_url($machine_slug)); ?>" class="btn btn-primary">
                <?php esc_html_e('Build & Quote SSQ II', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </a>
            <a href="<?php echo esc_url(\Standard\MachineStatus\get_replacement_url($machine_slug)); ?>" class="btn btn-secondary">
                <?php esc_html_e('Explore SSQ3', 'standard'); ?>
            </a>
        </div>
    </div>
</aside>
