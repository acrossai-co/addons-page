/**
 * Handles free add-on Install and Activate button clicks via admin-ajax.
 */
export function initInstall( page, config ) {
	page.addEventListener( 'click', ( e ) => {
		const btn = e.target.closest( '.wpb-addons-page__btn' );
		if ( ! btn ) return;

		const action = btn.dataset.action;
		if ( action !== 'install' && action !== 'activate' ) return;

		e.preventDefault();
		handleInstallOrActivate( btn, action, config );
	} );
}

async function handleInstallOrActivate( btn, action, config ) {
	const card      = btn.closest( '.wpb-addons-page__card' );
	const errorEl   = card ? card.querySelector( '.wpb-addons-page__card-error' ) : null;
	const slug      = btn.dataset.slug;
	const source    = btn.dataset.source;
	const pluginFile = btn.dataset.pluginFile || '';

	// Busy state.
	const originalLabel = btn.textContent.trim();
	const originalAriaLabel = btn.getAttribute( 'aria-label' );
	btn.disabled = true;
	btn.setAttribute( 'aria-busy', 'true' );
	btn.textContent = action === 'install'
		? config.i18n.installing
		: config.i18n.activating;

	if ( errorEl ) errorEl.hidden = true;

	const body = new FormData();
	body.append( 'action', action === 'install' ? 'wpb_addons_install_free' : 'wpb_addons_activate' );
	body.append( 'nonce', config.nonce );
	body.append( 'slug', slug );
	if ( action === 'install' ) {
		body.append( 'source', source );
	} else {
		body.append( 'plugin_file', pluginFile );
	}

	try {
		const response = await fetch( config.ajaxUrl, { method: 'POST', body } );
		const json     = await response.json();

		if ( json.success ) {
			// Update button to new state returned by server.
			const state = json.data.state;
			btn.textContent = state.label;
			btn.setAttribute( 'aria-label', state.label + ' ' + ( btn.dataset.slug || '' ) );
			btn.dataset.action = state.action;
			btn.disabled = ! state.enabled;
			btn.setAttribute( 'aria-disabled', String( ! state.enabled ) );
			btn.setAttribute( 'aria-busy', 'false' );
			btn.className = 'button ' + state.css_class + ' wpb-addons-page__btn';
			if ( json.data.plugin_file ) {
				btn.dataset.pluginFile = json.data.plugin_file;
			}
			btn.focus();
		} else {
			restoreButton( btn, originalLabel, originalAriaLabel );
			showError( errorEl, json.data?.message || config.i18n.installFailed );
		}
	} catch ( err ) {
		restoreButton( btn, originalLabel, originalAriaLabel );
		showError( errorEl, config.i18n.installFailed );
	}
}

function restoreButton( btn, label, ariaLabel ) {
	btn.textContent = label;
	btn.setAttribute( 'aria-label', ariaLabel );
	btn.disabled = false;
	btn.setAttribute( 'aria-busy', 'false' );
}

function showError( errorEl, message ) {
	if ( ! errorEl ) return;
	errorEl.textContent = message;
	errorEl.hidden = false;
}
