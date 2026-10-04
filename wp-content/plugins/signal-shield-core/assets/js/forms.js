/**
 * Contact and checklist forms: inline validation, an error summary, and
 * submission through the REST API without a page reload. Without this
 * script the forms still post normally and are validated on the server.
 */
( function () {
	'use strict';

	var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
	var PHONE = /^\+?[0-9\s().-]+$/;

	function fieldsOf( form ) {
		return Array.prototype.slice.call(
			form.querySelectorAll( 'input[name]:not([type="hidden"]):not([type="checkbox"]):not([name="website"]), select[name], textarea[name]' )
		);
	}

	// Mirrors the server rules in contact-form.php and checklist.php.
	function check( input ) {
		var value = input.value.trim();
		var rules = ( input.getAttribute( 'data-rules' ) || '' ).split( '|' ).filter( Boolean );

		if ( input.required && ! value ) {
			return input.getAttribute( 'data-msg-required' ) || '';
		}
		if ( ! value ) {
			return '';
		}
		for ( var i = 0; i < rules.length; i++ ) {
			var rule = rules[ i ];
			var invalid = input.getAttribute( 'data-msg-invalid' ) || '';
			if ( rule === 'email' && ! EMAIL.test( value ) ) {
				return invalid;
			}
			if ( rule === 'phone' ) {
				var digits = value.replace( /\D/g, '' ).length;
				if ( ! PHONE.test( value ) || digits < 7 || digits > 15 ) {
					return invalid;
				}
			}
			if ( rule.indexOf( 'min:' ) === 0 && value.length < parseInt( rule.slice( 4 ), 10 ) ) {
				return invalid;
			}
		}
		var max = parseInt( input.getAttribute( 'maxlength' ) || '0', 10 );
		if ( max && value.length > max ) {
			return input.getAttribute( 'data-msg-invalid' ) || '';
		}
		return '';
	}

	function setError( input, message ) {
		var field = input.closest( '.ss-field' );
		var error = document.getElementById( input.id + '-error' );
		var ids = ( input.getAttribute( 'aria-describedby' ) || '' ).split( ' ' ).filter( function ( id ) {
			return id && ( ! error || id !== error.id );
		} );

		if ( message ) {
			if ( error ) {
				error.textContent = message;
				error.hidden = false;
				ids.push( error.id );
			}
			input.setAttribute( 'aria-invalid', 'true' );
			if ( field ) {
				field.classList.add( 'has-error' );
			}
		} else {
			if ( error ) {
				error.textContent = '';
				error.hidden = true;
			}
			input.removeAttribute( 'aria-invalid' );
			if ( field ) {
				field.classList.remove( 'has-error' );
			}
		}

		if ( ids.length ) {
			input.setAttribute( 'aria-describedby', ids.join( ' ' ) );
		} else {
			input.removeAttribute( 'aria-describedby' );
		}
	}

	function showSummary( form, errors ) {
		var summary = form.querySelector( '.ss-form__summary' );
		if ( ! summary ) {
			return false;
		}
		var list = summary.querySelector( 'ul' );
		list.innerHTML = '';
		errors.forEach( function ( item ) {
			var li = document.createElement( 'li' );
			if ( item.input ) {
				var link = document.createElement( 'a' );
				link.href = '#' + item.input.id;
				link.textContent = item.message;
				link.addEventListener( 'click', function ( event ) {
					event.preventDefault();
					item.input.focus();
				} );
				li.appendChild( link );
			} else {
				li.textContent = item.message;
			}
			list.appendChild( li );
		} );
		summary.hidden = false;
		summary.focus();
		return true;
	}

	function hideSummary( form ) {
		var summary = form.querySelector( '.ss-form__summary' );
		if ( summary ) {
			summary.hidden = true;
		}
	}

	function reportErrors( form, errors ) {
		// errors: [{ input?: Element, message: string }]
		if ( ! showSummary( form, errors ) ) {
			var firstWithInput = errors.filter( function ( item ) {
				return item.input;
			} )[ 0 ];
			var target = firstWithInput ? firstWithInput.input : fieldsOf( form )[ 0 ];
			errors.forEach( function ( item ) {
				if ( ! item.input && target ) {
					setError( target, item.message );
				}
			} );
			if ( target ) {
				target.focus();
			}
		}
	}

	function showSuccess( form, data ) {
		var wrap = form.closest( '.ss-form-wrap' );
		var success = wrap && wrap.querySelector( '.ss-form-success' );
		if ( ! success ) {
			return;
		}
		var text = success.querySelector( '.ss-form-success__text' );
		if ( text && data.message ) {
			text.textContent = data.message;
		}
		var download = success.querySelector( '.ss-download-link' );
		if ( download && data.download_url ) {
			download.href = data.download_url;
		}
		form.hidden = true;
		success.hidden = false;
		success.focus();
	}

	function enhance( form ) {
		var fields = fieldsOf( form );
		var button = form.querySelector( '[type="submit"]' );
		var buttonLabel = button ? button.textContent : '';
		var busy = false;

		fields.forEach( function ( input ) {
			input.addEventListener( 'blur', function () {
				if ( input.value.trim() || input.getAttribute( 'aria-invalid' ) ) {
					setError( input, check( input ) );
				}
			} );
			input.addEventListener( input.tagName === 'SELECT' ? 'change' : 'input', function () {
				if ( input.getAttribute( 'aria-invalid' ) ) {
					setError( input, check( input ) );
				}
			} );
		} );

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			if ( busy ) {
				return;
			}

			var errors = [];
			fields.forEach( function ( input ) {
				var message = check( input );
				setError( input, message );
				if ( message ) {
					errors.push( { input: input, message: message } );
				}
			} );
			if ( errors.length ) {
				reportErrors( form, errors );
				return;
			}
			hideSummary( form );

			busy = true;
			form.setAttribute( 'aria-busy', 'true' );
			if ( button ) {
				button.disabled = true;
				button.textContent = form.getAttribute( 'data-sending' ) || buttonLabel;
			}

			function done() {
				busy = false;
				form.removeAttribute( 'aria-busy' );
				if ( button ) {
					button.disabled = false;
					button.textContent = buttonLabel;
				}
			}

			fetch( form.getAttribute( 'data-endpoint' ), {
				method: 'POST',
				body: new FormData( form ),
				headers: { Accept: 'application/json' },
				credentials: 'same-origin'
			} )
				.then( function ( response ) {
					return response.json().catch( function () {
						return { ok: false };
					} );
				} )
				.then( function ( data ) {
					done();
					if ( data && data.ok ) {
						showSuccess( form, data );
						return;
					}
					var serverErrors = [];
					var map = ( data && data.errors ) || {};
					Object.keys( map ).forEach( function ( name ) {
						var input = name === '_form' ? null : form.querySelector( '[name="' + name + '"]' );
						if ( input ) {
							setError( input, map[ name ] );
						}
						serverErrors.push( { input: input, message: map[ name ] } );
					} );
					if ( ! serverErrors.length ) {
						serverErrors.push( { input: null, message: form.getAttribute( 'data-error-generic' ) } );
					}
					reportErrors( form, serverErrors );
				} )
				.catch( function () {
					done();
					reportErrors( form, [ { input: null, message: form.getAttribute( 'data-error-generic' ) } ] );
				} );
		} );
	}

	Array.prototype.forEach.call( document.querySelectorAll( 'form[data-ss-form]' ), enhance );

	// After a no-JS round trip, move focus to the result.
	var landed = document.querySelector( '.ss-form-wrap .ss-form-success:not([hidden]), .ss-form__summary:not([hidden])' );
	if ( landed && window.location.search.indexOf( 'ss_state=' ) !== -1 ) {
		landed.focus();
	}
} )();
