<?php

/**
 * OG Survey Settings Fields
 */
add_action( 'admin_menu', 'og_survey_add_admin_menu' );
add_action( 'admin_init', 'og_survey_settings_init' );

function og_survey_add_admin_menu() {
  add_menu_page( 'OG Survey Settings', 'OG Survey Settings', 'manage_options', 'og-survey-settings', 'og_survey_options_page' );
}

function og_survey_settings_init() {
  register_setting( 'pluginPageMain', 'og_survey_settings' );
  register_setting( 'pluginPageShortcode', 'og_survey_settings_shortcode' );
  
  add_settings_section( 'og_survey_pluginPageMain_section', __( 'Choose how the Survey Questions and Answers would Display on the Front End of the website', 'og-survey' ), 'og_survey_settings_section_main_callback', 'pluginPageMain' );
  add_settings_section( 'og_survey_pluginPageShortcode_section', __( 'Generate Shortcodes for Multiple Survey and Add Them to Pages', 'og-survey' ), 'og_survey_settings_section_shortcode_callback', 'pluginPageShortcode' );
  
  // Setting Fields of Main Tab Section
  add_settings_field( 'og_survey_resultant_page', __( 'Resultant Page', 'og-survey' ), 'og_survey_resultant_page_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_resultant_page_shortcode', __( 'Resultant Page Shortcode', 'og-survey' ), 'og_survey_resultant_page_shortcode_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_extra_css', __( 'Extra Content Page CSS', 'og-survey' ), 'og_survey_extra_css_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_extra_js', __( 'Extra Content Page Scripts', 'og-survey' ), 'og_survey_extra_js_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_result_iframe_height', __( 'YouTube Video iFrame Height', 'og-survey' ), 'og_survey_result_iframe_height_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_result_extra_css', __( 'Extra Resultant Page CSS', 'og-survey' ), 'og_survey_result_extra_css_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_result_extra_js', __( 'Extra Resultant Page Scripts', 'og-survey' ), 'og_survey_result_extra_js_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  add_settings_field( 'og_survey_terms_conditions_page', __( 'Terms & Conditions Page', 'og-survey' ), 'og_survey_terms_conditions_page_render', 'pluginPageMain', 'og_survey_pluginPageMain_section' );
  
  // Setting Fields of Shortcode Tab Section
  add_settings_field( 'og_survey_multiple_survey_shortcode_generate', __( 'Survey Shortcodes', 'og-survey' ), 'og_survey_multiple_survey_shortcode_generate_render', 'pluginPageShortcode', 'og_survey_pluginPageShortcode_section' );
}

function og_survey_resultant_page_render() {
  $options = get_option( 'og_survey_settings' );
  $pages = get_pages(); ?>
  <select name="og_survey_settings[resultant_page]" id="resultantPage">
    <option value="">Select</option>
    <?php foreach( $pages as $page ) : ?>
      <option value="<?php echo $page->post_title; ?>" <?php if( $page->post_title == $options['resultant_page'] ) echo 'selected'; ?>><?php echo $page->post_title; ?></option>
    <?php endforeach; ?>
  </select>
  <p class="description">Select a Page from the above list, or Add a Page by <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>">Clicking Here</a>, where the Resultant Products would display.</p>
  <?php
}

function og_survey_resultant_page_shortcode_render() { ?>
  <input type="text" id="contentShortcode" value="[og-survey-result]" onfocus="this.select();" readonly />
  <p class="description">Now Copy the above Shortcode and Paste it to the Resultant Page selected above.</p>
  <?php
}

function og_survey_extra_css_render() {
  $options = get_option( 'og_survey_settings' ); ?>
  <textarea name="og_survey_settings[extra_css]" class="large-text code" rows="15"><?php echo $options['extra_css']; ?></textarea>
  <p class="description">This CSS should be applied for the Survey Pages Only.</p>
  <?php
}

function og_survey_extra_js_render() {
  $options = get_option( 'og_survey_settings' ); ?>
  <textarea name="og_survey_settings[extra_js]" class="large-text code" rows="15"><?php echo $options['extra_js']; ?></textarea>
  <p class="description">This Scripts should be applied for the Survey Pages Only.</p>
  <?php
}

function og_survey_result_iframe_height_render() {
  $options = get_option( 'og_survey_settings' ); ?>
  <input type="number" name="og_survey_settings[iframe_height]" value="<?php echo $options['iframe_height']; ?>" />
  <p class="description">Set the Height of the YouTube iFrame, if there is any YouTube Video on the Resultant Page.</p>
  <?php
}

function og_survey_result_extra_css_render() {
  $options = get_option( 'og_survey_settings' ); ?>
  <textarea name="og_survey_settings[extra_resultant_css]" class="large-text code" rows="15"><?php echo $options['extra_resultant_css']; ?></textarea>
  <p class="description">This CSS should be applied for the Resultant Page Only.</p>
  <?php
}

function og_survey_result_extra_js_render() {
  $options = get_option( 'og_survey_settings' ); ?>
  <textarea name="og_survey_settings[extra_resultant_js]" class="large-text code" rows="15"><?php echo $options['extra_resultant_js']; ?></textarea>
  <p class="description">This Scripts should be applied for the Resultant Page Only.</p>
  <?php
}

function og_survey_terms_conditions_page_render() {
  $options = get_option( 'og_survey_settings' );
  $pages = get_pages(); ?>
  <select name="og_survey_settings[terms_conditions_page]" id="termsConditionsPage">
    <option value="">Select</option>
    <?php foreach( $pages as $page ) : ?>
      <option value="<?php echo $page->post_title; ?>" <?php if( $page->post_title == $options['terms_conditions_page'] ) echo 'selected'; ?>><?php echo $page->post_title; ?></option>
    <?php endforeach; ?>
  </select>
  <p class="description">Select a Page from the above list, or Add a Page by <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>">Clicking Here</a>, which will be linked to Terms & Conditions on the Survey page(s).</p>
  <?php
}

function og_survey_multiple_survey_shortcode_generate_render() {
  $shortcodes = get_option( 'og_survey_settings_shortcode' );
  if( !empty( $shortcodes ) && is_array( $shortcodes ) ) {
    foreach( $shortcodes as $key => $all ) {
      foreach( $all as $i => $val ) {
        $modified_shortcodes_array[$i][$key] = $val;
      }
    }
  }
  $all_questions = get_posts( array( 'post_type' => 'question', 'post_status' => 'publish', 'posts_per_page' => 5000 ) );
  $pages = get_pages(); ?>
  <ul id="og-survey-multiple-survey" class="widefat">
    <?php if( !empty( $modified_shortcodes_array ) ) : foreach( $modified_shortcodes_array as $key => $survey ) : ?>
      <li>
        <input type="hidden" value="<?php echo $key; ?>" />
        <?php foreach( $all_questions as $question ) : ?>
          <fieldset>
            <legend class="screen-reader-text">Survey Shortcodes</legend>
            <label for="survey_shortcode_questions">
              <input type="checkbox" class="og-survey-shortcode-question-checkbox" name="og_survey_settings_shortcode[survey_shortcode_questions][<?php echo $key; ?>][]" value="<?php echo $question->ID; ?>" <?php if( is_array( $survey['survey_shortcode_questions'] ) && in_array( $question->ID, $survey['survey_shortcode_questions'] ) ) echo 'checked'; ?> /> <?php echo $question->post_title; ?>
            </label>
          </fieldset>
        <?php endforeach; ?>
        <fieldset>
          <legend class="screen-reader-text">Survey Shortcodes</legend>
          <label>
            <select name="og_survey_settings_shortcode[survey_shortcode_page][]">
              <option value="">Select</option>
              <?php foreach( $pages as $page ) : ?>
                <option value="<?php echo $page->post_title; ?>" <?php if( $page->post_title == $survey['survey_shortcode_page'] ) echo 'selected'; ?>><?php echo $page->post_title; ?></option>
              <?php endforeach; ?>
            </select>
            <p class="description">Select a Page from the above list( or <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>">Create a Page</a> ) where you want to insert the Shortcode.</p>
          </label>
        </fieldset>
        <fieldset>
          <legend class="screen-reader-text">Survey Shortcodes</legend>
          <label>
            <input type="text" class="survey_shortcode" name="og_survey_settings_shortcode[survey_shortcode][]" value='<?php echo $survey['survey_shortcode']; ?>' onfocus="this.select();" placeholder="Shortcode" readonly />
            <p class="description">Shortcode would be generated after checking one or more Questions. Go to the above selected Page and insert the Shortcode there.</p>
          </label>
        </fieldset>
        <button class="button-secondary survey-delete"> - </button>
        <?php if( (int)$key + 1 == count( $modified_shortcodes_array ) ) : ?>
          <button class="button-primary" id="survey-add"> + </button>
        <?php endif; ?>
      </li>
    <?php endforeach; else : ?>
      <li>
        <input type="hidden" value="0" />
        <?php foreach( $all_questions as $question ) : ?>
          <fieldset>
            <legend class="screen-reader-text">Survey Shortcodes</legend>
            <label for="survey_shortcode_questions">
              <input type="checkbox" class="og-survey-shortcode-question-checkbox" name="og_survey_settings_shortcode[survey_shortcode_questions][0][]" value="<?php echo $question->ID; ?>" /> <?php echo $question->post_title; ?>
            </label>
          </fieldset>
        <?php endforeach; ?>
        <fieldset>
          <legend class="screen-reader-text">Survey Shortcodes</legend>
          <label>
            <select name="og_survey_settings_shortcode[survey_shortcode_page][]">
              <option value="">Select</option>
              <?php foreach( $pages as $page ) : ?>
                <option value="<?php echo $page->post_title; ?>"><?php echo $page->post_title; ?></option>
              <?php endforeach; ?>
            </select>
            <p class="description">Select a Page from the above list( or <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>">Create a Page</a> ) where you want to insert the Shortcode.</p>
          </label>
        </fieldset>
        <fieldset>
          <legend class="screen-reader-text">Survey Shortcodes</legend>
          <label>
            <input type="text" class="survey_shortcode" name="og_survey_settings_shortcode[survey_shortcode][]" onfocus="this.select();" placeholder="Shortcode" readonly />
            <p class="description">Shortcode would be generated after checking one or more Questions. Go to the above selected Page and insert the Shortcode there.</p>
          </label>
        </fieldset>
        <button class="button-secondary survey-delete"> - </button>
        <button class="button-primary" id="survey-add"> + </button>
      </li>
    <?php endif; ?>
  </ul>
  <?php
}

function og_survey_settings_section_main_callback() {
  echo __( 'After adding Questions and Answers, choose one or more Survey pages from the Multiple Surveys tab, i.e. the pages which will contain the Questions & Answers and choose another page where the Resultant Products should display.', 'og-survey' );
}

function og_survey_settings_section_shortcode_callback() {
  echo __( 'Add or Remove one or more Shortcodes with one or more Questions, and add the Shortcodes to different Pages.', 'og-survey' );
}

function og_survey_options_page() { ?>
  <div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>OG Survey Plugin Settings Page</h2>
    <?php settings_errors(); ?>
    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'main'; ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=og-survey-settings&tab=main" class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>">Main</a>
      <a href="?page=og-survey-settings&tab=shortcode" class="nav-tab <?php echo $active_tab == 'shortcode' ? 'nav-tab-active' : ''; ?>">Multiple Surveys</a>
    </h2>
    <form action='options.php' method='post'>
      <?php
        if( $active_tab == 'main' ) {
          settings_fields( 'pluginPageMain' );
          do_settings_sections( 'pluginPageMain' );
        } elseif( $active_tab == 'shortcode' ) {
          settings_fields( 'pluginPageShortcode' );
          do_settings_sections( 'pluginPageShortcode' );
        }
        submit_button();
      ?>
    </form>
  </div>
  <?php
}