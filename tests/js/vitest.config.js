/**
 * Vitest configuration.
 *
 * @package SmoothMaintenance
 */

import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        environment: 'jsdom',
        globals: true,
        coverage: {
            provider: 'v8',
            include: ['assets/admin/src/**/*.js'],
            exclude: ['assets/admin/src/index.js'],
        },
    },
});
