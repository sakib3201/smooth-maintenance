/**
 * Template Selector Component.
 */

import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { SelectControl, Spinner, Button } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

const TemplateSelector = () => {
    const { settings, isSaving, hasLoaded } = useSelect((select) => ({
        settings: select('smooth-maintenance').getSettings(),
        isSaving: select('smooth-maintenance').isSaving(),
        hasLoaded: select('smooth-maintenance').hasLoaded(),
    }), []);

    const { updateSettings } = useDispatch('smooth-maintenance');

    const [templates, setTemplates] = useState([]);
    const [isFetching, setIsFetching] = useState(true);

    useEffect(() => {
        // Fetch published templates.
        apiFetch({ path: '/wp/v2/sm_template?status=publish&per_page=100' })
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
            <div className="sm-card sm-template-selector sm-loading">
                <Spinner />
                <p>{__('Loading templates...', 'smooth-maintenance')}</p>
            </div>
        );
    }

    const activeTemplate = settings.active_template ? parseInt(settings.active_template, 10) : 0;

    const handleChange = (newTemplateId) => {
        updateSettings({ active_template: parseInt(newTemplateId, 10) });
    };

    const hasTemplates = templates.length > 0;

    return (
        <div className="sm-card sm-template-selector">
            <h3>{__('Maintenance Template', 'smooth-maintenance')}</h3>
            <p className="sm-description">
                {__('Select which Gutenberg-designed template to display when maintenance mode is active.', 'smooth-maintenance')}
            </p>

            <div className="sm-template-controls">
                {hasTemplates ? (
                    <SelectControl
                        value={activeTemplate}
                        options={[
                            { label: __('— Select a Template —', 'smooth-maintenance'), value: 0 },
                            ...templates,
                        ]}
                        onChange={handleChange}
                        disabled={isSaving}
                    />
                ) : (
                    <p className="sm-error-text">
                        {__('No templates found. Please create one under Maintenance > Templates.', 'smooth-maintenance')}
                    </p>
                )}

                {activeTemplate > 0 && (
                    <Button
                        isSecondary
                        href={`post.php?post=${activeTemplate}&action=edit`}
                        target="_blank"
                    >
                        {__('Edit in Gutenberg', 'smooth-maintenance')}
                    </Button>
                )}
            </div>

            <div className="sm-template-footer">
                <Button
                    isLink
                    href="edit.php?post_type=sm_template"
                >
                    {__('Manage All Templates', 'smooth-maintenance')}
                </Button>
            </div>
        </div>
    );
};

export default TemplateSelector;
