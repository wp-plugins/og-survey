<?php

/**
 * Shortcodes
 */
add_shortcode( 'questions', 'og_survey_question_answer_content_shortcode' );

function og_survey_question_answer_content_shortcode( $atts ) {
  global $post;
  extract( shortcode_atts( array( 'ids' => '' ), $atts ) );
  if( !empty( $ids ) ) {
    $array_of_question_ids = explode( ',', $ids );
    $all_questions = array();
    foreach( $array_of_question_ids as $qstnid ) {
      array_push( $all_questions, get_post( $qstnid ) );
    }
    $options = get_option( 'og_survey_settings' ); ?>
    <?php if( !empty( $all_questions ) && !empty( $options['resultant_page'] ) ) : ?>
      <?php if( !isset( $_REQUEST['terms-conditions-accepted'] ) && ( empty( $_REQUEST['terms-conditions-accepted'] ) || ( $_REQUEST['terms-conditions-accepted'] != 'true' ) ) ) : ?>
        <form id="og-survey-accept-form" class="ws-validate" method="post" action="">
          <p><input type="checkbox" name="terms-conditions-accepted" value="true" required /> I agree to the <a href="<?php if( !empty( $options['terms_conditions_page'] ) ) echo get_page_link( get_page_by_title( $options['terms_conditions_page'] )->ID ); else echo '#'; ?>" target="_blank">Terms and Conditions</a> prior to using this Survey.</p>
          <p><input type="submit" id="og-survey-accept-form-submit" value="Build My Personalized Plan" /></p>
        </form>
      <?php endif; ?>

      <?php if( isset( $_REQUEST['terms-conditions-accepted'] ) && ( $_REQUEST['terms-conditions-accepted'] == 'true' ) ) : ?>
        <form method="post" id="og-survey-form" class="ws-validate" action="<?php echo get_page_link( get_page_by_title( $options['resultant_page'] )->ID ); ?>">
          <input type="hidden" id="og-survey-question-number" value="1" />
          <ul class="og-survey-main-questions">
            <?php foreach( $all_questions as $question ) : ?>
              <li>
                <?php
                  echo '<strong>' . $question->post_title . '</strong>';
                  $question_meta = get_post_meta( $question->ID, '_question', true );
                  if( !empty( $question_meta ) && is_array( $question_meta ) ) {
                    foreach( $question_meta as $key => $all ) {
                      foreach( $all as $i => $val ) {
                        $modified_question_meta[$i][$key] = $val;
                      }
                    }
                  }
                  if( !empty( $modified_question_meta ) && is_array( $modified_question_meta ) ) :
                ?>
                  <ul class="og-survey-main-questions-answers">
                    <?php foreach( $modified_question_meta as $key => $val ) : ?>
                      <li>
                        <span><input type="radio" name="answer[<?php echo $question->ID; ?>]" data-id="<?php echo $question->ID; ?>" value="<?php echo preg_replace( "#[[:punct:]]#", "", $val['answer'] ); ?>" /> <?php echo $val['answer']; ?></span>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <p>
            <input type="button" id="og-survey-previous-question" value="Previous" style="display: inline;" />
            <input type="button" id="og-survey-next-question" value="Next" style="display: inline;" />
          </p>
          <ul id="og-survey-question-progress">
            <?php for( $i = 0; $i < count( $all_questions ); $i++ ) : ?>
              <li>
                <img style="width: 10px;" src="<?php echo plugins_url( 'images/black-dot.png', dirname(__FILE__) ); ?>" alt="progress" />
              </li>
            <?php endfor; ?>
          </ul>
          <p><input type="submit" id="og-survey-form-submit" value="Submit" /></p>
        </form>
    <?php endif; endif; ?>
  <?php }
}

add_shortcode( 'og-survey-result', 'og_survey_question_answer_result_shortcode' );

function og_survey_question_answer_result_shortcode() {
  $options = get_option( 'og_survey_settings' );
  if( ( isset( $_REQUEST['answer'] ) && !empty( $_REQUEST['answer'] ) ) ) {
    $resultant_post_meta = array();
    foreach( $_REQUEST['answer'] as $question_id => $answer_text ) {
      $question_meta = get_post_meta( $question_id, '_question', true );
      if (!empty($question_meta) && is_array($question_meta)) {
        foreach ($question_meta as $key => $all) {
          foreach ($all as $i => $val) {
            $modified_question_meta[$i][$key] = $val;
          }
        }
      }
      foreach( $modified_question_meta as $mqm ) {
        if( preg_replace( "#[[:punct:]]#", "", $mqm['answer'] ) == $answer_text )
          array_push( $resultant_post_meta, $mqm );
      }
    }
    $result = array();
    $i_woo = 0;
    $i_other = 0;
    foreach( $resultant_post_meta as $rpm ) {
      $recommend = $rpm['recommend'][0];
      if( $recommend == 'wooproduct' ) {
        foreach( $rpm['related_product_id'] as $key_id => $id ) {
          $result[$recommend][$i_woo]['product_ids'][$key_id] = $id;
        }
        $result[$recommend][$i_woo]['why_recommended'] = $rpm['why_recommend_this'];
        $i_woo++;
      } elseif( $recommend == 'other' ) {
        $result[$recommend][$i_other]['other_content'] = $rpm['recommend_other'];
        $result[$recommend][$i_other]['why_recommended'] = $rpm['why_recommend_this'];
        $i_other++;
      }
    }
    if( isset( $result['wooproduct'] ) && !empty( $result['wooproduct'] ) ) {
      foreach( $result['wooproduct'] as $wooproduct ) { ?>
        <div class="og-survey-result">
          <p><?php echo $wooproduct['why_recommended']; ?></p>
          <div class="result-content">
            <?php echo do_shortcode( '[products ids="'. implode( ',', $wooproduct['product_ids'] ) .'"]' ); ?>
          </div>
        </div>
      <?php }
    }
    if( isset( $result['other'] ) && !empty( $result['other'] ) ) {
      foreach( $result['other'] as $other ) { ?>
        <div class="og-survey-result">
          <p><?php echo $other['why_recommended']; ?></p>
          <div class="result-content">
            <?php
              if( substr( $other['other_content'], 0, 1 ) == '[' && substr( $other['other_content'], -1 ) == ']' ) {
                echo do_shortcode( $other['other_content'] );
              } else {
                preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $other['other_content'], $matches ); ?>
                <iframe width="100%" height="<?php echo $options['iframe_height']; ?>" src="https://www.youtube.com/embed/<?php echo $matches[1]; ?>" frameborder="0" allowfullscreen></iframe>
              <?php }
            ?>
          </div>
        </div>
      <?php }
    }
  } else {
    echo '<p>Sorry! Nothing matching your choice!</p>';
  }
}