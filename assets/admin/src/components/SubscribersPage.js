import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

const SubscribersPage = () => {
    const [ subscribers, setSubscribers ] = useState( [] );
    const [ total, setTotal ] = useState( 0 );
    const [ page, setPage ] = useState( 1 );
    const [ pages, setPages ] = useState( 1 );
    const [ loading, setLoading ] = useState( true );
    const [ error, setError ] = useState( null );

    const fetchSubscribers = ( p = 1 ) => {
        setLoading( true );
        setError( null );
        apiFetch( { path: `/smooth-maintenance/v1/subscribers?page=${ p }&per_page=50` } )
            .then( ( res ) => {
                const data = res.data || res;
                setSubscribers( data.subscribers || [] );
                setTotal( data.total || 0 );
                setPage( data.page || p );
                setPages( data.pages || 1 );
            } )
            .catch( () => setError( __( 'Failed to load subscribers.', 'smooth-maintenance' ) ) )
            .finally( () => setLoading( false ) );
    };

    useEffect( () => {
        fetchSubscribers( 1 );
    }, [] );

    const exportCsv = () => {
        const header = 'ID,Email,Subscribed At,IP Address\n';
        const rows = subscribers
            .map( ( s ) => `${ s.id },${ s.email },${ s.subscribed_at },${ s.ip_address || '' }` )
            .join( '\n' );
        const blob = new Blob( [ header + rows ], { type: 'text/csv' } );
        const url = URL.createObjectURL( blob );
        const a = document.createElement( 'a' );
        a.href = url;
        a.download = 'subscribers.csv';
        a.click();
        URL.revokeObjectURL( url );
    };

    const formatDate = ( dateStr ) => {
        if ( ! dateStr ) return '—';
        return new Date( dateStr ).toLocaleDateString( undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        } );
    };

    if ( loading ) {
        return (
            <div className="sm-card">
                <div className="sm-page-header">
                    <div className="sm-skeleton" style={ { width: '120px', height: '24px' } } />
                    <div className="sm-skeleton" style={ { width: '100px', height: '36px', borderRadius: '8px' } } />
                </div>
                { [ 1, 2, 3 ].map( ( i ) => (
                    <div key={ i } style={ { display: 'flex', gap: '16px', padding: '12px 0', borderTop: '1px solid var(--sm-border)' } }>
                        <div className="sm-skeleton" style={ { flex: 2 } } />
                        <div className="sm-skeleton" style={ { flex: 1 } } />
                        <div className="sm-skeleton" style={ { flex: 1 } } />
                    </div>
                ) ) }
            </div>
        );
    }

    if ( error ) {
        return (
            <div className="sm-card sm-empty-state">
                <p style={ { color: 'var(--sm-text-muted)', marginBottom: '16px' } }>{ error }</p>
                <button className="sm-btn-primary" onClick={ () => fetchSubscribers( page ) }>
                    { __( 'Retry', 'smooth-maintenance' ) }
                </button>
            </div>
        );
    }

    return (
        <>
            <div className="sm-card">
                <div className="sm-page-header">
                    <div>
                        <span style={ { fontSize: '2rem', fontWeight: 800, color: 'var(--sm-text)', letterSpacing: '-0.025em' } }>
                            { total }
                        </span>
                        <span style={ { marginLeft: '8px', color: 'var(--sm-text-muted)', fontSize: '0.9rem' } }>
                            { __( 'total subscribers', 'smooth-maintenance' ) }
                        </span>
                    </div>
                    <button
                        className="sm-btn-primary"
                        onClick={ exportCsv }
                        disabled={ subscribers.length === 0 }
                    >
                        { __( 'Export CSV', 'smooth-maintenance' ) }
                    </button>
                </div>

                { subscribers.length === 0 ? (
                    <div className="sm-empty-state">
                        <div style={ { fontSize: '3rem', marginBottom: '16px' } }>📭</div>
                        <p style={ { fontWeight: 600, color: 'var(--sm-text)', marginBottom: '8px' } }>
                            { __( 'No subscribers yet', 'smooth-maintenance' ) }
                        </p>
                        <p style={ { fontSize: '0.875rem' } }>
                            { __( 'Add the Subscriber Form block to your maintenance page to start collecting emails.', 'smooth-maintenance' ) }
                        </p>
                    </div>
                ) : (
                    <table className="sm-subscribers-table">
                        <thead>
                            <tr>
                                <th>{ __( 'Email', 'smooth-maintenance' ) }</th>
                                <th>{ __( 'Subscribed', 'smooth-maintenance' ) }</th>
                                <th>{ __( 'IP Address', 'smooth-maintenance' ) }</th>
                            </tr>
                        </thead>
                        <tbody>
                            { subscribers.map( ( s ) => (
                                <tr key={ s.id }>
                                    <td>{ s.email }</td>
                                    <td>{ formatDate( s.subscribed_at ) }</td>
                                    <td style={ { color: 'var(--sm-text-muted)', fontFamily: 'monospace', fontSize: '0.85rem' } }>
                                        { s.ip_address || '—' }
                                    </td>
                                </tr>
                            ) ) }
                        </tbody>
                    </table>
                ) }
            </div>

            { pages > 1 && (
                <div style={ { display: 'flex', justifyContent: 'center', gap: '8px', marginTop: '8px' } }>
                    <button
                        className="sm-btn-secondary"
                        disabled={ page <= 1 }
                        onClick={ () => fetchSubscribers( page - 1 ) }
                    >
                        { __( '← Previous', 'smooth-maintenance' ) }
                    </button>
                    <span style={ { padding: '8px 16px', color: 'var(--sm-text-muted)', fontSize: '0.875rem' } }>
                        { page } / { pages }
                    </span>
                    <button
                        className="sm-btn-secondary"
                        disabled={ page >= pages }
                        onClick={ () => fetchSubscribers( page + 1 ) }
                    >
                        { __( 'Next →', 'smooth-maintenance' ) }
                    </button>
                </div>
            ) }
        </>
    );
};

export default SubscribersPage;
