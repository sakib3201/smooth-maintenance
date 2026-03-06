/**
 * Main App component.
 *
 * @package SmoothMaintenance
 */

import Header from './components/Header';
import MaintenanceToggle from './components/MaintenanceToggle';
import TemplateSelector from './components/TemplateSelector';

const App = () => {
    return (
        <div className="sm-admin-app">
            <Header />
            <div className="sm-admin-content">
                <MaintenanceToggle />
                <TemplateSelector />
            </div>
        </div>
    );
};

export default App;
