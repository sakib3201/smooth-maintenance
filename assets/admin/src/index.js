/**
 * Entry point for the admin React app.
 *
 * @package SmoothMaintenance
 */

import { createRoot } from '@wordpress/element';
import App from './App';
import './store';
import './styles/admin.scss';

const rootElement = document.getElementById('smooth-maintenance-admin');

if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<App />);
}
