import { useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

function SmPreviewButton() {
	const postId = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostId()
	);

	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType()
	);

	useEffect( () => {
		if ( postType !== 'sm_template' ) {
			return;
		}

		const settingsArea = document.querySelector( '.editor-header__settings' );
		if ( ! settingsArea ) {
			return;
		}

		const existingButton = document.getElementById( 'sm-preview-toolbar-btn' );
		if ( existingButton ) {
			return;
		}

		const button = document.createElement( 'button' );
		button.id = 'sm-preview-toolbar-btn';
		button.className = 'components-button is-compact has-icon';
		button.setAttribute( 'aria-label', 'Preview Maintenance Page' );
		button.innerHTML = `
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
				<path d="M19.5 4.5h-7V6h4.44l-5.97 5.97 1.06 1.06L18 7.06v4.44h1.5v-7Zm-13 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3H17v3a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h3V5.5h-3Z"></path>
			</svg>
		`;

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

		button.addEventListener( 'click', openPreview );

		const previewDropdown = settingsArea.querySelector( '.editor-preview-dropdown' );
		if ( previewDropdown && previewDropdown.parentNode ) {
			previewDropdown.parentNode.insertBefore( button, previewDropdown.nextSibling );
		} else {
			settingsArea.appendChild( button );
		}

		return () => {
			button.removeEventListener( 'click', openPreview );
			const btn = document.getElementById( 'sm-preview-toolbar-btn' );
			if ( btn && btn.parentNode ) {
				btn.parentNode.removeChild( btn );
			}
		};
	}, [ postId, postType ] );

	return null;
}

registerPlugin( 'sm-editor-preview', { render: SmPreviewButton } );
