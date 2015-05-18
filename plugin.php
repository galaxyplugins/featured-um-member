<?php
/**
 * WordPress Widget Boilerplate
 *
 * The WordPress Widget Boilerplate is an organized, maintainable boilerplate for building widgets using WordPress best practices.
 *
 * @package   Widget_Name
 * @author    Joseph Lawrence
 * @license   GPL-2.0+
 * @link      http://galaxyplugins.co
 * @copyright 2015 Galaxy Plugins
 *
 * @wordpress-plugin
 * Plugin Name:       Ultimate Member Featured Member Widget
 * Plugin URI:        http://galaxyplugins.co
 * Description:       Feature an Ultimate Member user in a sidebar widget
 * Version:           1.0.0
 * Author:            Joseph C Lawrence
 * Author URI:        http://galaxyplugins.co
 * Text Domain:       featured-um-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

class Featured_UM extends WP_Widget {

    protected $widget_slug = 'feautured-um-widget';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );

		// TODO: update description
		parent::__construct(
			$this->get_widget_slug(),
			__( 'Featured Ultimate Member Widget', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Feature an Ultimate Member user in a sidebar widget.', $this->get_widget_slug() )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];
		
		// go on with your widget logic, put everything into a string and …


		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		ob_start();
		
		$widget_title = empty($instance['um_widget_title']) ? '' : apply_filters('um_widget_title', $instance['um_widget_title']);
		$um_member = empty($instance['um_member']) ? '' : apply_filters('um_member', $instance['um_member']);
		$um_widget_layout = empty($instance['um_widget_layout']) ? '' : apply_filters('um_widget_layout', $instance['um_widget_layout']);
		
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	} // end widget
	
	
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
		$instance['um_widget_title'] = strip_tags(stripslashes($new_instance['um_widget_title']));
		$instance['um_member'] = strip_tags(stripslashes($new_instance['um_member']));
		$instance['um_widget_layout'] = strip_tags(stripslashes($new_instance['um_widget_layout']));

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args(
        	(array)$instance,
			array(
				'um_widget_title' => '',
				'um_member' => '',
            	'um_widget_layout' => ''
			)
		);
		
		$widget_title = strip_tags(stripslashes($new_instance['um_widget_title']));
		$um_member = strip_tags(stripslashes($new_instance['um_member']));
		$um_widget_layout = strip_tags(stripslashes($new_instance['um_widget_layout']));
		
		$site_users = get_users( array( 
			'fields' => array( 
				'display_name',
				'ID',
				'user_nicename',
				'user_login' 
			) )
		);
		
		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain


	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-input-styles', plugins_url( 'css/input.min.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-search-styles', plugins_url( 'css/search.min.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-dropdown-styles', plugins_url( 'css/dropdown.min.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-loading-styles', plugins_url( 'css/loader.min.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-transition-styles', plugins_url( 'css/transition.min.css', __FILE__ ) );
		
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
				
		wp_enqueue_script( $this->get_widget_slug().'-dropdown-script', plugins_url( 'js/dropdown.min.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( $this->get_widget_slug().'-search-script', plugins_url( 'js/search.min.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( $this->get_widget_slug().'-transition-script', plugins_url( 'js/transition.min.js', __FILE__ ), array('jquery') );
		

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );

	} // end register_widget_styles


} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("Featured_UM");' ) );
