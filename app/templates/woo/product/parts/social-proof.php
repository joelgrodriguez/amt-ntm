<?php
/**
 * Machine Product — Social Proof
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine      = $args['machine'] ?? [];
$testimonials = $machine['testimonials'] ?? [];

if (empty($testimonials)) {
    return;
}
?>

<section class="section bg-slate-900" aria-labelledby="social-proof-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">Customer Stories</p>
            <h2 id="social-proof-title" class="text-3xl font-bold text-white md:text-4xl">Trusted by Contractors Nationwide</h2>
        </div>

        <div class="grid md:grid-cols-<?php echo esc_attr((string) min(count($testimonials), 3)); ?> gap-8">
            <?php foreach ($testimonials as $testimonial) :
                $name     = $testimonial['name'] ?? '';
                $company  = $testimonial['company'] ?? '';
                $location = $testimonial['location'] ?? '';
                $quote    = $testimonial['quote'] ?? '';
            ?>
                <blockquote class="border border-slate-700 p-6 grid gap-4">
                    <p class="text-slate-300 italic">&ldquo;<?php echo esc_html($quote); ?>&rdquo;</p>
                    <footer class="text-sm text-slate-400">
                        <strong class="text-white"><?php echo esc_html($name); ?></strong><?php
                        if (!empty($company)) {
                            echo ', ' . esc_html($company);
                        }
                        if (!empty($location)) {
                            echo ', ' . esc_html($location);
                        }
                        ?>
                    </footer>
                </blockquote>
            <?php endforeach; ?>
        </div>

    </div>
</section>
