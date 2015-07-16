<?php

/**
 * All Ajax Calls are Handled here
 */
add_action( 'wp_ajax_find_sub_question', 'og_survey_find_sub_question_callback' );
add_action( 'wp_ajax_nopriv_find_sub_question', 'og_survey_find_sub_question_callback' );

function og_survey_find_sub_question_callback() {
  $question_id = $_POST['questionId'];
  $answer = $_POST['answer'];
  $question_meta = get_post_meta($question_id, '_question', true);
  if (!empty($question_meta) && is_array($question_meta)) {
    foreach ($question_meta as $key => $all) {
      foreach ($all as $i => $val) {
        $modified_question_meta[$i][$key] = $val;
      }
    }
    $more_modified = array();
    foreach ($modified_question_meta as $v) {
      if (isset($v['sub_question_id']) && !empty($v['sub_question_id'])) {
        $more_modified[preg_replace( "#[[:punct:]]#", "", $v['answer'] )] = $v['sub_question_id'];
      }
    }
  }
  if( array_key_exists( $answer, $more_modified ) ) {
    $that_sub_question_id = $more_modified[$answer][0];
    $that_sub_question_details = get_post( $that_sub_question_id ); ?>
    <?php if( !empty( $that_sub_question_details ) ) : ?>
      <ul class="og-survey-main-questions-answers-sub-questions">
        <li>
          <?php
            echo '<strong>' . $that_sub_question_details->post_title . '</strong>';
            $that_sub_question_meta = get_post_meta( $that_sub_question_id, '_question', true );
            if( !empty( $that_sub_question_meta ) && is_array( $that_sub_question_meta ) ) {
              foreach( $that_sub_question_meta as $key => $all ) {
                foreach( $all as $i => $val ) {
                  $that_sub_question_meta_modified[$i][$key] = $val;   
                }
              }
            }
            if( !empty( $that_sub_question_meta_modified ) && is_array( $that_sub_question_meta_modified ) ) :
          ?>
            <ul class="og-survey-main-questions-answers-sub-questions-answers">
              <li>
                <?php foreach( $that_sub_question_meta_modified as $sub_question_meta ) : ?>
                  <input type="radio" name="answer[<?php echo $that_sub_question_id; ?>]" value="<?php echo preg_replace( "#[[:punct:]]#", "", $sub_question_meta['answer'] ); ?>" required /> <?php echo $sub_question_meta['answer']; ?><br />
                <?php endforeach; ?>
              </li>
            </ul>
          <?php endif; ?>
        </li>
      </ul>
    <?php endif; ?>
  <?php }
  wp_die();
}