import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { SelectControl, Spinner, Button, Tooltip } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { STORE_NAME } from '../store';

const TemplateSelector = ( { onManageTemplates } ) => {
    const { settings, isSaving, hasLoaded } = useSelect((select) => ({
        settings: select(STORE_NAME).getSettings(),
        isSaving: select(STORE_NAME).isSaving(),
        hasLoaded: select(STORE_NAME).hasLoaded(),
    }), []);

    const { updateSettings } = useDispatch(STORE_NAME);

    const [templates, setTemplates] = useState([]);
    const [isFetching, setIsFetching] = useState(true);

    useEffect(() => {
        // Fetch published templates with optimized field selection.
        apiFetch({ path: '/wp/v2/sm_template?status=publish&per_page=100&_fields=id,title' })
            .then((posts) => {
                const options = posts.map((post) => ({
                    label: post.title.rendered || __('(Untitled)', 'smooth-maintenance'),
                    value: post.id,
                }));
                setTemplates(options);
                setIsFetching(false);
            })
            .catch(() => {
                setTemplates([]);
                setIsFetching(false);
            });
    }, []);

    if (!hasLoaded || isFetching) {
        return (
            <div className="sm-card sm-loading">
                <Spinner />
                <p>{__('Fetching premium templates...', 'smooth-maintenance')}</p>
            </div>
        );
    }

    const activeTemplateId = settings.active_template ? parseInt(settings.active_template, 10) : 0;

    const handleChange = (newId) => {
        updateSettings({ active_template: parseInt(newId, 10) });
    };

    return (
        <div className="sm-card">
            <h3>{__('Visual Identity', 'smooth-maintenance')}</h3>
            <p className="sm-text-muted">
                {__('Choose the visual template that your visitors will encounter. All templates are fully customizable via the Gutenberg editor.', 'smooth-maintenance')}
            </p>

            <div className="sm-template-controls" style={{ display: 'flex', gap: '12px', alignItems: 'flex-end', marginTop: '24px' }}>
                <div style={{ flex: 1 }}>
                    <SelectControl
                        label={__('Active Design Template', 'smooth-maintenance')}
                        value={activeTemplateId}
                        options={[
                            { label: __('— Choose your design —', 'smooth-maintenance'), value: 0 },
                            ...templates,
                        ]}
                        onChange={handleChange}
                        disabled={isSaving}
                        __nextHasNoMarginBottom
                    />
                </div>

                {activeTemplateId > 0 && (
                    <Tooltip text={__('Open the block editor for this design', 'smooth-maintenance')}>
                        <Button
                            variant="secondary"
                            href={`post.php?post=${activeTemplateId}&action=edit`}
                            target="_blank"
                            style={{ height: '40px' }}
                        >
                            {__('Edit Template', 'smooth-maintenance')}
                        </Button>
                    </Tooltip>
                )}
            </div>

            <div className="sm-template-footer" style={{ marginTop: '24px', paddingTop: '16px', borderTop: '1px solid var(--sm-border)' }}>
                <Tooltip text={__('View and manage all your saved maintenance designs', 'smooth-maintenance')}>
                    <Button
                        variant="link"
                        onClick={ onManageTemplates }
                        style={{ padding: 0 }}
                    >
                        {__('Manage all templates →', 'smooth-maintenance')}
                    </Button>
                </Tooltip>
            </div>
        </div>
    );
};

export default TemplateSelector;
