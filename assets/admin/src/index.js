/**
 * Entry point for the admin React app.
 *
 * @package SmoothMaintenance
 */

import { createRoot, render } from '@wordpress/element';
import App from './App';
import './store';
import './styles/admin.scss';

const rootElement = document.getElementById( 'smooth-maintenance-admin' );

if ( rootElement ) {
    if ( typeof createRoot === 'function' ) {
        createRoot( rootElement ).render( <App /> );
    } else {
        render( <App />, rootElement );
    }
}
