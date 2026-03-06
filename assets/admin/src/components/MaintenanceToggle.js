/**
 * MaintenanceToggle component.
 *
 * Connected component for toggling maintenance mode.
 *
 * @package SmoothMaintenance
 */

import { useSelect, useDispatch } from '@wordpress/data';
import { ToggleControl, Spinner, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useCallback } from '@wordpress/element';
import { STORE_NAME } from '../store';

const MaintenanceToggle = () => {
    const [notice, setNotice] = useState(null);

    const { isEnabled, saving, loaded, error } = useSelect((select) => {
        const store = select(STORE_NAME);
        return {
            isEnabled: store.isMaintenanceEnabled(),
            saving: store.isSaving(),
            loaded: store.hasLoaded(),
            error: store.getError(),
        };
    }, []);

    const { updateSettings } = useDispatch(STORE_NAME);

    const handleToggle = useCallback(async (value) => {
        setNotice(null);

        try {
            await updateSettings({ maintenance_mode_enabled: value });
            setNotice({
                status: 'success',
                message: value
                    ? __('Maintenance mode enabled. Non-admin visitors will see the maintenance page.', 'smooth-maintenance')
                    : __('Maintenance mode disabled. Your site is now live.', 'smooth-maintenance'),
            });
        } catch (err) {
            setNotice({
                status: 'error',
                message: err.message || __('Failed to update settings.', 'smooth-maintenance'),
            });
        }
    }, [updateSettings]);

    if (!loaded) {
        return (
            <div className="sm-toggle-loading">
                <Spinner />
                <span>{__('Loading settings…', 'smooth-maintenance')}</span>
            </div>
        );
    }

    return (
        <div className="sm-toggle-wrapper">
            {notice && (
                <Notice
                    status={notice.status}
                    isDismissible
                    onDismiss={() => setNotice(null)}
                    className="sm-toggle-notice"
                >
                    {notice.message}
                </Notice>
            )}

            {error && !notice && (
                <Notice
                    status="error"
                    isDismissible={false}
                    className="sm-toggle-notice"
                >
                    {error}
                </Notice>
            )}

            <div className="sm-toggle-card">
                <div className="sm-toggle-card__content">
                    <div className="sm-toggle-card__info">
                        <h3 className="sm-toggle-card__title">
                            {__('Maintenance Mode', 'smooth-maintenance')}
                        </h3>
                        <p className="sm-toggle-card__description">
                            {__('When enabled, non-admin visitors will see a maintenance page instead of your site content.', 'smooth-maintenance')}
                        </p>
                    </div>
                    <div className="sm-toggle-card__control">
                        <ToggleControl
                            checked={isEnabled}
                            onChange={handleToggle}
                            disabled={saving}
                            __nextHasNoMarginBottom
                        />
                    </div>
                </div>

                <div className={`sm-toggle-status ${isEnabled ? 'sm-toggle-status--active' : 'sm-toggle-status--inactive'}`}>
                    <span className="sm-toggle-status__dot" />
                    <span className="sm-toggle-status__text">
                        {isEnabled
                            ? __('Maintenance mode is active', 'smooth-maintenance')
                            : __('Site is live', 'smooth-maintenance')
                        }
                    </span>
                    {saving && <Spinner />}
                </div>
            </div>
        </div>
    );
};

export default MaintenanceToggle;
