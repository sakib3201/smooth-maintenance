/**
 * Store resolvers - auto-fetch data on first selector call.
 *
 * @package SmoothMaintenance
 */

import apiFetch from '@wordpress/api-fetch';
import { setSettings, setError } from './actions';

/**
 * Resolver for getSettings selector.
 * Automatically fetches settings from REST API on first access.
 *
 * @return {Function} Thunk function.
 */
export const getSettings = () => async ({ dispatch }) => {
    try {
        const response = await apiFetch({
            path: '/smooth-maintenance/v1/settings',
        });

        if (response.success && response.data) {
            dispatch(setSettings(response.data));
        } else if (response.maintenance_mode_enabled !== undefined) {
            // Direct response format.
            dispatch(setSettings(response));
        }
    } catch (error) {
        dispatch(setError(error.message || 'Failed to load settings.'));
    }
};
