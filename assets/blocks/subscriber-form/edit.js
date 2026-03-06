import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { placeholder, buttonText, successMessage } = attributes;

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <PanelBody title={__('Form Settings', 'smooth-maintenance')}>
                    <TextControl
                        label={__('Placeholder Text', 'smooth-maintenance')}
                        value={placeholder}
                        onChange={(val) => setAttributes({ placeholder: val })}
                    />
                    <TextControl
                        label={__('Button Text', 'smooth-maintenance')}
                        value={buttonText}
                        onChange={(val) => setAttributes({ buttonText: val })}
                    />
                    <TextControl
                        label={__('Success Message', 'smooth-maintenance')}
                        value={successMessage}
                        onChange={(val) => setAttributes({ successMessage: val })}
                    />
                </PanelBody>
            </InspectorControls>

            <div className="sm-subscriber-form-preview">
                <div className="sm-form-group">
                    <input
                        type="email"
                        placeholder={placeholder}
                        disabled
                        style={{ opacity: 0.6 }}
                    />
                    <button disabled>{buttonText}</button>
                </div>
                <p className="sm-hint">{__('Note: Subscription works on the frontend.', 'smooth-maintenance')}</p>
            </div>
        </div>
    );
}
