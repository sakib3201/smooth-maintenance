/**
 * Custom Webpack Config for Smooth Maintenance.
 * Extends @wordpress/scripts to handle multiple entry points (admin app + blocks).
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        'admin': path.resolve(process.cwd(), 'assets/admin/src', 'index.js'),
        'blocks/countdown/index': path.resolve(process.cwd(), 'assets/blocks/countdown', 'index.js'),
        'blocks/countdown/view': path.resolve(process.cwd(), 'assets/blocks/countdown', 'view.js'),
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve(process.cwd(), 'build'),
    },
};
