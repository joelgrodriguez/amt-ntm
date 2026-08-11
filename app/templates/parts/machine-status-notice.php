<?php
/**
 * Compact discontinued-machine notice with one replacement action.
 *
 * @package Standard
 * @var array{machine_slug?:string,context?:string,contained?:bool} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine_slug = (string) ($args['machine_slug'] ?? '');
$context      = (string) ($args['context'] ?? 'resource');
$contained    = !empty($args['contained']);
$status       = \Standard\MachineStatus\get_status($machine_slug);

if ($status === null) {
    return;
}

$copy = match ($context) {
    'sales' => __('The SSQ II MultiPro is discontinued. The SSQ3 MultiPro is its current replacement.', 'standard'),
    'support' => __('The SSQ II is discontinued. Manuals, parts, and service remain available for current owners.', 'standard'),
    'archive' => __('The SSQ II is discontinued. Parts and owner resources remain available; new machine buyers should explore the SSQ3.', 'standard'),
    default => __('The SSQ II is discontinued. This resource remains available for current owners.', 'standard'),
};

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
                <?php echo esc_html($copy); ?>
            </p>
        </div>
        <a href="<?php echo esc_url(\Standard\MachineStatus\get_replacement_url($machine_slug)); ?>" class="btn btn-primary shrink-0">
            <?php esc_html_e('Explore SSQ3', 'standard'); ?>
            <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
        </a>
    </div>
</aside>
