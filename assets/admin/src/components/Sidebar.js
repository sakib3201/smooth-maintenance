import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import SearchBar from './SearchBar';

const Sidebar = () => {
    const [activeTab, setActiveTab] = useState('settings');
    const adminData = window.smoothMaintenanceAdmin || {};

    const navItems = [
        { id: 'settings', label: __('Settings', 'smooth-maintenance'), icon: '⚙️' },
        { id: 'subscribers', label: __('Subscribers', 'smooth-maintenance'), icon: '👥' },
        { id: 'analytics', label: __('Analytics', 'smooth-maintenance'), icon: '📊' },
    ];

    return (
        <aside className="sm-sidebar">
            <div className="sm-sidebar__logo">
                <div className="sm-sidebar__logo-icon">SM</div>
                <div className="sm-sidebar__logo-text">Smooth Maintenance</div>
            </div>

            <SearchBar
                items={navItems}
                onSelect={(id) => setActiveTab(id)}
            />

            <nav className="sm-sidebar__nav">
                {navItems.map((item) => (
                    <div
                        key={item.id}
                        className={`sm-sidebar__nav-item ${activeTab === item.id ? 'is-active' : ''}`}
                        onClick={() => setActiveTab(item.id)}
                    >
                        <span className="sm-sidebar__nav-icon">{item.icon}</span>
                        <span className="sm-sidebar__nav-label">{item.label}</span>
                    </div>
                ))}
            </nav>

            <div className="sm-sidebar__footer">
                <a href={adminData.wpAdminUrl} className="sm-sidebar__wp-exit">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9L6 12l5 4.5zm6-4.5h-7v-1h7v1z" />
                    </svg>
                    <span>{__('Back to WordPress', 'smooth-maintenance')}</span>
                </a>
            </div>
        </aside>
    );
};

export default Sidebar;
