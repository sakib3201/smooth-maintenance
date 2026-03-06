import { __ } from '@wordpress/i18n';
import ThemeToggle from './ThemeToggle';

const TAB_META = {
    settings: {
        title: __( 'Dashboard', 'smooth-maintenance' ),
        subtitle: __( 'Manage your maintenance mode and templates', 'smooth-maintenance' ),
    },
    subscribers: {
        title: __( 'Subscribers', 'smooth-maintenance' ),
        subtitle: __( 'View and export email subscribers', 'smooth-maintenance' ),
    },
    analytics: {
        title: __( 'Analytics', 'smooth-maintenance' ),
        subtitle: __( 'Monitor traffic during maintenance', 'smooth-maintenance' ),
    },
    templates: {
        title: __( 'Templates', 'smooth-maintenance' ),
        subtitle: __( 'Manage your maintenance page designs', 'smooth-maintenance' ),
    },
};

const Header = ( { activeTab } ) => {
    const meta = TAB_META[ activeTab ] || TAB_META.settings;

    return (
        <div className="sm-admin-header">
            <div className="sm-admin-header__text">
                <h2>{ meta.title }</h2>
                <p className="sm-admin-header__subtitle">{ meta.subtitle }</p>
            </div>
            <div className="sm-admin-header__actions">
                <ThemeToggle />
            </div>
        </div>
    );
};

export default Header;
