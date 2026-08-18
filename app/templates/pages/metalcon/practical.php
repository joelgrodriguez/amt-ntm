<?php
/**
 * METALCON 2026 — practical show details and final meeting CTA.
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$config = isset($args['config']) && is_array($args['config']) ? $args['config'] : [];

if (!isset($config['booth_number'], $config['demo_length'], $config['demo_format'], $config['demo_schedule']) || !is_array($config['demo_schedule'])) {
    return;
}

$booth_number = (string) $config['booth_number'];
$demo_length  = (string) $config['demo_length'];
$demo_format  = (string) $config['demo_format'];
$demo_schedule = $config['demo_schedule'];

$details = [
    [
        'label' => __('Booth', 'standard'),
        'value' => sprintf(__('Booth #%s · Hall South A', 'standard'), $booth_number),
    ],
    [
        'label' => __('Venue', 'standard'),
        'value' => __('Orange County Convention Center · 9899 International Drive, Orlando, FL 32819', 'standard'),
    ],
    [
        'label' => __('Show hours', 'standard'),
        'value' => __('Wednesday and Thursday, 10am-5pm · Friday, 10am-1pm', 'standard'),
    ],
    [
        'label' => __('Requested meeting', 'standard'),
        'value' => sprintf(__('%1$s %2$s · Exact time confirmed by phone or email', 'standard'), $demo_length, $demo_format),
    ],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="metalcon-practical-title">
    <div class="container section-content">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.65fr)] lg:items-end lg:gap-16">
            <div class="grid gap-8">
                <header class="section-header-left max-w-2xl">
                    <p class="section-eyebrow text-blue-300"><?php esc_html_e('Plan your visit', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="metalcon-practical-title" class="section-title text-white">
                        <?php esc_html_e('METALCON 2026 in Orlando', 'standard'); ?>
                    </h2>
                    <p class="text-lg text-blue-200">
                        <?php esc_html_e('October 7-9, 2026 · Orange County Convention Center', 'standard'); ?>
                    </p>
                </header>

                <div class="grid gap-4" aria-labelledby="metalcon-demo-schedule-title">
                    <div>
                        <h3 id="metalcon-demo-schedule-title" class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                            <?php esc_html_e('Live SSM demo schedule', 'standard'); ?>
                        </h3>
                        <p class="mt-2 text-base text-blue-200">
                            <?php
                            printf(
                                /* translators: %s: booth number. */
                                esc_html__('Visit booth #%s at one of these times.', 'standard'),
                                esc_html($booth_number)
                            );
                            ?>
                        </p>
                    </div>

                    <ul class="grid gap-px border border-blue-700 bg-blue-700 sm:grid-cols-3" role="list">
                        <?php foreach ($demo_schedule as $session) : ?>
                            <?php
                            $date  = isset($session['date']) ? (string) $session['date'] : '';
                            $times = isset($session['times']) && is_array($session['times']) ? $session['times'] : [];
                            if ($date === '' || $times === []) {
                                continue;
                            }
                            ?>
                            <li class="grid content-start gap-2 bg-blue-900 p-5">
                                <h4 class="text-base font-medium text-white"><?php echo esc_html($date); ?></h4>
                                <p class="text-sm leading-relaxed text-blue-200">
                                    <?php echo esc_html(implode(' · ', array_map('strval', $times))); ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <dl class="grid gap-px border border-blue-700 bg-blue-700 sm:grid-cols-2">
                    <?php foreach ($details as $detail) : ?>
                        <div class="grid content-start gap-2 bg-blue-900 p-5 md:p-6">
                            <dt class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                                <?php echo esc_html($detail['label']); ?>
                            </dt>
                            <dd class="text-base leading-relaxed text-white">
                                <?php echo esc_html($detail['value']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

            <div class="grid content-start gap-4 border-t border-blue-700 pt-8 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
                <p class="text-lg leading-relaxed text-blue-200">
                    <?php esc_html_e('Ask for the time before the show. Sales will follow up and lock in the details.', 'standard'); ?>
                </p>
                <a href="#metalcon-meeting-form" class="btn btn-primary w-full whitespace-nowrap sm:w-auto">
                    <?php esc_html_e('Save my spot', 'standard'); ?>
                    <?php icon('arrow-up', ['class' => 'h-5 w-5']); ?>
                </a>
                <a
                    href="https://metalcon26.mapyourshow.com/8_0/floorplan/"
                    class="inline-flex min-h-11 items-center gap-2 self-start text-base font-medium text-blue-200 underline decoration-blue-500 underline-offset-4 transition-colors hover:text-white"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <?php esc_html_e('Open the venue map', 'standard'); ?>
                    <?php icon('external-link', ['class' => 'h-4 w-4']); ?>
                </a>
            </div>
        </div>
    </div>
</section>
