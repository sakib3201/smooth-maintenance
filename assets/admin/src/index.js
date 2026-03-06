/**
 * Entry point for the admin React app.
 *
 * @package SmoothMaintenance
 */

import { createRoot, render } from '@wordpress/element';
import App from './App';
import './store';
import './styles/admin.scss';

// Apply saved theme before React mounts to avoid light-mode flash.
const savedTheme = localStorage.getItem( 'sm_admin_theme' ) || 'light';
document.documentElement.setAttribute( 'data-theme', savedTheme );

const rootElement = document.getElementById( 'smooth-maintenance-admin' );

if ( rootElement ) {
    if ( typeof createRoot === 'function' ) {
        createRoot( rootElement ).render( <App /> );
    } else {
        render( <App />, rootElement );
    }
}
