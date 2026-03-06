import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { STORE_NAME } from '../store';

const TemplatesPage = () => {
    const [ templates, setTemplates ] = useState( [] );
    const [ loading, setLoading ] = useState( true );

    const { settings } = useSelect( ( select ) => ( {
        settings: select( STORE_NAME ).getSettings(),
    } ), [] );

    const { updateSettings } = useDispatch( STORE_NAME );

    const activeTemplateId = settings?.active_template
        ? parseInt( settings.active_template, 10 )
        : 0;

    useEffect( () => {
        apiFetch( { path: '/wp/v2/sm_template?status=publish&per_page=100&_fields=id,title,date,modified' } )
            .then( ( posts ) => {
                setTemplates( posts );
                setLoading( false );
            } )
            .catch( () => {
                setTemplates( [] );
                setLoading( false );
            } );
    }, [] );

    const handleSetActive = ( id ) => {
        updateSettings( { active_template: id } );
    };

    const formatDate = ( dateString ) => {
        return new Date( dateString ).toLocaleDateString( undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        } );
    };

    if ( loading ) {
        return (
            <>
                <div className="sm-page-header">
                    <div className="sm-skeleton" style={ { width: '120px', height: '28px' } } />
                    <div className="sm-skeleton" style={ { width: '140px', height: '36px' } } />
                </div>
                <div className="sm-templates-grid">
                    { [ 1, 2, 3 ].map( ( i ) => (
                        <div key={ i } className="sm-template-card">
                            <div className="sm-skeleton" style={ { width: '70%', height: '20px' } } />
                            <div className="sm-skeleton" style={ { width: '40%', height: '14px', marginTop: '8px' } } />
                            <div className="sm-skeleton" style={ { width: '100%', height: '36px', marginTop: 'auto', paddingTop: '16px' } } />
                        </div>
                    ) ) }
                </div>
            </>
        );
    }

    if ( templates.length === 0 ) {
        return (
            <div className="sm-empty-state">
                <span style={ { fontSize: '3rem', marginBottom: '16px' } }>🎨</span>
                <h3 style={ { color: 'var(--sm-text)', marginBottom: '8px' } }>
                    { __( 'No templates yet', 'smooth-maintenance' ) }
                </h3>
                <p style={ { marginBottom: '24px' } }>
                    { __( 'Create your first maintenance page design.', 'smooth-maintenance' ) }
                </p>
                <Button
                    variant="primary"
                    href="post-new.php?post_type=sm_template"
                    target="_blank"
                >
                    { __( 'Create your first template', 'smooth-maintenance' ) }
                </Button>
            </div>
        );
    }

    return (
        <>
            <div className="sm-page-header">
                <span className="sm-badge">
                    { templates.length }{ ' ' }
                    { templates.length === 1
                        ? __( 'template', 'smooth-maintenance' )
                        : __( 'templates', 'smooth-maintenance' ) }
                </span>
                <Button
                    variant="primary"
                    href="post-new.php?post_type=sm_template"
                    target="_blank"
                >
                    { __( '+ New Template', 'smooth-maintenance' ) }
                </Button>
            </div>

            <div className="sm-templates-grid">
                { templates.map( ( template ) => {
                    const isActive = template.id === activeTemplateId;
                    return (
                        <div
                            key={ template.id }
                            className={ `sm-template-card${ isActive ? ' is-active' : '' }` }
                        >
                            <p className="sm-template-card__title">
                                { template.title?.rendered || __( '(Untitled)', 'smooth-maintenance' ) }
                                { isActive && (
                                    <span className="sm-active-badge">Active ✓</span>
                                ) }
                            </p>
                            <p className="sm-template-card__date">
                                { __( 'Modified', 'smooth-maintenance' ) }{ ' ' }
                                { formatDate( template.modified ) }
                            </p>
                            <div className="sm-template-card__actions">
                                <Button
                                    variant="secondary"
                                    href={ `post.php?post=${ template.id }&action=edit` }
                                    target="_blank"
                                >
                                    { __( 'Edit →', 'smooth-maintenance' ) }
                                </Button>
                                <Button
                                    variant="primary"
                                    onClick={ () => handleSetActive( template.id ) }
                                    disabled={ isActive }
                                >
                                    { isActive
                                        ? __( 'Active ✓', 'smooth-maintenance' )
                                        : __( 'Set Active', 'smooth-maintenance' ) }
                                </Button>
                            </div>
                        </div>
                    );
                } ) }
            </div>
        </>
    );
};

export default TemplatesPage;
