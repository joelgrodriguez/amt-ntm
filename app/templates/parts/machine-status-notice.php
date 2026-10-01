<?php
/**
 * Compact machine lifecycle notice. Leads with the current replacement model;
 * the retirement date is secondary context.
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
                <?php esc_html_e('Current model', 'standard'); ?>
            </p>
            <p class="m-0 font-sans text-blue-700" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('The SSQ3 MultiPro is NTM’s current 16-profile roof and wall panel machine.', 'standard'); ?>
                <span class="lg:block"><?php esc_html_e('The SSQ II MultiPro was discontinued September 30, 2026.', 'standard'); ?></span>
            </p>
        </div>
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
            <a href="<?php echo esc_url(\Standard\MachineStatus\get_replacement_url($machine_slug)); ?>" class="btn btn-primary">
                <?php esc_html_e('Explore SSQ3 MultiPro', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </a>
            <a href="<?php echo esc_url(\Standard\MachineStatus\get_replacement_configurator_url($machine_slug)); ?>" class="btn btn-secondary">
                <?php esc_html_e('Build & Quote SSQ3', 'standard'); ?>
            </a>
        </div>
    </div>
</aside>
