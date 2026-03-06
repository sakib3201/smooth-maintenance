import { __ } from '@wordpress/i18n';
import ThemeToggle from './ThemeToggle';

const Header = () => {
    return (
        <div className="sm-admin-header">
            <div className="sm-admin-header__text">
                <h2>{__('Dashboard', 'smooth-maintenance')}</h2>
                <p className="sm-admin-header__subtitle">
                    {__('Manage your site maintenance mode and templates', 'smooth-maintenance')}
                </p>
            </div>
            <div className="sm-admin-header__actions">
                <ThemeToggle />
            </div>
        </div>
    );
};

export default Header;
