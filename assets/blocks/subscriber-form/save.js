import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
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

    const blockProps = useBlockProps.save({
        className: 'sm-subscriber-form-wrapper',
        'data-success-message': successMessage,
        style: wrapperStyle,
    });

    return (
        <div {...blockProps}>
            <form className="sm-subscriber-form">
                <div className="sm-form-group">
                    <input
                        type="email"
                        className="sm-subscriber-email"
                        placeholder={placeholder}
                        required
                    />
                    <button type="submit" className="sm-subscriber-submit">
                        {buttonText}
                    </button>
                </div>
                <div className="sm-form-status" style={{ display: 'none' }}></div>
            </form>
        </div>
    );
}
