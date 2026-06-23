<?php
/**
 * Content-export manifest — which files' copy goes into the review CSV.
 *
 * Maps each pillar / custom landing page to the theme files whose user-facing
 * strings belong to it. The export script (export-copy.php) reads this, expands
 * the globs, and pulls every __('...', 'standard') call out of each file into
 * one CSV row, grouped by page.
 *
 * Why a hand-curated manifest instead of globbing all of app/templates: the
 * content team only reviews the marketing-facing pillar/landing copy, not all
 * 218 template parts. This list IS the scope. Add a page here to widen it.
 *
 * Path entries are relative to the theme root and may be globs (shell-style,
 * via glob()). 'functions' names the get_*() helpers in app/inc/machines-data.php
 * whose returned arrays carry page copy (FAQs, pillars, ROI stats, etc.); the
 * export script extracts the __() strings from inside those functions' bodies.
 *
 * @package Standard
 * @return array<string, array{label: string, paths: array<int, string>, functions?: array<int, string>}>
 */

declare(strict_types=1);

return [
    'home' => [
        'label'     => 'Home',
        'paths'     => [
            'app/front-page.php',
            'app/templates/parts/front-page/*.php',
        ],
        'functions' => [
            'get_portability_pillars',
        ],
    ],

    'about' => [
        'label' => 'About',
        'paths' => [
            'app/page-about.php',
            'app/templates/parts/about/*.php',
        ],
    ],

    'machines' => [
        'label'     => 'Machines (overview)',
        'paths'     => [
            'app/page-machines.php',
            'app/templates/pages/machines/*.php',
        ],
        'functions' => [
            'get_ironclad_pillars',
            'get_faq_items',
            'get_roi_stats',
            'get_differentiators',
        ],
    ],

    'roof-wall' => [
        'label'     => 'Roof & Wall Panel Machines',
        'paths'     => [
            'app/page-roof-wall-panel-machines.php',
            'app/templates/pages/roof-wall/*.php',
        ],
        'functions' => [
            'get_roof_wall_faq_items',
        ],
    ],

    'gutter' => [
        'label'     => 'Seamless Gutter Machines',
        'paths'     => [
            'app/page-seamless-gutter-machines.php',
            'app/templates/pages/gutter/*.php',
        ],
        'functions' => [
            'get_gutter_faq_items',
        ],
    ],

    'uniq' => [
        'label'     => 'UNIQ Control System',
        'paths'     => [
            'app/page-uniq-control-system.php',
            'app/templates/pages/uniq/*.php',
        ],
        'functions' => [
            'get_uniq_detailed_features',
            'get_uniq_resources',
        ],
    ],

    'safety' => [
        'label'     => 'Safety',
        'paths'     => [
            'app/page-safety.php',
            'app/templates/pages/safety/*.php',
        ],
        'functions' => [
            'get_safety_systems',
        ],
    ],

    'trailer' => [
        'label' => 'Trailer',
        'paths' => [
            'app/page-trailer.php',
            'app/templates/pages/trailer/*.php',
        ],
    ],

    'choose' => [
        'label' => 'Choose Your Machine',
        'paths' => [
            'app/page-choose-your-machine.php',
            'app/templates/pages/choose/*.php',
        ],
    ],

    'start-here' => [
        'label' => 'Start Here',
        'paths' => [
            'app/page-start-here.php',
            'app/templates/pages/start-here/*.php',
        ],
    ],

    'finance' => [
        'label' => 'Finance Center',
        'paths' => [
            'app/page-finance-center.php',
            'app/templates/pages/finance-center/*.php',
        ],
    ],

    // Custom MACH II family landing page (its own hardcoded copy).
    'machii' => [
        'label' => 'MACH II Landing',
        'paths' => [
            'app/page-machii.php',
            'app/templates/pages/machii/*.php',
        ],
    ],

    // SSQ3 MultiPro product page. The per-machine copy lives in the data file
    // (hero, breakdown, fit, case study, specs, FAQ — almost all __()-wrapped);
    // the page is rendered by the shared woo product template, whose generic
    // labels are captured once under 'product-ui' below.
    'ssq3' => [
        'label' => 'SSQ3 MultiPro (product)',
        'paths' => [
            'app/data/machines/ssq3-multipro.php',
        ],
    ],

    // MACH II product pages (the three gutter variants), copy from data files.
    'mach-ii' => [
        'label' => 'MACH II (product)',
        'paths' => [
            'app/data/machines/mach-ii-5-gutter.php',
            'app/data/machines/mach-ii-6-gutter.php',
            'app/data/machines/mach-ii-combo-gutter.php',
        ],
    ],

    // Shared machine product-page UI labels (Specs, Request a Quote, etc.) —
    // rendered for every machine, so reviewed once here, not per machine.
    'product-ui' => [
        'label' => 'Machine Product UI (shared)',
        'paths' => [
            'app/templates/woo/product/single-machine.php',
            'app/templates/woo/product/parts/*.php',
        ],
    ],
];
