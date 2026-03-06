/**
 * Reducer unit tests.
 *
 * @package SmoothMaintenance
 */

import { describe, it, expect } from 'vitest';
import reducer from '../../assets/admin/src/store/reducer';

describe('Reducer', () => {
    const initialState = {
        settings: {
            maintenance_mode_enabled: false,
            version: '1.0.0',
        },
        isSaving: false,
        hasLoaded: false,
        error: null,
    };

    it('returns default state', () => {
        const state = reducer(undefined, { type: 'UNKNOWN' });
        expect(state).toEqual(initialState);
    });

    it('handles SET_SETTINGS', () => {
        const action = {
            type: 'SET_SETTINGS',
            settings: { maintenance_mode_enabled: true },
        };

        const state = reducer(initialState, action);
        expect(state.settings.maintenance_mode_enabled).toBe(true);
        expect(state.hasLoaded).toBe(true);
        expect(state.error).toBeNull();
    });

    it('handles SET_SAVING', () => {
        const action = { type: 'SET_SAVING', isSaving: true };
        const state = reducer(initialState, action);
        expect(state.isSaving).toBe(true);
    });

    it('handles SET_ERROR', () => {
        const action = { type: 'SET_ERROR', error: 'Something went wrong' };
        const state = reducer(initialState, action);
        expect(state.error).toBe('Something went wrong');
        expect(state.isSaving).toBe(false);
    });

    it('merges settings with existing state', () => {
        const existingState = {
            ...initialState,
            settings: { maintenance_mode_enabled: false, version: '1.0.0' },
        };

        const action = {
            type: 'SET_SETTINGS',
            settings: { maintenance_mode_enabled: true },
        };

        const state = reducer(existingState, action);
        expect(state.settings.maintenance_mode_enabled).toBe(true);
        expect(state.settings.version).toBe('1.0.0'); // Preserved.
    });
});
