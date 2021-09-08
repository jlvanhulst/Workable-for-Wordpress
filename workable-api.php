<?php
/**
 * Plugin Name:     Workable API Wrapper
 * Plugin URI:      https://workable.readme.io/v3/docs
 * Description:     WordPress accessible wrapper for the Workable API.
 * Author:          Katherine White and Miriam Goldman
 * Author URI:      https://kanopi.com/
 * Text Domain:     workable-api
 * Domain Path:     /languages
 * Version:         1.0.3
 *
 * @package         Workable_Api
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.3' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and API hooks.
 */
require plugin_dir_path( __FILE__ ) . 'inc/classes/class-workable-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_workable_api() {

	$plugin = new Workable_Api();
	$plugin->run();

}
run_workable_api();

// expose some methods for easier theming.

/**
 * Get Featured Job Listings
 * Retrieve the listings to feature on the public-facing site.
 *
 * @see Workable_Api_Wrapper::get_job()
 * @return object JSON object of featured job listings in display order.
 */
function workable_api_get_featured_jobs() {
	$plugin   = new Workable_Api();
	$workable = new Workable_Api_Wrapper( $plugin->get_plugin_name(), PLUGIN_NAME_VERSION );
	$options  = array();
	return $workable->get_featured_jobs();
}

/**
 * A basic shortcode to return the job listings.
 *
 * @return string $output The shortcode output.
 */
function workable_api_shortcode() {
	$job_listing = workable_api_get_featured_jobs();
	if ( $job_listing ) :
		$output = '<div class="featured__jobs-listing>';
		foreach ( $job_listing as $featured_job ) :
			$output .= '<div class="featured__job">';
			$output .= '<h2>' . $featured_job->title . '</h2>';
			$output .= '<h3>' . $featured_job->department . '</h3>';
			$output .= '<p><a href="' . $featured_job->shortlink . '">Apply Now</a></p>';
			$output .= '</div>';
	endforeach;
		$output .= '</div>';
	endif;

	return $output;
}
add_shortcode( 'workable_job_listing', 'workable_api_shortcode' );


/**
 * A basic shortcode to return the job listings.
 *
 * All shortcode parameters are optional. Default is to list all published jobs,
 * all departments with an 'Apply now' message under each job.
 *
 * Department will be suppressed if department was given.
 *
 * Apply now button will be suppressed and Title will be clickable instead if apply=''
 * Apply='xxx' will replace the Apply Now message with 'xxx'
 *
 * nojobs can be used to change the message that is shown when no jobs are found.
 *
 * Default:
 *
 * [workable_jobs state='published' department='' apply='Apply Now' nojobs='No Current Job Openings' ]
 *
 * @return string $output The shortcode output.
 */
function workable_api_listjobs( $atts =[],$content = null, $tag='' ) {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $output ='';
    $workable_atts = shortcode_atts(
        array(
            'department' => '', 'state' => 'published', 'apply' => 'Apply Now', 'nojobs' => 'No Current Job Openings'
        ), $atts, $tag
    );
    $plugin   = new Workable_Api();
    $workable = new Workable_Api_Wrapper( $plugin->get_plugin_name(), PLUGIN_NAME_VERSION );

    $job_listing  =  $workable->get_jobs( array( 'state' => $workable_atts['state']) );

    if ( $job_listing ) {
        $count = 0;
        $output .= '<div class="workable_jobs-listing">';
        foreach ($job_listing->jobs as $job) {
            error_log($job->department);
            if ($workable_atts['department'] === '' || $job->department == $workable_atts['department']) {
                $count++;
                $output .= '<div class="workable_job">';
                if (!($workable_atts['apply'] === '')) {
                    $wrap_begin = '';
                    $wrap_end = '';
                } else {
                    $wrap_begin = '<a href="' . $job->shortlink . '">';
                    $wrap_end = '</a>';
                };
                $output .= '<h2>' . $wrap_begin . $job->title . $wrap_end . '</h2>';
                if (!$workable_atts['department'] === '') {
                    $output .= '<h3>' . $job->department . '</h3>';
                };
                if (!($workable_atts['apply'] === '')) {
                    $output .= '<p><a href="' . $job->shortlink . '">' . $workable_atts['apply'] . '</a></p>';
                }
                $output .= '</div>';
            };
        }
        if ($count == 0) {
            $output .= '<div class="workable_no-jobs">'. $workable_atts['nojobs'] .'</div>';
        };
        $output .= '</div>';
    };
    return $output;
}
add_shortcode( 'workable_jobs', 'workable_api_listjobs' );
