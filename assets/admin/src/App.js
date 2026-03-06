import Sidebar from './components/Sidebar';
import Header from './components/Header';
import MaintenanceToggle from './components/MaintenanceToggle';
import TemplateSelector from './components/TemplateSelector';

const App = () => {
    return (
        <div className="sm-admin-app">
            <Sidebar />
            <main className="sm-main-content">
                <div className="sm-content-container">
                    <Header />
                    <div className="sm-content-body">
                        <MaintenanceToggle />
                        <TemplateSelector />
                    </div>
                </div>
            </main>
        </div>
    );
};

export default App;
