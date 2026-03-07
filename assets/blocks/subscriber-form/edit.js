import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, ColorPalette } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl, BaseControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const {
        placeholder,
        buttonText,
        successMessage,
        containerBackgroundColor,
        containerBorderColor,
        containerBorderRadius,
        containerPadding,
        containerBorderWidth,
        inputTextColor,
        inputPlaceholderColor,
        inputBackgroundColor,
        inputBorderColor,
        inputBorderRadius,
        inputFontSize,
        buttonTextColor,
        buttonBackgroundColor,
        buttonHoverBackgroundColor,
        buttonBorderColor,
        buttonBorderRadius,
        buttonFontSize,
        buttonFontWeight,
        successTextColor,
        successBackgroundColor,
        successBorderColor,
        successBorderRadius,
    } = attributes;

    const wrapperStyle = {
        ...(containerBackgroundColor && { '--sm-container-bg': containerBackgroundColor }),
        ...(containerBorderColor && { '--sm-container-border': containerBorderColor }),
        ...(containerBorderRadius && { '--sm-container-radius': containerBorderRadius + 'px' }),
        ...(containerPadding && { '--sm-container-padding': containerPadding + 'px' }),
        ...(containerBorderWidth && { '--sm-container-border-width': containerBorderWidth + 'px' }),
        ...(inputTextColor && { '--sm-input-text-color': inputTextColor }),
        ...(inputPlaceholderColor && { '--sm-input-placeholder-color': inputPlaceholderColor }),
        ...(inputBackgroundColor && { '--sm-input-bg': inputBackgroundColor }),
        ...(inputBorderColor && { '--sm-input-border-color': inputBorderColor }),
        ...(inputBorderRadius && { '--sm-input-radius': inputBorderRadius + 'px' }),
        ...(inputFontSize && { '--sm-input-font-size': inputFontSize + 'px' }),
        ...(buttonTextColor && { '--sm-btn-text-color': buttonTextColor }),
        ...(buttonBackgroundColor && { '--sm-btn-bg': buttonBackgroundColor }),
        ...(buttonHoverBackgroundColor && { '--sm-btn-hover-bg': buttonHoverBackgroundColor }),
        ...(buttonBorderColor && { '--sm-btn-border-color': buttonBorderColor }),
        ...(buttonBorderRadius && { '--sm-btn-radius': buttonBorderRadius + 'px' }),
        ...(buttonFontSize && { '--sm-btn-font-size': buttonFontSize + 'px' }),
        ...(buttonFontWeight && { '--sm-btn-font-weight': buttonFontWeight }),
        ...(successTextColor && { '--sm-success-text-color': successTextColor }),
        ...(successBackgroundColor && { '--sm-success-bg': successBackgroundColor }),
        ...(successBorderColor && { '--sm-success-border-color': successBorderColor }),
        ...(successBorderRadius && { '--sm-success-radius': successBorderRadius + 'px' }),
    };

    return (
        <div {...useBlockProps({ style: wrapperStyle, className: 'sm-subscriber-form-wrapper' })}>
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

                <PanelBody title={__('Container Styles', 'smooth-maintenance')} initialOpen={false}>
                    <BaseControl label={__('Background Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={containerBackgroundColor}
                            onChange={(color) => setAttributes({ containerBackgroundColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Border Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={containerBorderColor}
                            onChange={(color) => setAttributes({ containerBorderColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <RangeControl
                        label={__('Border Radius', 'smooth-maintenance')}
                        value={containerBorderRadius}
                        onChange={(val) => setAttributes({ containerBorderRadius: val })}
                        min={0}
                        max={100}
                    />
                    <RangeControl
                        label={__('Padding', 'smooth-maintenance')}
                        value={containerPadding}
                        onChange={(val) => setAttributes({ containerPadding: val })}
                        min={0}
                        max={50}
                    />
                    <RangeControl
                        label={__('Border Width', 'smooth-maintenance')}
                        value={containerBorderWidth}
                        onChange={(val) => setAttributes({ containerBorderWidth: val })}
                        min={0}
                        max={20}
                    />
                </PanelBody>

                <PanelBody title={__('Input Styles', 'smooth-maintenance')} initialOpen={false}>
                    <BaseControl label={__('Text Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={inputTextColor}
                            onChange={(color) => setAttributes({ inputTextColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Placeholder Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={inputPlaceholderColor}
                            onChange={(color) => setAttributes({ inputPlaceholderColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Background Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={inputBackgroundColor}
                            onChange={(color) => setAttributes({ inputBackgroundColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Border Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={inputBorderColor}
                            onChange={(color) => setAttributes({ inputBorderColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <RangeControl
                        label={__('Border Radius', 'smooth-maintenance')}
                        value={inputBorderRadius}
                        onChange={(val) => setAttributes({ inputBorderRadius: val })}
                        min={0}
                        max={100}
                    />
                    <RangeControl
                        label={__('Font Size', 'smooth-maintenance')}
                        value={inputFontSize}
                        onChange={(val) => setAttributes({ inputFontSize: val })}
                        min={12}
                        max={32}
                    />
                </PanelBody>

                <PanelBody title={__('Button Styles', 'smooth-maintenance')} initialOpen={false}>
                    <BaseControl label={__('Text Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={buttonTextColor}
                            onChange={(color) => setAttributes({ buttonTextColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Background Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={buttonBackgroundColor}
                            onChange={(color) => setAttributes({ buttonBackgroundColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Hover Background Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={buttonHoverBackgroundColor}
                            onChange={(color) => setAttributes({ buttonHoverBackgroundColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Border Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={buttonBorderColor}
                            onChange={(color) => setAttributes({ buttonBorderColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <RangeControl
                        label={__('Border Radius', 'smooth-maintenance')}
                        value={buttonBorderRadius}
                        onChange={(val) => setAttributes({ buttonBorderRadius: val })}
                        min={0}
                        max={100}
                    />
                    <RangeControl
                        label={__('Font Size', 'smooth-maintenance')}
                        value={buttonFontSize}
                        onChange={(val) => setAttributes({ buttonFontSize: val })}
                        min={12}
                        max={32}
                    />
                    <RangeControl
                        label={__('Font Weight', 'smooth-maintenance')}
                        value={buttonFontWeight}
                        onChange={(val) => setAttributes({ buttonFontWeight: val })}
                        min={100}
                        max={900}
                        step={100}
                    />
                </PanelBody>

                <PanelBody title={__('Success Message Styles', 'smooth-maintenance')} initialOpen={false}>
                    <BaseControl label={__('Text Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={successTextColor}
                            onChange={(color) => setAttributes({ successTextColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Background Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={successBackgroundColor}
                            onChange={(color) => setAttributes({ successBackgroundColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Border Color', 'smooth-maintenance')}>
                        <ColorPalette
                            value={successBorderColor}
                            onChange={(color) => setAttributes({ successBorderColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <RangeControl
                        label={__('Border Radius', 'smooth-maintenance')}
                        value={successBorderRadius}
                        onChange={(val) => setAttributes({ successBorderRadius: val })}
                        min={0}
                        max={100}
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
