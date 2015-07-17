<?php
/**
 * Plugin Name: OG Survey
 * Plugin URI: http://www.ogmaconceptions.com/
 * Description: A simple WooCommerce extension which creates a Survey Management and as a result after the survey, some WooCommerce products or any other contents via shortcode or one or more YouTube videos would be recommended. WooCommerce plugin must be activated to work with this plugin.
 * Version: 1.0
 * Author: Team OGMA
 * Author URI: http://www.ogmaconceptions.com/
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  require_once 'includes/og-survey-scripts-styles.php';
  require_once 'includes/og-survey-post-types.php';
  require_once 'includes/og-survey-settings.php';
  require_once 'includes/og-survey-shortcodes.php';
  require_once 'includes/og-survey-ajax.php';
  
  function og_survey_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=og-survey-settings">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }

  $plugin = plugin_basename(__FILE__);
  add_filter( "plugin_action_links_$plugin", 'og_survey_add_settings_link' );
  
} else {
  wp_die( __( 'OG Survey requires WooCommerce to be activated!', 'og-survey' ) );
}