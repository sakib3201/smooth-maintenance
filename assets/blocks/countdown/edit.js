/**
 * React editor component for the Countdown block.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, ColorPalette } from '@wordpress/block-editor';
import { PanelBody, DateTimePicker, TextControl, BaseControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const { endDate, expiredMessage, numberColor, labelColor } = attributes;

    // Local state for live editor preview of the countdown
    const [timeLeft, setTimeLeft] = useState({
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
    });

    useEffect(() => {
        if (!endDate) return;

        const targetDate = new Date(endDate).getTime();

        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(interval);
                setTimeLeft({ days: '00', hours: '00', minutes: '00', seconds: '00' });
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            setTimeLeft({
                days: String(days).padStart(2, '0'),
                hours: String(hours).padStart(2, '0'),
                minutes: String(minutes).padStart(2, '0'),
                seconds: String(seconds).padStart(2, '0'),
            });
        }, 1000);

        return () => clearInterval(interval);
    }, [endDate]);

    const wrapperStyle = {
        ...(numberColor && { '--sm-number-color': numberColor }),
        ...(labelColor && { '--sm-label-color': labelColor }),
    };

    const blockProps = useBlockProps({
        className: 'sm-countdown-wrapper',
        style: wrapperStyle,
    });

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Countdown Settings', 'smooth-maintenance')}>
                    <DateTimePicker
                        currentDate={endDate}
                        onChange={(newDate) => setAttributes({ endDate: newDate })}
                        is12Hour={false}
                    />
                    <TextControl
                        label={__('Expired Message', 'smooth-maintenance')}
                        value={expiredMessage}
                        onChange={(val) => setAttributes({ expiredMessage: val })}
                        help={__('Message to display when the countdown reaches zero.', 'smooth-maintenance')}
                    />
                </PanelBody>
                <PanelBody title={__('Colors', 'smooth-maintenance')} initialOpen={false}>
                    <BaseControl label={__('Number color', 'smooth-maintenance')} id="sm-number-color">
                        <ColorPalette
                            value={numberColor}
                            onChange={(color) => setAttributes({ numberColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                    <BaseControl label={__('Label color', 'smooth-maintenance')} id="sm-label-color">
                        <ColorPalette
                            value={labelColor}
                            onChange={(color) => setAttributes({ labelColor: color || '' })}
                            enableAlpha
                        />
                    </BaseControl>
                </PanelBody>
            </InspectorControls>

            {endDate ? (
                <div className="sm-countdown-grid">
                    <div className="sm-countdown-item">
                        <span className="sm-countdown-number">{timeLeft.days}</span>
                        <span className="sm-countdown-label">{__('Days', 'smooth-maintenance')}</span>
                    </div>
                    <div className="sm-countdown-item">
                        <span className="sm-countdown-number">{timeLeft.hours}</span>
                        <span className="sm-countdown-label">{__('Hours', 'smooth-maintenance')}</span>
                    </div>
                    <div className="sm-countdown-item">
                        <span className="sm-countdown-number">{timeLeft.minutes}</span>
                        <span className="sm-countdown-label">{__('Minutes', 'smooth-maintenance')}</span>
                    </div>
                    <div className="sm-countdown-item">
                        <span className="sm-countdown-number">{timeLeft.seconds}</span>
                        <span className="sm-countdown-label">{__('Seconds', 'smooth-maintenance')}</span>
                    </div>
                </div>
            ) : (
                <div className="sm-countdown-placeholder">
                    <p>{__('Please select an end date in the block settings.', 'smooth-maintenance')}</p>
                </div>
            )}
        </div>
    );
}
