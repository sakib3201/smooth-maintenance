/**
 * Selectors unit tests.
 *
 * @package SmoothMaintenance
 */

import { describe, it, expect } from 'vitest';
import {
    getSettings,
    isMaintenanceEnabled,
    isSaving,
    hasLoaded,
    getError,
} from '../../assets/admin/src/store/selectors';

describe('Selectors', () => {
    const mockState = {
        settings: {
            maintenance_mode_enabled: true,
            version: '1.0.0',
        },
        isSaving: false,
        hasLoaded: true,
        error: null,
    };

    it('getSettings returns settings object', () => {
        const result = getSettings(mockState);
        expect(result).toEqual(mockState.settings);
    });

    it('isMaintenanceEnabled returns boolean', () => {
        expect(isMaintenanceEnabled(mockState)).toBe(true);

        const disabledState = {
            ...mockState,
            settings: { ...mockState.settings, maintenance_mode_enabled: false },
        };
        expect(isMaintenanceEnabled(disabledState)).toBe(false);
    });

    it('isSaving returns saving state', () => {
        expect(isSaving(mockState)).toBe(false);
        expect(isSaving({ ...mockState, isSaving: true })).toBe(true);
    });

    it('hasLoaded returns loaded state', () => {
        expect(hasLoaded(mockState)).toBe(true);
        expect(hasLoaded({ ...mockState, hasLoaded: false })).toBe(false);
    });

    it('getError returns null when no error', () => {
        expect(getError(mockState)).toBeNull();
    });

    it('getError returns error message', () => {
        const errorState = { ...mockState, error: 'Something went wrong' };
        expect(getError(errorState)).toBe('Something went wrong');
    });
});
