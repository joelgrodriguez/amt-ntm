import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';
import { writeFileSync, unlinkSync } from 'fs';
import { networkInterfaces } from 'os';
import { themeJsonToCss } from './vite-plugin-theme-json.js';

/**
 * Get the local network IP address.
 *
 * @returns {string} Local IP or localhost fallback.
 */
function getLocalIP() {
  const nets = networkInterfaces();
  for (const name of Object.keys(nets)) {
    for (const net of nets[name]) {
      // Skip internal and non-IPv4 addresses
      if (net.family === 'IPv4' && !net.internal) {
        return net.address;
      }
    }
  }
  return 'localhost';
}

const DEV_SERVER_FILE = resolve(__dirname, 'app/.vite-dev-server');

export default defineConfig(({ command }) => {
  return {
    base: './',
    plugins: [
      themeJsonToCss({ themeJsonPath: './app/theme.json' }),
      tailwindcss(),
      // Write dev server URL for PHP to read
      {
        name: 'vite-dev-server-url',
        configureServer(server) {
          const ip = getLocalIP();

          server.httpServer?.once('listening', () => {
            const address = server.httpServer?.address();
            const port = typeof address === 'object' && address !== null
              ? address.port
              : server.config.server.port || 5173;
            const url = `http://${ip}:${port}`;

            writeFileSync(DEV_SERVER_FILE, url);
            console.log(`\n  Dev server URL written to app/.vite-dev-server`);
            console.log(`  WordPress will load assets from: ${url}\n`);
          });
        },
        buildStart() {
          // Clean up dev server file on build
          if (command === 'build') {
            try {
              unlinkSync(DEV_SERVER_FILE);
            } catch {
              // File doesn't exist, ignore
            }
          }
        },
      },
    ],

    build: {
      assetsInlineLimit: 0,
      outDir: 'app/dist',
      emptyOutDir: true,
      manifest: true,
      sourcemap: 'hidden',
      rollupOptions: {
        input: {
          app: resolve(__dirname, 'app/resources/js/_app.js'),
          frontPage: resolve(__dirname, 'app/resources/js/front-page.js'),
          machineProduct: resolve(__dirname, 'app/resources/js/machine-product.js'),
          woocommerce: resolve(__dirname, 'app/resources/css/woo.css'),
          financeCenter: resolve(__dirname, 'app/resources/css/pages/finance-center.css'),
          editor: resolve(__dirname, 'app/resources/css/editor.css'),
        },
        output: {
          entryFileNames: 'js/[name].[hash].js',
          chunkFileNames: 'js/[name].[hash].js',
          assetFileNames: (assetInfo) => {
            if (assetInfo.names?.[0]?.endsWith('.css')) {
              return 'css/[name].[hash][extname]';
            }
            return 'assets/[name].[hash][extname]';
          },
        },
      },
    },

    server: {
      host: '0.0.0.0',
      port: 5173,
      // WordPress reads the actual URL from app/.vite-dev-server, so it is safe
      // to use the next free port when another local Vite app owns 5173.
      strictPort: false,
      cors: true,
    },
  };
});
