import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { external } from '@wordpress/icons';

function SmPreviewPanel() {
	const postId = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostId()
	);

	const openPreview = () => {
		if ( ! window.smPreviewData ) {
			return;
		}
		const { nonce, previewUrl } = window.smPreviewData;
		const url = new URL( previewUrl );
		url.searchParams.set( 'sm_preview', '1' );
		url.searchParams.set( 'sm_tid', postId );
		url.searchParams.set( '_wpnonce', nonce );
		window.open( url.toString(), '_blank' );
	};

	return (
		<PluginDocumentSettingPanel
			name="sm-preview-panel"
			title={ __( 'Maintenance Page', 'smooth-maintenance' ) }
			icon="visibility"
		>
			<Button
				variant="secondary"
				icon={ external }
				onClick={ openPreview }
				style={ { width: '100%', justifyContent: 'center' } }
			>
				{ __( 'Preview Maintenance Page', 'smooth-maintenance' ) }
			</Button>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'sm-editor-preview', { render: SmPreviewPanel } );
