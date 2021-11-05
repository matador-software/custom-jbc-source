<?php
/**
 * Plugin Name: Matador Jobs Custom Extension - JBC Team "Most Recent Source"
 * Plugin URI: https://matadorjobs.com/
 * Description: Extends Matador Jobs to add "Most Recent Source" value to Candidate object
 * Author: Matador Software, LLC, Jeremy Scott (jeremyescott)
 * Author URI: http://matadorjobs.com
 * Version: 1.0.0
 * Text Domain: matador-extension-custom-jbc-source
 * Domain Path: /languages
 *
 * Extends Matador Jobs to add "Most Recent Source" value to Candidate object
 *
 * Matador Jobs Custom Extension - JBC Team "Most Recent Source" is free software: you can
 * redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of
 * the License, or any later version.
 *
 * Matador Jobs Custom Extension - JBC Team "Most Recent Source" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Matador Jobs Board. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author     Matador Software LLC, Jeremy Scott, Paul Bearne
 * @version    1.0.0 - 2021-11-02
 */

namespace matador\MatadorJobsCustomJBCSource;

use stdClass;

/**
 * Fetch Existing `customText15` into Candidate GET.
 *
 * Note: `customText15` aka "Most Recent Source" Field
 *
 * @since 2021-11-02
 *
 * @param string $fields
 *
 * @return string
 * @author Jeremy Scott
 */
add_filter( 'matador_bullhorn_candidate_get_candidate_fields', function ( $fields ) {

	return $fields . ',customText15';
} );

/**
 * Create value for `customText15` from Candidate source data
 *
 * Note: `customText15` aka "Most Recent Source" Field
 *
 * @since 2021-11-02
 *
 * @param stdClass $candidate
 * @param array    $application
 *
 * @return stdClass
 * @author Jeremy Scott
 */
add_filter( 'matador_submit_candidate_candidate_data', function ( $candidate, $application ) {

	// Check the submission. Campaign data needs to exist.
	if ( empty( $application['campaign'] ) ) {

		$candidate->customText15 = "";

		return $candidate;
	}

	$source = '';

	/**
	 * Filter: Matador Campaign Tracking Source Field Separator
	 *
	 * @see Matador Core /includes/modules/class-campaign-tracking.php for Filter Documentation
	 */
	$separator = apply_filters( 'matador_campaign_tracking_source_separator', '/' );

	if ( ! empty( $application['campaign']['source'] ) ) {
		$source .= ucwords( str_replace( '_', ' ', $application['campaign']['source'] ) );
	} else {
		$source .= 'NA';
	}

	$source .= $separator;

	if ( ! empty( $application['campaign']['medium'] ) ) {
		$source .= ucwords( str_replace( '_', ' ', $application['campaign']['medium'] ) );
	} else {
		$source .= 'NA';
	}

	$source .= $separator;

	if ( ! empty( $application['campaign']['campaign'] ) ) {
		$source .= ucwords( str_replace( '_', ' ', $application['campaign']['campaign'] ) );
	} else {
		$source .= 'NA';
	}

	$candidate->customText15 = $source;

	return $candidate;

}, 10, 2 );
