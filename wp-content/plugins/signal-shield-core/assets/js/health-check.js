/**
 * Network Health Check: turns the server-rendered quiz form into one
 * question per screen with a progress bar, and scores it in the browser.
 * Answers are never sent anywhere.
 */
( function () {
	'use strict';

	var root = document.querySelector( '[data-ss-quiz]' );
	if ( ! root ) {
		return;
	}

	var form = root.querySelector( '[data-ss-quiz-form]' );
	var questions = Array.prototype.slice.call( root.querySelectorAll( '[data-ss-question]' ) );
	var status = root.querySelector( '[data-ss-status]' );
	var count = root.querySelector( '[data-ss-count]' );
	var areaLabel = root.querySelector( '[data-ss-area-label]' );
	var progress = root.querySelector( '[data-ss-progress]' );
	var bar = root.querySelector( '[data-ss-progress-bar]' );
	var live = root.querySelector( '[data-ss-live]' );
	var back = root.querySelector( '[data-ss-back]' );
	var next = root.querySelector( '[data-ss-next]' );
	var scorecard = root.querySelector( '[data-ss-scorecard]' );
	var total = questions.length;
	var current = 0;
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( ! form || ! total || ! scorecard ) {
		return;
	}

	root.classList.add( 'is-enhanced' );

	function format( template, a, b ) {
		return template.replace( '%1$s', a ).replace( '%2$s', b );
	}

	function scrollToTop() {
		var top = root.getBoundingClientRect().top;
		if ( top < 0 || top > window.innerHeight * 0.6 ) {
			root.scrollIntoView( { behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' } );
		}
	}

	function show( index, moveFocus ) {
		current = index;
		questions.forEach( function ( question, i ) {
			question.hidden = i !== index;
		} );

		var question = questions[ index ];
		var text = format( count.getAttribute( 'data-template' ), index + 1, total );
		count.textContent = text;
		areaLabel.textContent = question.getAttribute( 'data-category-label' );
		progress.setAttribute( 'aria-valuenow', String( index + 1 ) );
		progress.setAttribute( 'aria-valuetext', text );
		bar.style.width = ( ( index + 1 ) / total ) * 100 + '%';

		back.hidden = index === 0;
		next.textContent = next.getAttribute( index === total - 1 ? 'data-label-finish' : 'data-label-next' );

		if ( moveFocus ) {
			live.textContent = text;
			scrollToTop();
			question.querySelector( '[data-ss-question-text]' ).focus( { preventScroll: true } );
		}
	}

	function setQuestionError( question, visible ) {
		var error = question.querySelector( '[data-ss-question-error]' );
		var describedBy = ( question.getAttribute( 'aria-describedby' ) || '' ).split( ' ' ).filter( Boolean );
		describedBy = describedBy.filter( function ( id ) {
			return id !== error.id;
		} );
		if ( visible ) {
			describedBy.push( error.id );
		}
		error.hidden = ! visible;
		question.classList.toggle( 'has-error', visible );
		if ( describedBy.length ) {
			question.setAttribute( 'aria-describedby', describedBy.join( ' ' ) );
		} else {
			question.removeAttribute( 'aria-describedby' );
		}
	}

	function rate( ratio ) {
		if ( ratio >= 0.75 ) {
			return 'green';
		}
		if ( ratio >= 0.4 ) {
			return 'amber';
		}
		return 'red';
	}

	// Mirrors ss_core_health_check_score() in health-check.php.
	function score() {
		var areas = {};
		questions.forEach( function ( question ) {
			var key = question.getAttribute( 'data-category' );
			var checked = question.querySelector( 'input:checked' );
			var points = checked ? parseInt( checked.getAttribute( 'data-points' ), 10 ) : 0;
			if ( ! areas[ key ] ) {
				areas[ key ] = { points: 0, max: 0, weakestPoints: Infinity, weakestTip: '' };
			}
			areas[ key ].points += points;
			areas[ key ].max += 2;
			if ( points < areas[ key ].weakestPoints ) {
				areas[ key ].weakestPoints = points;
				areas[ key ].weakestTip = question.getAttribute( 'data-tip' );
			}
		} );
		Object.keys( areas ).forEach( function ( key ) {
			areas[ key ].rating = rate( areas[ key ].max ? areas[ key ].points / areas[ key ].max : 0 );
		} );
		return areas;
	}

	// Mirrors ss_core_health_check_summary() in health-check.php.
	function summaryKey( attention, reds ) {
		if ( reds && attention > 1 ) {
			return 'data-text-red';
		}
		if ( attention === 0 ) {
			return 'data-text-0';
		}
		return attention === 1 ? 'data-text-1' : 'data-text-n';
	}

	function renderScorecard( areas ) {
		var attention = 0;
		var reds = 0;
		var pairs = [];

		Array.prototype.forEach.call( scorecard.querySelectorAll( '[data-ss-area]' ), function ( item ) {
			var key = item.getAttribute( 'data-ss-area' );
			var area = areas[ key ];
			if ( ! area ) {
				return;
			}
			var rating = area.rating;
			var ratingEl = item.querySelector( '[data-ss-rating]' );
			var meaning = item.querySelector( '[data-ss-meaning]' );
			var tip = item.querySelector( '[data-ss-tip]' );

			attention += rating === 'green' ? 0 : 1;
			reds += rating === 'red' ? 1 : 0;
			pairs.push( key + ':' + rating );

			item.className = 'ss-score ss-score--' + rating;
			ratingEl.className = 'ss-rating ss-rating--' + rating;
			Array.prototype.forEach.call( ratingEl.querySelectorAll( '[data-variant]' ), function ( span ) {
				span.hidden = span.getAttribute( 'data-variant' ) !== rating;
			} );
			meaning.textContent = meaning.getAttribute( 'data-' + rating );

			// Green areas get the general "keep it up" tip rendered by PHP
			// for that area; others get the tip for their weakest answer.
			tip.textContent = rating === 'green' ? greenTips[ key ] : area.weakestTip;
		} );

		var summary = scorecard.querySelector( '[data-ss-summary]' );
		summary.textContent = summary.getAttribute( summaryKey( attention, reds ) );

		var book = scorecard.querySelector( '[data-ss-book]' );
		try {
			var url = new URL( book.href, window.location.href );
			url.searchParams.set( 'service', 'health-check' );
			url.searchParams.set( 'result', pairs.join( ',' ) );
			book.href = url.toString();
		} catch ( e ) {
			// Keep the default link.
		}
	}

	function finish() {
		renderScorecard( score() );
		form.hidden = true;
		status.hidden = true;
		scorecard.hidden = false;
		scrollToTop();
		scorecard.querySelector( '.ss-scorecard__title' ).focus( { preventScroll: true } );
	}

	function restart( event ) {
		if ( event ) {
			event.preventDefault();
		}
		form.reset();
		questions.forEach( function ( question ) {
			setQuestionError( question, false );
		} );
		scorecard.hidden = true;
		form.hidden = false;
		status.hidden = false;
		if ( window.history && window.location.search.indexOf( 'ss_hc' ) !== -1 ) {
			window.history.replaceState( null, '', window.location.pathname + '#ss-health-check' );
		}
		show( 0, true );
	}

	// Remember each area's "healthy" tip as rendered on first load.
	var greenTips = {};
	( function collectGreenTips() {
		var tips = root.getAttribute( 'data-green-tips' );
		if ( tips ) {
			try {
				greenTips = JSON.parse( tips );
			} catch ( e ) {
				greenTips = {};
			}
		}
	} )();

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		var question = questions[ current ];
		if ( ! question.querySelector( 'input:checked' ) ) {
			setQuestionError( question, true );
			question.querySelector( 'input' ).focus();
			return;
		}
		setQuestionError( question, false );
		if ( current < total - 1 ) {
			show( current + 1, true );
		} else {
			finish();
		}
	} );

	form.addEventListener( 'change', function ( event ) {
		var question = event.target.closest( '[data-ss-question]' );
		if ( question ) {
			setQuestionError( question, false );
		}
	} );

	back.addEventListener( 'click', function () {
		if ( current > 0 ) {
			show( current - 1, true );
		}
	} );

	scorecard.querySelector( '[data-ss-retake]' ).addEventListener( 'click', restart );

	if ( scorecard.hidden ) {
		status.hidden = false;
		show( 0, false );
	}
} )();
