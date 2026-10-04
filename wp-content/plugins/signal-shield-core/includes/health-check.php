<?php
/**
 * Network Health Check: [ss_health_check]
 *
 * The full quiz is rendered as one ordinary form, so it works without
 * JavaScript (answers are scored on the server after a GET submit). With
 * JavaScript, health-check.js shows one question per screen with a progress
 * bar and scores in the browser. Answers are never stored.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * The three scored areas.
 *
 * @return array<string, array{label: string, green_tip: string}>
 */
function ss_core_health_check_categories() {
	return array(
		'wireless' => array(
			'label'     => __( 'Wireless', 'signal-shield-core' ),
			'green_tip' => __( 'Your Wi-Fi sounds healthy. Re-check coverage whenever you renovate, move desks around or add lots of new devices.', 'signal-shield-core' ),
		),
		'network'  => array(
			'label'     => __( 'Network', 'signal-shield-core' ),
			'green_tip' => __( 'Your network foundations look solid. Keep the diagrams up to date every time something changes.', 'signal-shield-core' ),
		),
		'security' => array(
			'label'     => __( 'Security', 'signal-shield-core' ),
			'green_tip' => __( 'Good security habits. Keep testing your backups and review who has access at least once a year.', 'signal-shield-core' ),
		),
	);
}

/**
 * Rating labels.
 *
 * @return array<string, string>
 */
function ss_core_health_check_rating_labels() {
	return array(
		'green' => __( 'Green', 'signal-shield-core' ),
		'amber' => __( 'Amber', 'signal-shield-core' ),
		'red'   => __( 'Red', 'signal-shield-core' ),
	);
}

/**
 * One-line meaning of each rating.
 *
 * @return array<string, string>
 */
function ss_core_health_check_rating_summaries() {
	return array(
		'green' => __( 'In good shape', 'signal-shield-core' ),
		'amber' => __( 'Worth a closer look', 'signal-shield-core' ),
		'red'   => __( 'Needs attention soon', 'signal-shield-core' ),
	);
}

/**
 * The ten questions. Each option scores 2 (good), 1 (partly) or 0 (risk).
 * "Not sure" scores 0: not knowing is itself a risk worth flagging.
 *
 * @return array<int, array{id: string, category: string, question: string, help: string, tip: string, options: array<int, array{0: string, 1: int}>}>
 */
function ss_core_health_check_questions() {
	$not_sure = __( 'Not sure', 'signal-shield-core' );

	return array(
		array(
			'id'       => 'coverage',
			'category' => 'wireless',
			'question' => __( 'How good is the Wi-Fi in the places people actually work?', 'signal-shield-core' ),
			'help'     => __( 'Think about meeting rooms, upper floors, corners and outdoor areas, not just the spot next to the router.', 'signal-shield-core' ),
			'tip'      => __( 'Walk the building with a phone speed test and mark the weak spots on a floor plan. That map is the starting point for any fix.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Strong everywhere', 'signal-shield-core' ), 2 ),
				array( __( 'Fine in most places, with a few dead spots', 'signal-shield-core' ), 1 ),
				array( __( 'Patchy, and people complain regularly', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'busy',
			'category' => 'wireless',
			'question' => __( 'What happens to the Wi-Fi when your building is at its busiest?', 'signal-shield-core' ),
			'help'     => __( 'For example a full conference room, a busy restaurant or the first lesson of the day.', 'signal-shield-core' ),
			'tip'      => __( 'Slowdowns at busy times usually mean too few access points in the busiest rooms, not a slow internet line. Check that before paying for more bandwidth.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'It holds up fine', 'signal-shield-core' ), 2 ),
				array( __( 'It slows down noticeably', 'signal-shield-core' ), 1 ),
				array( __( 'It struggles or drops out', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'survey',
			'category' => 'wireless',
			'question' => __( 'When did a specialist last check or survey your Wi-Fi?', 'signal-shield-core' ),
			'help'     => __( 'A survey measures signal strength room by room, usually with special software.', 'signal-shield-core' ),
			'tip'      => __( 'Get a Wi-Fi survey after any renovation, new partition walls or a big jump in staff numbers. Buildings change and Wi-Fi needs to keep up.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Within the last two years', 'signal-shield-core' ), 2 ),
				array( __( 'More than two years ago', 'signal-shield-core' ), 1 ),
				array( __( 'Never, it was just installed', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'guest',
			'category' => 'network',
			'question' => __( 'Do guests and staff use separate Wi-Fi networks?', 'signal-shield-core' ),
			'help'     => __( 'Guests include visitors, contractors and anyone’s personal phone.', 'signal-shield-core' ),
			'tip'      => __( 'Put guests on their own network that reaches the internet and nothing else. It is one of the cheapest, most effective fixes there is.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Yes, and guests cannot reach any of our systems', 'signal-shield-core' ), 2 ),
				array( __( 'Different names, but we are not sure they are truly separate', 'signal-shield-core' ), 1 ),
				array( __( 'No, everyone uses the same network', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'resilience',
			'category' => 'network',
			'question' => __( 'If your main internet line or a key piece of network equipment failed today, what would happen?', 'signal-shield-core' ),
			'help'     => '',
			'tip'      => __( 'Add a second internet line from a different provider and keep a spare for your most important switch. A day of downtime usually costs more than both.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'We would switch to a backup and keep working', 'signal-shield-core' ), 2 ),
				array( __( 'We would be down for a few hours while someone fixes it', 'signal-shield-core' ), 1 ),
				array( __( 'We would be down for a day or more', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'documentation',
			'category' => 'network',
			'question' => __( 'If a new IT person started tomorrow, could they find out what is connected where?', 'signal-shield-core' ),
			'help'     => __( 'For example from network diagrams, an equipment list or labelled cables.', 'signal-shield-core' ),
			'tip'      => __( 'Ask whoever manages your network for a simple labelled diagram and an equipment list. If they cannot produce one, that is a risk in itself.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Yes, our diagrams and records are up to date', 'signal-shield-core' ), 2 ),
				array( __( 'Partly, some notes exist but they are out of date', 'signal-shield-core' ), 1 ),
				array( __( 'No, it is all in one person’s head', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'firewall',
			'category' => 'security',
			'question' => __( 'When was your firewall last reviewed?', 'signal-shield-core' ),
			'help'     => __( 'The firewall is the device that controls what can get in and out of your network.', 'signal-shield-core' ),
			'tip'      => __( 'Have your firewall rules reviewed every year. Old openings left by past contractors are one of the most common ways attackers get in.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'In the last 12 months', 'signal-shield-core' ), 2 ),
				array( __( 'One to three years ago', 'signal-shield-core' ), 1 ),
				array( __( 'More than three years ago, or never', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'backups',
			'category' => 'security',
			'question' => __( 'If ransomware locked your files tomorrow, could you get them back?', 'signal-shield-core' ),
			'help'     => __( 'Ransomware is software that scrambles your files until you pay.', 'signal-shield-core' ),
			'tip'      => __( 'Test a restore this month: pick a folder, delete a copy and bring it back from backup. Keep at least one backup offline, where ransomware cannot reach it.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Yes, we back up automatically and have tested a restore recently', 'signal-shield-core' ), 2 ),
				array( __( 'We have backups, but have never tested restoring them', 'signal-shield-core' ), 1 ),
				array( __( 'We do not back up regularly', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'passwords',
			'category' => 'security',
			'question' => __( 'How do staff sign in to email and important systems?', 'signal-shield-core' ),
			'help'     => '',
			'tip'      => __( 'Turn on two-step sign-in for email and remote access first. On its own it stops most attacks that use stolen passwords.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Strong passwords plus a second step, like a code on their phone', 'signal-shield-core' ), 2 ),
				array( __( 'Passwords only, with some rules about length', 'signal-shield-core' ), 1 ),
				array( __( 'Simple or shared passwords', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
		array(
			'id'       => 'updates',
			'category' => 'security',
			'question' => __( 'How are computers, phones and network equipment kept up to date?', 'signal-shield-core' ),
			'help'     => '',
			'tip'      => __( 'Switch on automatic updates and list any device that no longer gets them. Unsupported equipment should be replaced or kept off the main network.', 'signal-shield-core' ),
			'options'  => array(
				array( __( 'Updates install automatically and someone checks monthly', 'signal-shield-core' ), 2 ),
				array( __( 'Someone updates things when they remember', 'signal-shield-core' ), 1 ),
				array( __( 'Rarely, and some devices are very old', 'signal-shield-core' ), 0 ),
				array( $not_sure, 0 ),
			),
		),
	);
}

/**
 * Rating for a share of the maximum score. Mirrors rate() in health-check.js.
 *
 * @param float $ratio 0..1.
 * @return string green|amber|red
 */
function ss_core_health_check_rate( $ratio ) {
	if ( $ratio >= 0.75 ) {
		return 'green';
	}
	if ( $ratio >= 0.4 ) {
		return 'amber';
	}
	return 'red';
}

/**
 * Score a set of answers. Mirrors score() in health-check.js.
 *
 * @param array<string, int> $answers Question id => option index.
 * @return array<string, array{rating: string, points: int, max: int, tip: string}>|null Null when incomplete.
 */
function ss_core_health_check_score( $answers ) {
	$questions = ss_core_health_check_questions();
	$results   = array();

	foreach ( ss_core_health_check_categories() as $key => $category ) {
		$results[ $key ] = array(
			'points'     => 0,
			'max'        => 0,
			'weakest'    => null,
			'weakest_pt' => PHP_INT_MAX,
		);
	}

	foreach ( $questions as $question ) {
		if ( ! isset( $answers[ $question['id'] ], $question['options'][ $answers[ $question['id'] ] ] ) ) {
			return null;
		}
		$points = $question['options'][ $answers[ $question['id'] ] ][1];
		$cat    = $question['category'];

		$results[ $cat ]['points'] += $points;
		$results[ $cat ]['max']    += 2;
		if ( $points < $results[ $cat ]['weakest_pt'] ) {
			$results[ $cat ]['weakest_pt'] = $points;
			$results[ $cat ]['weakest']    = $question['tip'];
		}
	}

	$categories = ss_core_health_check_categories();
	foreach ( $results as $key => $result ) {
		$rating          = ss_core_health_check_rate( $result['max'] ? $result['points'] / $result['max'] : 0 );
		$results[ $key ] = array(
			'rating' => $rating,
			'points' => $result['points'],
			'max'    => $result['max'],
			'tip'    => 'green' === $rating ? $categories[ $key ]['green_tip'] : $result['weakest'],
		);
	}
	return $results;
}

/**
 * Small inline icon that gives each rating a distinct shape, so the result
 * never depends on colour alone.
 *
 * @param string $rating green|amber|red.
 * @return string
 */
function ss_core_rating_icon( $rating ) {
	$paths = array(
		'green' => '<circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/>',
		'amber' => '<path d="M12 3.5 21 19.5H3z"/><path d="M12 10v4M12 17h.01"/>',
		'red'   => '<path d="M8.5 3h7L21 8.5v7L15.5 21h-7L3 15.5v-7z"/><path d="m9 9 6 6M15 9l-6 6"/>',
	);
	return '<svg class="ss-rating__icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $rating ] . '</svg>';
}

/**
 * One-sentence summary of a scorecard. Mirrors summaryKey() in health-check.js.
 *
 * @param array $results Scored results.
 * @return string
 */
function ss_core_health_check_summary( $results ) {
	$ratings   = wp_list_pluck( $results, 'rating' );
	$counts    = array_count_values( $ratings );
	$attention = count( $ratings ) - ( $counts['green'] ?? 0 );
	if ( ! empty( $counts['red'] ) && $attention > 1 ) {
		return __( 'Some areas need attention soon. Start with the Red ones: the tips below are quick wins.', 'signal-shield-core' );
	}
	if ( 0 === $attention ) {
		return __( 'All three areas look healthy. A short consultation can confirm nothing is hiding.', 'signal-shield-core' );
	}
	if ( 1 === $attention ) {
		return __( 'One area needs attention. The tip below is a good place to start.', 'signal-shield-core' );
	}
	return __( 'Several areas are worth a closer look. The tips below are a good place to start.', 'signal-shield-core' );
}

/**
 * The consultation URL carrying the result into the contact form.
 *
 * @param array $results Scored results.
 * @return string
 */
function ss_core_health_check_contact_url( $results ) {
	$pairs = array();
	foreach ( $results as $key => $result ) {
		$pairs[] = $key . ':' . $result['rating'];
	}
	return add_query_arg(
		array(
			'service' => 'health-check',
			'result'  => implode( ',', $pairs ),
		),
		home_url( '/contact/' )
	) . '#ss-contact';
}

/**
 * Render the scorecard. Used by PHP (no-JS) and as the template JS fills in.
 *
 * @param array|null $results Scored results, or null for an empty template.
 * @return string
 */
function ss_core_render_scorecard( $results ) {
	$categories = ss_core_health_check_categories();
	$labels     = ss_core_health_check_rating_labels();
	$summaries  = ss_core_health_check_rating_summaries();
	ob_start();
	?>
	<div class="ss-scorecard" data-ss-scorecard<?php echo $results ? '' : ' hidden'; ?>>
		<h2 class="ss-scorecard__title" tabindex="-1"><?php esc_html_e( 'Your network health scorecard', 'signal-shield-core' ); ?></h2>
		<p class="ss-scorecard__summary" data-ss-summary
			data-text-0="<?php esc_attr_e( 'All three areas look healthy. A short consultation can confirm nothing is hiding.', 'signal-shield-core' ); ?>"
			data-text-1="<?php esc_attr_e( 'One area needs attention. The tip below is a good place to start.', 'signal-shield-core' ); ?>"
			data-text-n="<?php esc_attr_e( 'Several areas are worth a closer look. The tips below are a good place to start.', 'signal-shield-core' ); ?>"
			data-text-red="<?php esc_attr_e( 'Some areas need attention soon. Start with the Red ones: the tips below are quick wins.', 'signal-shield-core' ); ?>">
			<?php
			if ( $results ) {
				echo esc_html( ss_core_health_check_summary( $results ) );
			}
			?>
		</p>
		<ul class="ss-scorecard__list">
			<?php foreach ( $categories as $key => $category ) : ?>
				<?php $rating = $results ? $results[ $key ]['rating'] : 'green'; ?>
				<li class="ss-score ss-score--<?php echo esc_attr( $rating ); ?>" data-ss-area="<?php echo esc_attr( $key ); ?>">
					<div class="ss-score__head">
						<h3 class="ss-score__area"><?php echo esc_html( $category['label'] ); ?></h3>
						<p class="ss-rating ss-rating--<?php echo esc_attr( $rating ); ?>" data-ss-rating>
							<?php
							foreach ( array( 'green', 'amber', 'red' ) as $variant ) {
								printf(
									'<span data-variant="%1$s"%2$s>%3$s<span class="screen-reader-text">%4$s </span>%5$s</span>',
									esc_attr( $variant ),
									$variant === $rating ? '' : ' hidden',
									ss_core_rating_icon( $variant ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
									esc_html__( 'Rating:', 'signal-shield-core' ),
									esc_html( $labels[ $variant ] )
								);
							}
							?>
						</p>
					</div>
					<p class="ss-score__meaning" data-ss-meaning
						data-green="<?php echo esc_attr( $summaries['green'] ); ?>"
						data-amber="<?php echo esc_attr( $summaries['amber'] ); ?>"
						data-red="<?php echo esc_attr( $summaries['red'] ); ?>"><?php echo esc_html( $summaries[ $rating ] ); ?></p>
					<p class="ss-score__tip"><span class="ss-score__tip-label"><?php esc_html_e( 'Tip:', 'signal-shield-core' ); ?></span> <span data-ss-tip><?php echo esc_html( $results ? $results[ $key ]['tip'] : '' ); ?></span></p>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="ss-scorecard__actions">
			<a class="ss-button wp-element-button" data-ss-book href="<?php echo esc_url( $results ? ss_core_health_check_contact_url( $results ) : add_query_arg( 'service', 'health-check', home_url( '/contact/' ) ) . '#ss-contact' ); ?>"><?php esc_html_e( 'Book a free consultation', 'signal-shield-core' ); ?></a>
			<a class="ss-button ss-button--ghost" data-ss-retake href="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/network-health-check/' ) ); ?>"><?php esc_html_e( 'Retake the quiz', 'signal-shield-core' ); ?></a>
		</div>
		<p class="ss-scorecard__note"><?php esc_html_e( 'This quick check gives a general picture only. A site visit is the only way to know for sure.', 'signal-shield-core' ); ?></p>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * [ss_health_check]
 *
 * @return string
 */
function ss_core_health_check_shortcode() {
	wp_enqueue_style( 'ss-core' );
	wp_enqueue_script( 'ss-core-health-check' );

	$questions  = ss_core_health_check_questions();
	$categories = ss_core_health_check_categories();
	$total      = count( $questions );

	// No-JS path: answers arrive as GET parameters.
	$answers   = array();
	$submitted = false;
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only scoring of a public quiz.
	if ( isset( $_GET['ss_hc'] ) ) {
		$submitted = true;
		foreach ( $questions as $question ) {
			$key = 'q_' . $question['id'];
			if ( isset( $_GET[ $key ] ) && '' !== $_GET[ $key ] ) {
				$answers[ $question['id'] ] = absint( $_GET[ $key ] );
			}
		}
	}
	// phpcs:enable
	$results = $submitted ? ss_core_health_check_score( $answers ) : null;

	ob_start();
	?>
	<div class="ss-quiz" id="ss-health-check" data-ss-quiz data-total="<?php echo esc_attr( (string) $total ); ?>" data-green-tips="<?php echo esc_attr( (string) wp_json_encode( wp_list_pluck( $categories, 'green_tip' ) ) ); ?>">
		<div class="ss-quiz__status" data-ss-status hidden>
			<div class="ss-quiz__status-row">
				<p class="ss-quiz__count" data-ss-count
					data-template="<?php /* translators: 1: question number, 2: total questions */ echo esc_attr( __( 'Question %1$s of %2$s', 'signal-shield-core' ) ); ?>"></p>
				<p class="ss-quiz__area" data-ss-area-label></p>
			</div>
			<div class="ss-progress" role="progressbar" aria-label="<?php esc_attr_e( 'Health check progress', 'signal-shield-core' ); ?>" aria-valuemin="0" aria-valuemax="<?php echo esc_attr( (string) $total ); ?>" aria-valuenow="0" data-ss-progress>
				<span class="ss-progress__bar" data-ss-progress-bar></span>
			</div>
		</div>
		<p class="screen-reader-text" aria-live="polite" data-ss-live></p>

		<?php if ( $submitted && ! $results ) : ?>
			<div class="ss-form__summary" role="alert">
				<p class="ss-form__summary-title"><?php esc_html_e( 'Please answer every question to see your scorecard.', 'signal-shield-core' ); ?></p>
			</div>
		<?php endif; ?>

		<form class="ss-quiz__form" method="get" action="<?php echo esc_url( ( get_permalink() ? get_permalink() : home_url( '/network-health-check/' ) ) . '#ss-health-check' ); ?>" data-ss-quiz-form<?php echo $results ? ' hidden' : ''; ?>>
			<input type="hidden" name="ss_hc" value="1">
			<?php foreach ( $questions as $index => $question ) : ?>
				<?php
				$qid      = 'ss-q-' . $question['id'];
				$current  = $answers[ $question['id'] ] ?? null;
				$help_id  = $qid . '-help';
				$error_id = $qid . '-error';
				?>
				<fieldset class="ss-q" id="<?php echo esc_attr( $qid ); ?>" data-ss-question data-category="<?php echo esc_attr( $question['category'] ); ?>" data-category-label="<?php echo esc_attr( $categories[ $question['category'] ]['label'] ); ?>" data-tip="<?php echo esc_attr( $question['tip'] ); ?>"<?php echo $question['help'] ? ' aria-describedby="' . esc_attr( $help_id ) . '"' : ''; ?>>
					<legend class="ss-q__legend">
						<span class="ss-q__num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
						<span class="ss-q__text" tabindex="-1" data-ss-question-text><?php echo esc_html( $question['question'] ); ?></span>
					</legend>
					<?php if ( $question['help'] ) : ?>
						<p class="ss-q__help" id="<?php echo esc_attr( $help_id ); ?>"><?php echo esc_html( $question['help'] ); ?></p>
					<?php endif; ?>
					<div class="ss-q__options">
						<?php foreach ( $question['options'] as $option_index => $option ) : ?>
							<?php $oid = $qid . '-' . $option_index; ?>
							<div class="ss-option">
								<input type="radio" id="<?php echo esc_attr( $oid ); ?>" name="q_<?php echo esc_attr( $question['id'] ); ?>" value="<?php echo esc_attr( (string) $option_index ); ?>" data-points="<?php echo esc_attr( (string) $option[1] ); ?>"<?php checked( $current, $option_index ); ?>>
								<label for="<?php echo esc_attr( $oid ); ?>"><?php echo esc_html( $option[0] ); ?></label>
							</div>
						<?php endforeach; ?>
					</div>
					<p class="ss-field__error ss-q__error" id="<?php echo esc_attr( $error_id ); ?>" data-ss-question-error hidden><?php esc_html_e( 'Choose an answer to continue. If you do not know, pick “Not sure”.', 'signal-shield-core' ); ?></p>
				</fieldset>
			<?php endforeach; ?>

			<div class="ss-quiz__nav">
				<button type="button" class="ss-button ss-button--ghost" data-ss-back hidden><?php esc_html_e( 'Back', 'signal-shield-core' ); ?></button>
				<button type="submit" class="ss-button wp-element-button" data-ss-next
					data-label-next="<?php esc_attr_e( 'Next question', 'signal-shield-core' ); ?>"
					data-label-finish="<?php esc_attr_e( 'See my scorecard', 'signal-shield-core' ); ?>"><?php esc_html_e( 'See my scorecard', 'signal-shield-core' ); ?></button>
			</div>
		</form>

		<?php echo ss_core_render_scorecard( $results ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'ss_health_check', 'ss_core_health_check_shortcode' );
