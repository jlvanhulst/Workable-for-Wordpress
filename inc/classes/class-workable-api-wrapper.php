<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.kanopistudios.com
 * @since      1.0.0
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/inc
 */

/**
 * The wrapper around the core Workable API.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/inc
 * @author     Katherine White <katherine@kanopistudios.com>
 */
class Workable_Api_Wrapper {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Workable API key for the application.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $api_key    The current application API key.
	 */
	private $api_key;

	/**
	 * The Workable API path for the application.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var      string    $api_path   The current application API path.
	 */
	private $api_path;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$options = get_option( 'workable_api_options' );

		$this->plugin_name       = $plugin_name;
		$this->version           = $version;
		$this->api_key           = $options['field_api_key'];
		$this->featured_listings = $options['field_featured_jobs'];
		$this->api_path          = 'https://' . $options['field_workable_subdomain'] . '.workable.com/spi/v3/';
	}

	/**
	 * Make a GET request to the Workable API.
	 *
	 * @param  string $endpoint the API endpoint we are connecting to.
	 * @param  array  $params   query parameters for the request.
	 * @return object           response object or request error.
	 */
	protected function get_request( $endpoint, $params = array() ) {

		// generate the preliminary query string.
		$query = http_build_query( $params );
		$path  = $this->api_path . $endpoint . '?' . $query;

		$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL            => $path,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'GET',
				CURLOPT_HTTPHEADER     => array(
					'Cache-Control: no-cache',
					'Authorization:Bearer ' . $this->api_key,
				),
			)
		);

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			return 'cURL Error #:' . $err;
		} else {
			return json_decode( $response );
		}
	}


    /**
     * Retrieve all job listings from Workable.
     *
     * @param  array $params query parameters for the request.
     *
     * The transient string takes parameters into account.
     *
     * @return object         transient value or response object.
     */

    public function get_jobs( $params = array() ) {
        if ( false === ( $jobs = get_transient( 'workable_all_jobs'.implode(',',$params) ) ) ) {
            $jobs = $this->get_request( 'jobs', $params );
            set_transient( 'workable_all_jobs'.implode(',',$params), $jobs, 2 * HOUR_IN_SECONDS );
        }

        return $jobs;
    }


    /**
	 * Get a specific job from Workable.
	 *
	 * @param array $params query parameters for the request.
	 * @return object The job object.
	 */
	public function get_job( $params = array() ) {
		$job = $this->get_request( 'jobs/' . $params['shortcode'], $params );

		return $job;
	}

	/**
	 * Get featured jobs from Workable.
	 *
	 * @param array $params query parameters for the request.
	 * @return object The featured jobs object.
	 */
	public function get_featured_jobs( $params = array() ) {

		if ( false === $listings = $this->featured_listings ) {
			return false;
		}

		$featured_listings = explode( ',', $listings );
		$results           = $this->get_jobs( array( 'state' => 'published' ) );
		$all_listings      = array();
		$listings          = array();

		foreach ( $results->jobs as $job ) {
			// convert this to an array so we can more easily grab a specific job.
			$all_listings[ $job->shortcode ] = $job;
		}

		foreach ( $featured_listings as $job ) {
			$shortcode = trim( $job );
			if ( array_key_exists( $shortcode, $all_listings ) ) {
				$listing_data = $all_listings[ $shortcode ];
				$listings[]   = $listing_data;
			}
		}

		return $listings;
	}


}
