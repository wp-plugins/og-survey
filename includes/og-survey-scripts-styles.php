<?php

/**
 * Admin Scripts & Styles
 */
if( ! function_exists( 'og_survey_admin_scripts_styles' ) ) {
  
  add_action( 'admin_enqueue_scripts', 'og_survey_admin_scripts_styles' );

  function og_survey_admin_scripts_styles() {
    $screen = get_current_screen();
    wp_enqueue_style( 'og-survey-admin-style', plugins_url( 'css/admin-style.css', dirname(__FILE__) ) );
    if( in_array( $screen->id, array( 'question' ) ) ) {
      wp_enqueue_script( 'question-add-edit-customization-js', plugins_url( 'js/question.add.edit.customization.js' , dirname(__FILE__) ), array( 'jquery' ) );
    }
    if( in_array( $screen->id, array( 'sub-question' ) ) ) {
      wp_enqueue_script( 'sub-question-add-edit-customization-js', plugins_url( 'js/sub.question.add.edit.customization.js' , dirname(__FILE__) ), array( 'jquery' ) );
    }
    if( in_array( $screen->id, array( 'toplevel_page_og-survey-settings' ) ) ) {
      wp_enqueue_script( 'settings-js', plugins_url( 'js/settings.js' , dirname(__FILE__) ), array( 'jquery' ) );
      wp_enqueue_style( 'og-survey-settings-style', plugins_url( 'css/settings.css', dirname(__FILE__) ) );
    }
  }
}

/**
 * Front End
 * Adding Style from Settings Page on WP_Head on the Content Page
 */
add_action( 'wp_head', 'og_survey_load_custom_styles' );
  
function og_survey_load_custom_styles() {
  $options = get_option( 'og_survey_settings' );
  $options_shortcode = get_option( 'og_survey_settings_shortcode' );
  if( is_page( $options_shortcode['survey_shortcode_page'] ) && !empty( $options['extra_css'] ) ) {
    echo '<style type="text/css">' . $options['extra_css'] . '</style>';
  }
  if( is_page( $options_shortcode['survey_shortcode_page'] ) && !empty( $options['extra_js'] ) ) {
    echo '<script type="text/javascript">' . $options['extra_js'] . '</script>';
  }
  if( is_page( $options['resultant_page'] ) && !empty( $options['extra_resultant_css'] ) ) {
    echo '<style type="text/css">' . $options['extra_resultant_css'] . '</style>';
  }
  if( is_page( $options['resultant_page'] ) && !empty( $options['extra_resultant_js'] ) ) {
    echo '<script type="text/javascript">' . $options['extra_resultant_js'] . '</script>';
  }
}

/**
 * Front End Scripts & Styles
 */
add_action( 'wp_enqueue_scripts', 'og_survey_special_scripts_styles_inclution' );

function og_survey_special_scripts_styles_inclution() {
  global $post;
  $options = get_option( 'og_survey_settings' );
  $options_shortcode = get_option( 'og_survey_settings_shortcode' );

  // Script on Content Page
  if( is_page( $options_shortcode['survey_shortcode_page'] ) ) {
    // webshim library for html5 features on non-html5 feature compatible browsers
    wp_enqueue_script( 'webshim', plugins_url( 'lib/webshim/polyfiller.js', dirname(__FILE__) ), array( 'jquery' ), '1.15.7' );
    wp_enqueue_script( 'webshim-options-js', plugins_url( 'js/webshim.options.js', dirname(__FILE__) ), array( 'jquery', 'webshim' ) );
    
    wp_enqueue_style( 'content-page', plugins_url( 'css/content.page.css', dirname(__FILE__) ) );
    wp_enqueue_script( 'content-page-js', plugins_url( 'js/content.page.js', dirname(__FILE__) ), array( 'jquery' ) );
    wp_localize_script('content-page-js', 'valueObject', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'loaderPath' => plugins_url( 'images/ajax-loader.gif', dirname(__FILE__) ) ) );
  }
  if( is_page( $options['resultant_page'] ) ) {
    wp_enqueue_style( 'resultant-page', plugins_url( 'css/resultant.page.css', dirname(__FILE__) ) );
  }
}