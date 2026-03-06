/**
 * Redux-style reducer for maintenance settings state.
 *
 * @package SmoothMaintenance
 */

const DEFAULT_STATE = {
	settings: {
		maintenance_mode_enabled: false,
		version: '1.0.0',
	},
	isSaving: false,
	hasLoaded: false,
	error: null,
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'SET_SETTINGS':
			return {
				...state,
				settings: {
					...state.settings,
					...action.settings,
				},
				hasLoaded: true,
				error: null,
			};

		case 'SET_SAVING':
			return {
				...state,
				isSaving: action.isSaving,
			};

		case 'SET_ERROR':
			return {
				...state,
				error: action.error,
				isSaving: false,
			};

		default:
			return state;
	}
};

export default reducer;
