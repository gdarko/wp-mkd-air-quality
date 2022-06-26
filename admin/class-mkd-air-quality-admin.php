<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://darkog.com
 * @since      1.0.0
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the admin views and enqueues the admin-specific stylesheet and JavaScript.
 *
 * @package    MKD_Air_Quality
 * @subpackage MKD_Air_Quality/admin
 * @author     Darko Gjorgjijoski <dg@darkog.com>
 */
class MKD_Air_Quality_Admin {

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
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The settings instance
	 * @since    1.0.0
	 * @var MKD_Air_Quality_Settings
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of this plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->settings = new MKD_Air_Quality_Settings();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/script.js',
			array( 'jquery' ), $this->version, false );

	}

	/**
	 * Render the admin menu options
	 */
	public function register_options() {
		$page_title = __( 'AIR Quality (MK)', 'wp-mkd-air-quality' );
		add_options_page( $page_title, $page_title, 'manage_options', 'mkd-air-quality',
			array( $this, 'render_options' ) );
	}

	/**
	 * Render the plugin options
	 */
	public function render_options() {

		$message = '';

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if ( current_user_can( 'manage_options' ) ) {
				if ( isset( $_POST['mkdaiq']['language'] ) ) {
					$this->settings->set( 'language', _sanitize_text_fields( $_POST['mkdaiq']['language'] ) );
				}
				$this->settings->save();
				$message = __( 'Settings saved successfully', 'wp-mkd-air-quality' );

				if ( isset( $_POST['purge_cache'] ) ) {
					MKDAQAPI::purge_cache();
					$message = __( 'Cache purged successfully', 'wp-mkd-air-quality' );
				}
			}
		}

		$language = $this->settings->get( 'language' );

		?>
		<div class="wrap mkdaiq-admin">
			<?php if ( ! empty( $message ) ): ?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo $message; ?></p>
				</div>
			<?php endif; ?>
			<h2 style="margin-bottom: 15px;"><?php _e( 'Settings', 'wp-mkd-air-quality' ); ?></h2>
			<form method="post" action="options-general.php?page=mkd-air-quality">
				<table>
					<tr>
						<th scope="row"><label for="language"><?php _e( 'Language', 'wp-mkd-air-quality' ); ?></label>
						</th>
						<td>
							<select id="language" name="mkdaiq[language]">
								<option value="default" <?php selected( 'default', $language ); ?>><?php _e( 'Default',
										'wp-mkd-air-quality' ); ?></option>
								<option value="mk_MK" <?php selected( 'mk_MK', $language ); ?>><?php _e( 'Македонски',
										'wp-mkd-air-quality' ); ?></option>
							</select>
						</td>
					</tr>
				</table>
				<hr/>
				<p>
					<?php submit_button( __( 'Save settings', 'wp-mkd-air-quality' ), 'primary', 'submit',
						'' ); ?>&nbsp;<?php submit_button( __( 'Purge cache', 'wp-mkd-air-quality' ), 'secondary',
						'purge_cache', '', array( 'class' => 'button' ) ); ?>
				</p>
			</form>
		</div>
		<?php
	}

}
