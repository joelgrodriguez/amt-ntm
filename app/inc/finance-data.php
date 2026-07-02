<?php
/**
 * Finance Data
 *
 * Shared finance figures quoted across the theme, so every mention
 * interpolates one constant instead of hardcoding the number in copy.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Finance;

if (!defined('ABSPATH')) {
    exit;
}

// Section 179 deduction cap. This is a TAX-YEAR figure (the $1,220,000
// limit) — review annually when the IRS publishes the new-year limit and
// update this one constant; the Finance Center copy interpolates it.
const SECTION_179_CAP = 1220000;

/**
 * The Section 179 cap formatted for display, e.g. "$1,220,000".
 */
function section_179_cap(): string {
    return '$' . \number_format(SECTION_179_CAP);
}
