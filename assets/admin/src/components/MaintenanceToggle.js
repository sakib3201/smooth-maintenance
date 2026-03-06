import { useSelect, useDispatch } from '@wordpress/data';
import { Spinner, Notice } from '@wordpress/components';
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
                <Spinner aria-label={__('Loading configuration…', 'smooth-maintenance')} />
                <span>{__('Loading configuration…', 'smooth-maintenance')}</span>
            </div>
        );
    }

    return (
        <div className="sm-card sm-card--has-badge" role="region" aria-label={__('Maintenance Mode Control', 'smooth-maintenance')}>
            <span
                className={`sm-status-badge sm-status-badge--${isEnabled ? 'maintenance' : 'live'}`}
                aria-live="polite"
                aria-atomic="true"
            >
                <span className="sm-status-badge__dot" aria-hidden="true" />
                { isEnabled ? __('Maintenance', 'smooth-maintenance') : __('Live', 'smooth-maintenance') }
            </span>

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
                    <button
                        className={`sm-big-toggle${isEnabled ? ' is-on' : ''}`}
                        role="switch"
                        aria-checked={isEnabled}
                        aria-label={isEnabled
                            ? __('Maintenance mode on — click to go live', 'smooth-maintenance')
                            : __('Site is live — click to enable maintenance mode', 'smooth-maintenance')
                        }
                        onClick={() => handleToggle(!isEnabled)}
                        disabled={saving}
                    >
                        <span className="sm-big-toggle__track" aria-hidden="true" />
                        <span className="sm-big-toggle__thumb" aria-hidden="true" />
                    </button>
                </div>
            </div>

            <div
                className={`sm-toggle-status ${isEnabled ? 'sm-toggle-status--active' : 'sm-toggle-status--inactive'}`}
                aria-label={isEnabled
                    ? __('Status: Maintenance mode active', 'smooth-maintenance')
                    : __('Status: Site is live', 'smooth-maintenance')
                }
            >
                <span className="sm-toggle-status__dot" />
                <span className="sm-toggle-status__text">
                    {isEnabled
                        ? __('Securely Locked. Maintenance page is visible.', 'smooth-maintenance')
                        : __('Publicly Accessible. Site is currently live.', 'smooth-maintenance')
                    }
                </span>
                {saving && <Spinner aria-label={__('Saving…', 'smooth-maintenance')} />}
            </div>
        </div>
    );
};

export default MaintenanceToggle;
