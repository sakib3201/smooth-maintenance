/**
 * Production build script.
 *
 * Creates a clean distribution folder and zip file for WordPress.org submission.
 *
 * Usage: npm run production-build
 *
 * Output:
 *   plugins/dist/smooth-maintenance/          (clean plugin folder)
 *   plugins/dist/smooth-maintenance-x.x.x.zip (ready-to-submit zip)
 */

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { createWriteStream } from 'fs';
import { createHash } from 'crypto';
import { fileURLToPath } from 'url';

// ─── Config ─────────────────────────────────────────────────────────────────

const PLUGIN_SLUG = 'smooth-maintenance';
const __filename = fileURLToPath(import.meta.url);
const PLUGIN_DIR = path.dirname(path.dirname(__filename));
const DIST_ROOT = path.resolve(PLUGIN_DIR, '..', 'dist');
const DIST_PLUGIN = path.join(DIST_ROOT, PLUGIN_SLUG);

// Files/folders to EXCLUDE from the distribution package.
const EXCLUDE = new Set([
    '.git',
    '.github',
    '.claude',
    '.playwright',
    'node_modules',
    'vendor',         // Will be re-installed --no-dev below
    'tests',
    'docs',
    'bin',
    'assets',         // src only — build/ is included
    '.gitignore',
    '.phpcs.xml.dist',
    'phpunit.xml',
    'package.json',
    'package-lock.json',
    'composer.json',
    'composer.lock',
    'smooth-maintanence-implementation-plan.md',
]);

// ─── Helpers ─────────────────────────────────────────────────────────────────

function info(msg) { console.log(`\x1b[36mℹ\x1b[0m  ${msg}`); }
function success(msg) { console.log(`\x1b[32m✔\x1b[0m  ${msg}`); }
function step(msg) { console.log(`\n\x1b[1m▸ ${msg}\x1b[0m`); }
function run(cmd, cwd = PLUGIN_DIR) {
    execSync(cmd, { cwd, stdio: 'inherit' });
}

/** Read the version from the main plugin file header. */
function getVersion() {
    const mainFile = path.join(PLUGIN_DIR, `${PLUGIN_SLUG}.php`);
    const content = fs.readFileSync(mainFile, 'utf8');
    const match = content.match(/^\s*\*\s*Version:\s*(.+)$/m);
    if (!match) throw new Error('Could not read Version from plugin header.');
    return match[1].trim();
}

/** Recursively copy a directory, honouring the EXCLUDE set at top level. */
function copyDir(src, dest, isTopLevel = false) {
    fs.mkdirSync(dest, { recursive: true });

    for (const entry of fs.readdirSync(src)) {
        if (isTopLevel && EXCLUDE.has(entry)) continue;

        const srcPath = path.join(src, entry);
        const destPath = path.join(dest, entry);
        const stat = fs.statSync(srcPath);

        if (stat.isDirectory()) {
            copyDir(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
        }
    }
}

/** Create a zip from a directory using the built-in `zip` (Linux/Mac) or PowerShell (Windows). */
function createZip(sourceDir, zipPath) {
    const isWindows = process.platform === 'win32';

    if (isWindows) {
        // PowerShell Compress-Archive: zip the *contents under the slug folder*
        run(
            `powershell -NoProfile -Command "Compress-Archive -Path '${sourceDir}' -DestinationPath '${zipPath}' -Force"`,
            DIST_ROOT
        );
    } else {
        const zipName = path.basename(zipPath);
        const dirName = path.basename(sourceDir);
        run(`zip -r "${zipName}" "${dirName}"`, DIST_ROOT);
    }
}

// ─── Main ────────────────────────────────────────────────────────────────────

async function main() {
    console.log('\n\x1b[1m\x1b[35m📦 Smooth Maintenance — Production Build\x1b[0m\n');

    const version = getVersion();
    const zipName = `${PLUGIN_SLUG}-${version}.zip`;
    const zipPath = path.join(DIST_ROOT, zipName);

    info(`Plugin version : ${version}`);
    info(`Output folder  : ${DIST_PLUGIN}`);
    info(`Zip file       : ${zipPath}`);

    // 1. Build JS assets.
    step('Building production JS/CSS assets…');
    run('npm run build');
    success('Assets built.');

    // 2. Install Composer production deps (no dev).
    step('Installing Composer production dependencies…');
    run('composer install --no-dev --optimize-autoloader --no-interaction');
    success('Composer done.');

    // 3. Clean dist directory.
    step('Preparing dist directory…');
    if (fs.existsSync(DIST_PLUGIN)) {
        fs.rmSync(DIST_PLUGIN, { recursive: true, force: true });
    }
    fs.mkdirSync(DIST_PLUGIN, { recursive: true });
    success('Dist directory ready.');

    // 4. Copy plugin files.
    step('Copying plugin files…');
    copyDir(PLUGIN_DIR, DIST_PLUGIN, true);
    success('Files copied.');

    // 5. Remove any existing zip and create a new one.
    step('Creating zip archive…');
    if (fs.existsSync(zipPath)) {
        fs.rmSync(zipPath);
    }
    createZip(DIST_PLUGIN, zipPath);
    success(`Zip created: ${zipName}`);

    // 6. Summary.
    const zipSize = (fs.statSync(zipPath).size / 1024).toFixed(1);
    console.log(`\n\x1b[32m\x1b[1m✔ Done!\x1b[0m  ${zipName}  (${zipSize} KB)\n`);
}

main().catch((err) => {
    console.error('\x1b[31mBuild failed:\x1b[0m', err.message);
    process.exit(1);
});
