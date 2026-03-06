/**
 * Store actions for maintenance settings.
 *
 * @package SmoothMaintenance
 */

import apiFetch from '@wordpress/api-fetch';

/**
 * Set settings in store state.
 *
 * @param {Object} settings Settings object.
 * @return {Object} Action object.
 */
export const setSettings = (settings) => ({
    type: 'SET_SETTINGS',
    settings,
});

/**
 * Set saving state flag.
 *
 * @param {boolean} isSaving Whether currently saving.
 * @return {Object} Action object.
 */
export const setSaving = (isSaving) => ({
    type: 'SET_SAVING',
    isSaving,
});

/**
 * Set error state.
 *
 * @param {string|null} error Error message or null.
 * @return {Object} Action object.
 */
export const setError = (error) => ({
    type: 'SET_ERROR',
    error,
});

/**
 * Async action: Update settings via REST API.
 *
 * @param {Object} settings Settings to update.
 * @return {Function} Thunk function.
 */
export const updateSettings = (settings) => async ({ dispatch }) => {
    dispatch(setSaving(true));

    try {
        const response = await apiFetch({
            path: '/smooth-maintenance/v1/settings',
            method: 'POST',
            data: settings,
        });

        if (response.success && response.data) {
            dispatch(setSettings(response.data));
        }

        dispatch(setSaving(false));
        return response;
    } catch (error) {
        dispatch(setError(error.message || 'Failed to save settings.'));
        dispatch(setSaving(false));
        throw error;
    }
};
