/**
 * Custom Webpack Config for Smooth Maintenance.
 * Extends @wordpress/scripts to handle multiple entry points (admin app + blocks).
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
    ...defaultConfig,
    entry: {
        'admin': path.resolve(process.cwd(), 'assets/admin/src', 'index.js'),
        'blocks/countdown/index': path.resolve(process.cwd(), 'assets/blocks/countdown', 'index.js'),
        'blocks/countdown/view': path.resolve(process.cwd(), 'assets/blocks/countdown', 'view.js'),
        'blocks/subscriber-form/index': path.resolve(process.cwd(), 'assets/blocks/subscriber-form', 'index.js'),
        'blocks/subscriber-form/view': path.resolve(process.cwd(), 'assets/blocks/subscriber-form', 'view.js'),
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve(process.cwd(), 'build'),
    },
    plugins: [
        ...defaultConfig.plugins,
        new CopyPlugin({
            patterns: [
                {
                    from: 'assets/blocks/countdown/block.json',
                    to: 'blocks/countdown/block.json',
                },
                {
                    from: 'assets/blocks/subscriber-form/block.json',
                    to: 'blocks/subscriber-form/block.json',
                },
            ],
        }),
    ],
};
