import { readFileSync, writeFileSync } from 'fs';
import { resolve } from 'path';

/**
 * Vite plugin to read WordPress theme.json and generate CSS variables
 * for Tailwind CSS v4 @theme directive.
 *
 * Generates a physical CSS file (tokens.css) that can be imported normally.
 *
 * @param {Object} options - Plugin options
 * @param {string} options.themeJsonPath - Path to theme.json relative to project root
 * @param {string} options.outputPath - Path to output CSS file
 * @returns {import('vite').Plugin}
 */
export function themeJsonToCss(options = {}) {
  const {
    themeJsonPath = './app/theme.json',
    outputPath = './app/resources/css/tokens.css'
  } = options;

  let resolvedThemeJsonPath;
  let resolvedOutputPath;

  function generateThemeVars() {
    try {
      const themeJsonContent = readFileSync(resolvedThemeJsonPath, 'utf-8');
      const themeJson = JSON.parse(themeJsonContent);
      const css = generateCssFromThemeJson(themeJson);
      writeFileSync(resolvedOutputPath, css, 'utf-8');
      console.log('[vite-plugin-theme-json] Generated tokens.css from theme.json');
    } catch (error) {
      console.error('[vite-plugin-theme-json] Error:', error.message);
    }
  }

  return {
    name: 'vite-plugin-theme-json',

    configResolved(config) {
      resolvedThemeJsonPath = resolve(config.root, themeJsonPath);
      resolvedOutputPath = resolve(config.root, outputPath);

      // Generate on startup
      generateThemeVars();
    },

    configureServer(server) {
      // Watch theme.json for changes during dev
      server.watcher.add(resolvedThemeJsonPath);
      server.watcher.on('change', (path) => {
        if (path === resolvedThemeJsonPath) {
          generateThemeVars();
          // Trigger HMR reload
          server.ws.send({ type: 'full-reload' });
        }
      });
    },

    buildStart() {
      // Regenerate before each build
      generateThemeVars();
    }
  };
}

/**
 * Convert theme.json settings to CSS with @theme directive.
 *
 * @param {Object} themeJson - Parsed theme.json object
 * @returns {string} CSS string with @theme block
 */
function generateCssFromThemeJson(themeJson) {
  const settings = themeJson.settings || {};
  const cssVars = [];

  // Colors
  if (settings.color?.palette) {
    cssVars.push('  /* Colors */');
    settings.color.palette.forEach(({ slug, color }) => {
      cssVars.push(`  --color-${slug}: ${color};`);
    });
    cssVars.push('');
  }

  // Font families
  if (settings.typography?.fontFamilies) {
    cssVars.push('  /* Font Families */');
    settings.typography.fontFamilies.forEach(({ slug, fontFamily }) => {
      cssVars.push(`  --font-${slug}: ${fontFamily};`);
    });
    cssVars.push('');
  }

  // Font sizes
  if (settings.typography?.fontSizes) {
    cssVars.push('  /* Font Sizes */');
    settings.typography.fontSizes.forEach(({ slug, size }) => {
      const cssSlug = slug.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase());
      cssVars.push(`  --text-${cssSlug}: ${size};`);
    });
    cssVars.push('');
  }

  // Layout
  if (settings.layout) {
    cssVars.push('  /* Layout */');
    if (settings.layout.contentSize) {
      cssVars.push(`  --layout-content: ${settings.layout.contentSize};`);
    }
    if (settings.layout.wideSize) {
      cssVars.push(`  --layout-wide: ${settings.layout.wideSize};`);
    }
    cssVars.push('');
  }

  // Custom settings
  if (settings.custom) {
    cssVars.push('  /* Custom */');
    if (settings.custom.borderRadius !== undefined) {
      cssVars.push(`  --radius: ${settings.custom.borderRadius};`);
    }
  }

  return `/**
 * Auto-generated from theme.json
 * Do not edit directly - modify app/theme.json instead
 */
@theme {
${cssVars.join('\n')}
}
`;
}
