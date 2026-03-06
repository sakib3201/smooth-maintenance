/**
 * Main App component.
 *
 * @package SmoothMaintenance
 */

import Header from './components/Header';
import MaintenanceToggle from './components/MaintenanceToggle';

const App = () => {
    return (
        <div className="sm-admin-app">
            <Header />
            <div className="sm-admin-content">
                <MaintenanceToggle />
            </div>
        </div>
    );
};

export default App;
