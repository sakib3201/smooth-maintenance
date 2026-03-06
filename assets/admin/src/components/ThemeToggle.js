/**
 * Theme Toggle component.
 */

import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';

const ThemeToggle = () => {
    const [theme, setTheme] = useState(localStorage.getItem('sm_admin_theme') || 'light');

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('sm_admin_theme', theme);
    }, [theme]);

    const toggleTheme = () => {
        setTheme(theme === 'light' ? 'dark' : 'light');
    };

    return (
        <button
            className="sm-theme-toggle"
            onClick={toggleTheme}
            aria-label={__('Toggle Dark Mode', 'smooth-maintenance')}
        >
            {theme === 'light' ? '🌙' : '☀️'}
        </button>
    );
};

export default ThemeToggle;
