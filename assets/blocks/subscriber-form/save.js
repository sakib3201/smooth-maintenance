import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
    const { placeholder, buttonText, successMessage } = attributes;

    const blockProps = useBlockProps.save({
        className: 'sm-subscriber-form-wrapper',
        'data-success-message': successMessage,
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
