/**
 * Store registration with @wordpress/data.
 *
 * @package SmoothMaintenance
 */

import { createReduxStore, register } from '@wordpress/data';
import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';
import * as resolvers from './resolvers';

const STORE_NAME = 'smooth-maintenance/settings';

const store = createReduxStore(STORE_NAME, {
    reducer,
    actions,
    selectors,
    resolvers,
});

register(store);

export { STORE_NAME };
export default store;
