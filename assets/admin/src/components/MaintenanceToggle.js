import { useSelect, useDispatch } from '@wordpress/data';
import { ToggleControl, Spinner, Notice, Tooltip } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useCallback } from '@wordpress/element';
import { STORE_NAME } from '../store';

const MaintenanceToggle = () => {
    const [notice, setNotice] = useState(null);

    const { isEnabled, saving, loaded, error } = useSelect((select) => {
        const store = select(STORE_NAME);
        return {
            isEnabled: store.isMaintenanceEnabled(),
            saving: store.isSaving() || false,
            loaded: store.hasLoaded(),
            error: store.getError(),
        };
    }, []);

    const { updateSettings } = useDispatch(STORE_NAME);

    const handleToggle = useCallback(async (value) => {
        setNotice(null);
        try {
            await updateSettings({ maintenance_mode_enabled: value });
        } catch (err) {
            setNotice({
                status: 'error',
                message: err.message || __('Failed to update settings.', 'smooth-maintenance'),
            });
        }
    }, [updateSettings]);

    if (!loaded) {
        return (
            <div className="sm-toggle-loading card">
                <Spinner />
                <span>{__('Loading configuration…', 'smooth-maintenance')}</span>
            </div>
        );
    }

    return (
        <div className="sm-card">
            {notice && (
                <Notice
                    status={notice.status}
                    onDismiss={() => setNotice(null)}
                    className="sm-toggle-notice"
                >
                    {notice.message}
                </Notice>
            )}

            <div className="sm-toggle-card__content">
                <div className="sm-toggle-card__info">
                    <h3 className="sm-toggle-card__title">
                        {__('Maintenance Activation', 'smooth-maintenance')}
                    </h3>
                    <p className="sm-toggle-card__description">
                        {__('Control your site visibility. When enabled, only administrators can access the full site.', 'smooth-maintenance')}
                    </p>
                </div>
                <div className="sm-toggle-card__control">
                    <Tooltip text={isEnabled ? __('Switch to Live Mode', 'smooth-maintenance') : __('Go into Maintenance Mode', 'smooth-maintenance')}>
                        <div>
                            <ToggleControl
                                checked={isEnabled}
                                onChange={handleToggle}
                                disabled={saving}
                                __nextHasNoMarginBottom
                            />
                        </div>
                    </Tooltip>
                </div>
            </div>

            <div className={`sm-toggle-status ${isEnabled ? 'sm-toggle-status--active' : 'sm-toggle-status--inactive'}`}>
                <span className="sm-toggle-status__dot" />
                <span className="sm-toggle-status__text">
                    {isEnabled
                        ? __('Securely Locked. Maintenance page is visible.', 'smooth-maintenance')
                        : __('Publicly Accessible. Site is currently live.', 'smooth-maintenance')
                    }
                </span>
                {saving && <Spinner />}
            </div>
        </div>
    );
};

export default MaintenanceToggle;
