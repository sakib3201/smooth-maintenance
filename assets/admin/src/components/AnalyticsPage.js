import { __ } from '@wordpress/i18n';

const STAT_CARDS = [
    {
        icon: '👁️',
        label: __( 'Visitors During Maintenance', 'smooth-maintenance' ),
        value: '—',
    },
    {
        icon: '🌐',
        label: __( 'Traffic Sources', 'smooth-maintenance' ),
        value: '—',
    },
    {
        icon: '⏰',
        label: __( 'Peak Hours', 'smooth-maintenance' ),
        value: '—',
    },
];

const AnalyticsPage = () => {
    return (
        <>
            <div className="sm-card" style={ { textAlign: 'center', padding: '48px 32px' } }>
                <div style={ {
                    width: '72px',
                    height: '72px',
                    background: 'linear-gradient(135deg, #6366f1, #8b5cf6)',
                    borderRadius: '20px',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    fontSize: '2rem',
                    margin: '0 auto 24px',
                    boxShadow: '0 8px 24px rgba(99, 102, 241, 0.35)',
                } }>
                    📊
                </div>
                <h3 style={ { fontSize: '1.5rem', fontWeight: 800, color: 'var(--sm-text)', marginBottom: '12px', letterSpacing: '-0.025em' } }>
                    { __( 'Analytics — Coming in v1.3', 'smooth-maintenance' ) }
                </h3>
                <p style={ { color: 'var(--sm-text-muted)', maxWidth: '480px', margin: '0 auto', lineHeight: 1.6 } }>
                    { __( 'Track visitor traffic during maintenance windows, understand peak hours, and analyse referral sources — all without leaving your dashboard.', 'smooth-maintenance' ) }
                </p>
            </div>

            <div style={ { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '16px' } }>
                { STAT_CARDS.map( ( card ) => (
                    <div key={ card.label } className="sm-card" style={ { marginBottom: 0, textAlign: 'center', padding: '28px 20px' } }>
                        <div style={ { fontSize: '1.75rem', marginBottom: '12px' } }>{ card.icon }</div>
                        <div style={ { fontSize: '1.75rem', fontWeight: 800, color: 'var(--sm-text)', marginBottom: '6px' } }>
                            { card.value }
                        </div>
                        <div style={ { fontSize: '0.8rem', color: 'var(--sm-text-muted)', marginBottom: '10px' } }>
                            { card.label }
                        </div>
                        <span className="sm-badge">v1.3</span>
                    </div>
                ) ) }
            </div>
        </>
    );
};

export default AnalyticsPage;
