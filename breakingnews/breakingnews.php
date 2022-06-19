<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Breaking News
 *
 * @package     PluginPackage
 * @author   	Arqam Saleem   
 * @copyright   2021
 * @license     GPL-3.0
 *
 * @wordpress-plugin
 * Plugin Name: Breaking News
 * Plugin URI:  https://arqamsaleem.wordpress.com/
 * Description: Provide ability to mark a post as breaking news and display it on the frontend. User can select breaking news to expire after a specific time.
 * Version:     1.0.0
 * Author: Arqam Saleem
 * Author URI:  https://arqamsaleem.wordpress.com/
 * Text Domain: breakingnews 
 * License:     GPL-3.0+
 */

define( 'BN_VERSION',		'1.0.0' );
define( 'BN_PLUGIN_DIR',	plugin_dir_path( __FILE__ ) );
define( 'BN_PLUGIN_FILE',	__FILE__ );

/****Link style and script files****/
add_action( 'admin_enqueue_scripts', 'bn_add_scripts' );
function bn_add_scripts($hook) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
        
        wp_enqueue_style( 'datetimepicker-css', plugins_url( 'js/datetimepicker-master/jquery.datetimepicker.css', __FILE__ ));
        wp_enqueue_script( 'datetme-picker', plugins_url( 'js/datetimepicker-master/build/jquery.datetimepicker.full.min.js', __FILE__ ), array(), false, true ); 
        
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}

/******Creating Admin menu here*******/
add_action( 'admin_menu', 'built_admin_menu', 2 );
function built_admin_menu() {
	add_menu_page( 'Breaking News', 'Breaking News', 'manage_options', 'bn_settings', 'bn_setting_page' );
}

function bn_setting_page() {
  load_template( BN_PLUGIN_DIR . 'views/plugin_page.php' );
}


/**
 * Register meta boxes.
 */
function breaking_news_meta_boxes() {
    add_meta_box( 'bn-selection', __( 'Breaking News Selection', 'breakingnews' ), 'bn_display_callback', 'post' );
}
add_action( 'add_meta_boxes', 'breaking_news_meta_boxes' );

/*** Meta box display callback.*/
function bn_display_callback( $post ) {
    
    load_template( BN_PLUGIN_DIR . 'views/breaking_news_selection_meta_box.php' );
}


/*** Save meta box content. */
add_action( 'save_post', 'breaking_news_save_meta_box' );
function breaking_news_save_meta_box( $post_id ) {
  
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
      $post_id = $parent_id;
    }

    $fields = [
        'bn_select_breaking_news',
        'bn_custom_title',
        'bn_select_expiry',
        'bn_select_datetime',
    ];

    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
     
    $bn_select_breaking_news = strtolower( $_POST[ 'bn_select_breaking_news' ] );

  	if ( isset( $_POST[ 'bn_select_breaking_news' ] ) and $bn_select_breaking_news == 'yes' ){
  		$setting_result = update_post_meta( $post_id, 'bn_select_breaking_news', $_POST['bn_select_breaking_news'] );
  	} else{
  		$setting_result = update_post_meta( $post_id, 'bn_select_breaking_news', 'no' );
  	}

  	$bn_select_expiry = strtolower( $_POST[ 'bn_select_expiry' ] );

  	if ( isset( $_POST[ 'bn_select_expiry' ] ) and $bn_select_expiry == 'yes' ){
  		$setting_result = update_post_meta( $post_id, 'bn_select_expiry', $_POST['bn_select_expiry'] );
  	} else{
  		$setting_result = update_post_meta( $post_id, 'bn_select_expiry', 'no' );
  	}
}

/*****Expiring Breaking News if selected date and time to expire********/
add_action( 'init', 'expire_breaking_news' );
function expire_breaking_news(){
  
  // Set timezone on the base of wordpress settings
  date_default_timezone_set(wp_timezone_string());
  
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation'    => 'AND',
      array('key'   => 'bn_select_breaking_news',
              'value'     => 'yes',
      ),
      array('key'   => 'bn_select_expiry',
              'value'     => 'yes',
      )
    )
  );

  $query = new WP_Query( $args );
  
    while ( $query->have_posts() ) : $query->the_post();
      
      $expiry_datetime = get_post_meta( get_the_ID(), 'bn_select_datetime', true ) ;
      $expiry_datetime_formatted = new DateTime( $expiry_datetime );
      
      $current_datetime = new DateTime();

      if ( $current_datetime >= $expiry_datetime_formatted ) {

        update_post_meta( get_the_ID(), 'bn_select_breaking_news', 'no' );
        update_post_meta( get_the_ID(), 'bn_select_expiry', 'no' );

      }
      
    endwhile;
}
add_shortcode( 'display_beaking_news', 'create_breaking_news_shortcode' );
function create_breaking_news_shortcode(){

  load_template( BN_PLUGIN_DIR . 'views/breaking_news_display.php' );

}