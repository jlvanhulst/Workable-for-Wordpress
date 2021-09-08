<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.kanopistudios.com
 * @since      1.0.0
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/inc
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Workable_Api
 * @subpackage Workable_Api/admin
 * @author     Katherine White <katherine@kanopistudios.com>
 */
class Workable_Api_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-workable-api-wrapper.php';
		$this->workable = new Workable_Api_Wrapper( $this->plugin_name, $this->version );

	}

	/**
	 * Register settings page
	 * Create an admin page for managing API options.
	 *
	 * @return void
	 */
	public function workable_api_settings_init() {
		// register a new setting for the workable list api configuration.
		register_setting( 'workable_api', 'workable_api_options' );

		// register a new section in the page.
		add_settings_section(
			'workable_api_section_creds',
			__( 'API Settings', 'workable-api' ),
			array( $this, 'workable_api_section_creds_cb' ),
			'workable_api'
		);

		// register a new field.
		add_settings_field(
			'field_workable_subdomain',
			__( 'Workable Subdomain', 'workable-api' ),
			array( $this, 'workable_subdomain_cb' ),
			'workable_api',
			'workable_api_section_creds',
			array(
				'label_for' => 'field_workable_subdomain',
			)
		);

		add_settings_field(
			'field_api_key',
			__( 'Access Token', 'workable-api' ),
			array( $this, 'workable_api_key_cb' ),
			'workable_api',
			'workable_api_section_creds',
			array(
				'label_for' => 'field_api_key',
			)
		);

		// settings section for job display.
		add_settings_section(
			'workable_api_section_jobs',
			__( 'Current Published Listings', 'workable-api' ),
			array( $this, 'workable_api_section_jobs_cb' ),
			'workable_api'
		);

		// select the jobs to be featured on the site.
		add_settings_field(
			'field_featured_jobs',
			__( 'List of Shortcodes to Display', 'workable-api' ),
			array( $this, 'workable_featured_jobs_cb' ),
			'workable_api',
			'workable_api_section_jobs',
			array(
				'label_for' => 'field_featured_jobs',
			)
		);
	}


	/**
	 * Callback function: credentials section
	 *
	 * Adding introductory content to the credentials management section of
	 * the settings page.
	 *
	 * @param object $args The arguments object to print out on the admin side.
	 *
	 * @return void
	 */
	public function workable_api_section_creds_cb( $args ) {
		$options = get_option( 'workable_api_options' );
		?>

		<div id="<?php echo esc_attr( $args['id'] ); ?>" style="max-width:700px;">
			<p>Please enter your Access Token below.</p>
			<p>Not sure where to find this information? Check out the <a href="https://help.workable.com/hc/en-us/articles/115015785428-How-do-I-generate-an-API-key-access-token-Pro-" target="_blank">Workable API help documentation.</a></p>
		</div>
		<?php
	}


	/**
	 * Callback function: API Key field
	 *
	 * HTML output for the API Key field.
	 *
	 * @param object $args object The arguments object to print out on the admin side.
	 *
	 * @return void
	 */
	public function workable_api_key_cb( $args ) {
		// get the value of the setting we've registered with register_setting().
		$options = get_option( 'workable_api_options' );
		// output the field.
		$value = '';

		if ( $options && array_key_exists( $args['label_for'], $options ) ) {
			$value = $options[ $args['label_for'] ];
		}
		?>
		<input type="text" size="80" id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="workable_api_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $value ); ?>"
		>
		<?php
	}

	/**
	 * Callback function: Workable Subdomain field
	 * HTML output for the subdomain field.
	 *
	 * @param object $args The arguments object to print out.
	 *
	 * @return void
	 */
	public function workable_subdomain_cb( $args ) {
		// get the value of the setting we've registered with register_setting().
		$options = get_option( 'workable_api_options' );
		$value   = '';

		if ( $options && array_key_exists( $args['label_for'], $options ) ) {
			$value = $options[ $args['label_for'] ];
		}
		// output the field.
		?>
		<input type="text" size="20" id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="workable_api_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $value ); ?>"
		>.workable.com
		<?php
	}


	/**
	 * Callback function: Display job field
	 * HTML output for the job field.
	 *
	 * @param object $args The arguments object to print out.
	 *
	 * @return void
	 */
	public function workable_featured_jobs_cb( $args ) {
		// get the value of the setting we've registered with register_setting().
		$options = get_option( 'workable_api_options' );
		// output the field.
		$value = '';

		if ( $options && array_key_exists( $args['label_for'], $options ) ) {
			$value = $options[ $args['label_for'] ];
		}

		?>
		<input type="text" size="80" id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="workable_api_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="
											<?php
												echo esc_attr( $value );
											?>
		"
		>
		<?php
	}

	/**
	 * Callback function: Job Listing Section
	 *
	 * @param object $args The arguments needed to get all the object info.
	 *
	 * @return void
	 */
	public function workable_api_section_jobs_cb( $args ) {
		$options = get_option( 'workable_api_options' );

		if ( empty( $options['field_api_key'] ) || empty( $options['field_workable_subdomain'] ) ) :
			?>
			<p><?php echo esc_html( 'Enter your Access Token and URL to get started.', 'workable-api' ); ?></p>
			<?php
			return;
		endif;
		$jobs           = $this->workable->get_jobs( array( 'state' => 'published' ) );
		$displayed_jobs = explode( ',', $options['field_featured_jobs'] );
		if ( $jobs ) :
			$hidden_jobs     = array_diff( wp_list_pluck( $jobs->jobs, 'shortcode' ), $displayed_jobs );
			$shortcode_order = array_merge( $displayed_jobs, $hidden_jobs );
		endif;
		if ( count( $jobs->jobs ) > 0 ) :
			usort(
				$jobs->jobs,
				function( $a, $b ) use ( $shortcode_order ) {
					return array_search( $a->shortcode, $shortcode_order, true ) - array_search( $b->shortcode, $shortcode_order, true );
				}
			);
		endif;

		?>
	<div id="workable_job_listings">
	<p>Note: Job postings highlighted in <span class="active_job">green</span> are live on the website. Live postings are listed in the order they will appear. To reorder, drag and drop any active listing. To enable a new listing, check off the "live on site" checkbox in required row. </p>
		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th>Shortcode</th>
					<th>Title</th>
					<th>Region</th>
					<th>Country</th>
					<th>Show on Site</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( $jobs->jobs ) :
					foreach ( $jobs->jobs as $job ) :
						?>
						<?php
						$location = '';
						$region   = '--';
						if ( $job->location->region ) {
							$region = $job->location->city . ', ' . $job->location->region_code;
						}
						?>
				<tr 
						<?php
						if ( in_array( $job->shortcode, $displayed_jobs, true ) ) {
							echo 'class="live"';
						} else {
							echo 'class="not_sortable"';
						}
						?>

				data-shortcode="<?php echo esc_html( $job->shortcode ); ?>">
					<td><code><?php echo esc_html( $job->shortcode ); ?></code></td>
					<td><?php echo esc_html( $job->title ); ?></td>
					<td><?php echo esc_html( $region ); ?></td>
					<td><?php echo esc_html( $job->location->country ); ?></td>
					<td><input type="checkbox" class="show_on_site" id="show_live_<?php echo esc_html( $job->shortcode ); ?>" 
						<?php
						if ( in_array( $job->shortcode, $displayed_jobs, true ) ) {
							echo 'checked';
						}
						?>

					data-shortcode="<?php echo esc_html( $job->shortcode ); ?>"> <button class="see_job_description" data-shortcode="<?php echo esc_html( $job->shortcode ); ?>">See Job Description</button></td>
				</tr>
						<?php
				endforeach;
				endif;
				?>
			</tbody>
		</table>

		<h2>To Use</h2>

		<p>A basic shortcode that will output the short title, department, and job link has been included. To use it, insert the shortcode <code>[workable_job_listing]</code> wherever you want the job listing output.</p>
		<p>If you wish to create your own shortcode, or use it within a block, in your template, call the <code>workable_api_get_featured_jobs()</code> PHP function to get an array of the jobs you have selected to be featured on your site.</p>

		<div id="job_description_modal" class="modal fade">
			<div class="modal-content">
				<span class="dashicons dashicons-no-alt" id="close_modal"></span>
				<div class="inner"></div>
			</div>		
		</div>
	</div>
		<?php
	}


	/**
	 * Menu item for settings page
	 * Add a menu item under the default Settings area to access the
	 * settings page.
	 *
	 * @return void
	 */
	public function workable_api_options_page() {
		// add top level menu page.
		add_options_page(
			'Workable API Settings',
			'Workable API',
			'manage_options',
			'workable_api',
			array( $this, 'workable_api_page_html' )
		);
	}


	/**
	 * Callback: Admin Page HTML
	 * Output the HTML wrapper for the API settings page.
	 *
	 * @return null
	 */
	public function workable_api_page_html() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// show error/update messages.
		settings_errors( 'workable_api_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form action="options.php" method="post" id="save_workable_options" >
				<?php
				// output security fields for the registered setting.
				settings_fields( 'workable_api' );

				// output setting sections and their fields.
				do_settings_sections( 'workable_api' );
				// output save settings button.
				submit_button( 'Save Settings' );
				?>
			</form>

		</div>
		<?php
	}
}
/**
 * Function to enqueue the admin scripts needed.
 *
 * @return void
 */
function add_jquery_ui() {
	wp_enqueue_script( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array( 'jquery' ), time(), false );
	wp_enqueue_style( 'wp-workable-styles', WP_PLUGIN_URL . '/wp-workable/inc/css/style.css', array(), time() );
	wp_enqueue_script( 'wp-workable-scripts', WP_PLUGIN_URL . '/wp-workable/inc/js/functionality.js', array( 'jquery' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'add_jquery_ui' );

/**
 * Function to add admin specific things.
 *
 * @return void
 */
function workable_api_admin_scripts() {
	if ( is_admin() ) { // for Admin Dashboard Only.
		// Embed the Script on our Plugin's Option Page Only.
		if ( isset( $_GET['page'] ) && 'workable_api' === $_GET['page'] ) {
			wp_enqueue_script( 'jquery-form' );
		}
	}
}
add_action( 'admin_init', 'workable_api_admin_scripts' );


function get_specific_job_description() {
	$shortcode = $_POST['shortcode'];

	$plugin          = new Workable_Api();
	$workable        = new Workable_Api_Wrapper( $plugin->get_plugin_name(), PLUGIN_NAME_VERSION );
	$job_description = $workable->get_job( array( 'shortcode' => $shortcode ) );
	echo json_encode( $job_description->full_description );
	wp_die();
}
add_action( 'wp_ajax_get_specific_job_description', 'get_specific_job_description' );
add_action( 'wp_ajax_nopriv_get_specific_job_description', 'get_specific_job_description' );

