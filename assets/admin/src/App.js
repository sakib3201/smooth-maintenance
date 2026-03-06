import { useState } from '@wordpress/element';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import MaintenanceToggle from './components/MaintenanceToggle';
import TemplateSelector from './components/TemplateSelector';
import SubscribersPage from './components/SubscribersPage';
import AnalyticsPage from './components/AnalyticsPage';
import TemplatesPage from './components/TemplatesPage';

const App = () => {
    const [ activeTab, setActiveTab ] = useState( 'settings' );

    const renderContent = () => {
        switch ( activeTab ) {
            case 'subscribers':
                return <SubscribersPage />;
            case 'analytics':
                return <AnalyticsPage />;
            case 'templates':
                return <TemplatesPage />;
            default:
                return (
                    <>
                        <MaintenanceToggle />
                        <TemplateSelector onManageTemplates={ () => setActiveTab( 'templates' ) } />
                    </>
                );
        }
    };

    return (
        <div className="sm-admin-app">
            <Sidebar activeTab={ activeTab } setActiveTab={ setActiveTab } />
            <main className="sm-main-content">
                <div className="sm-content-container">
                    <Header activeTab={ activeTab } />
                    <div className="sm-content-body">
                        { renderContent() }
                    </div>
                </div>
            </main>
        </div>
    );
};

export default App;
