/**
 * Store selectors for maintenance settings.
 *
 * @package SmoothMaintenance
 */

/**
 * Get all settings.
 *
 * @param {Object} state Store state.
 * @return {Object} Settings object.
 */
export const getSettings = (state) => state.settings;

/**
 * Check if maintenance mode is enabled.
 *
 * @param {Object} state Store state.
 * @return {boolean} Whether maintenance mode is enabled.
 */
export const isMaintenanceEnabled = (state) => state.settings.maintenance_mode_enabled;

/**
 * Check if settings are currently being saved.
 *
 * @param {Object} state Store state.
 * @return {boolean} Whether currently saving.
 */
export const isSaving = (state) => state.isSaving;

/**
 * Check if settings have been loaded.
 *
 * @param {Object} state Store state.
 * @return {boolean} Whether settings have been loaded.
 */
export const hasLoaded = (state) => state.hasLoaded;

/**
 * Get current error.
 *
 * @param {Object} state Store state.
 * @return {string|null} Error message or null.
 */
export const getError = (state) => state.error;
