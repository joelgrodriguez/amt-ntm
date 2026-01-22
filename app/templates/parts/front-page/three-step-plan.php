<?php

declare(strict_types=1);

namespace Standard;

$steps = [
    [
        'number' => 1,
        'title'  => 'Schedule a Call',
        'text'   => 'Connect with an account specialist to discuss your equipment needs and budget.',
    ],
    [
        'number' => 2,
        'title'  => 'Get a Tailored Solution',
        'text'   => 'We\'ll design a custom financing package that fits your business requirements.',
    ],
    [
        'number' => 3,
        'title'  => 'Achieve Your Goals',
        'text'   => 'Gain efficiency, increase productivity, and grow your business with the right equipment.',
    ],
];
?>

<section class="py-16 bg-primary pattern-square-grid pattern-square-grid--primary md:py-20 lg:py-24" aria-labelledby="three-step-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container text-center">
        <p class="text-sm font-semibold uppercase tracking-wider text-white/80 mb-2">
            The 3 Step Plan
        </p>
        <div class="w-12 h-1 bg-secondary mx-auto mb-6"></div>

        <h2 id="three-step-title" class="text-3xl font-bold text-white mb-4 md:text-4xl">
            Your Path to Better Equipment Financing
        </h2>
        <p class="text-lg text-white/90 mb-12 max-w-2xl mx-auto">
            Working with NTM is simple, transparent, and designed around your needs.
        </p>

        <div class="grid gap-8 pt-6 md:grid-cols-3 md:gap-6 lg:gap-8">
            <?php foreach ($steps as $step) : ?>
                <div class="bg-white p-8 text-center">
                    <span class="inline-flex items-center justify-center w-14 h-14 bg-secondary text-white text-2xl font-bold -mt-14 mb-4">
                        <?php echo esc_html($step['number']); ?>
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">
                        <?php echo esc_html($step['title']); ?>
                    </h3>
                    <p class="text-slate-600">
                        <?php echo esc_html($step['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12">
            <a href="#contact" class="btn btn-secondary">
                Schedule a Call
            </a>
        </div>
    </div>
</section>
