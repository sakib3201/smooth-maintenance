/**
 * Output the clean HTML string for the frontend.
 * Pure Vanilla JS view script will attach to this.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
    const { endDate, expiredMessage } = attributes;

    const blockProps = useBlockProps.save({
        className: 'sm-countdown-wrapper',
        'data-end-date': endDate,
        'data-expired-message': expiredMessage,
    });

    if (!endDate) {
        return null;
    }

    // Output clean, bare HTML structure for view.js to manipulate
    return (
        <div {...blockProps}>
            <div className="sm-countdown-grid">
                <div className="sm-countdown-item">
                    <span className="sm-countdown-number sm-days">00</span>
                    <span className="sm-countdown-label">{__('Days', 'smooth-maintenance')}</span>
                </div>
                <div className="sm-countdown-item">
                    <span className="sm-countdown-number sm-hours">00</span>
                    <span className="sm-countdown-label">{__('Hours', 'smooth-maintenance')}</span>
                </div>
                <div className="sm-countdown-item">
                    <span className="sm-countdown-number sm-minutes">00</span>
                    <span className="sm-countdown-label">{__('Minutes', 'smooth-maintenance')}</span>
                </div>
                <div className="sm-countdown-item">
                    <span className="sm-countdown-number sm-seconds">00</span>
                    <span className="sm-countdown-label">{__('Seconds', 'smooth-maintenance')}</span>
                </div>
            </div>
            <div className="sm-countdown-expired" style={{ display: 'none' }}>
                {expiredMessage}
            </div>
        </div>
    );
}
