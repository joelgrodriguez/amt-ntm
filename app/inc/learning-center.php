<?php
/**
 * Learning Center module loader.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LearningCenter;

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/learning-center/config.php';
require_once __DIR__ . '/learning-center/filters.php';
require_once __DIR__ . '/learning-center/queries.php';
