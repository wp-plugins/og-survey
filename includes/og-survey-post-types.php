<?php

/**
 * Register a question post type.
 */
if( ! function_exists( 'og_question_post_type_init' ) ) {
  
  add_action( 'init', 'og_question_post_type_init' );

  function og_question_post_type_init() {
    $labels = array(
      'name' => _x( 'Questions', 'post type general name', 'og-survey' ),
      'singular_name' => _x( 'Question', 'post type singular name', 'og-survey' ),
      'menu_name' => _x( 'Questions', 'admin menu', 'og-survey' ),
      'name_admin_bar' => _x( 'Question', 'add new on admin bar', 'og-survey' ),
      'add_new' => _x( 'Add New', 'question', 'og-survey' ),
      'add_new_item' => __( 'Add New Question', 'og-survey' ),
      'new_item' => __( 'New Question', 'og-survey' ),
      'edit_item' => __( 'Edit Question', 'og-survey' ),
      'view_item' => __( 'View Question', 'og-survey' ),
      'all_items' => __( 'All Questions', 'og-survey' ),
      'search_items' => __( 'Search Questions', 'og-survey' ),
      'parent_item_colon' => __( 'Parent Questions:', 'og-survey' ),
      'not_found' => __( 'No questions found.', 'og-survey' ),
      'not_found_in_trash' => __( 'No questions found in Trash.', 'og-survey' )
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'question' ),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => null,
      'menu_icon' => 'dashicons-book',
      'supports' => array( 'title' )
    );

    register_post_type( 'question', $args );
  }
}

/**
 * Add a Metabox to "Question" Post Type
 */
if( ! function_exists( 'og_question_answer_metabox' ) && ! function_exists( 'og_question_answer_metabox_content' ) && ! function_exists( 'save_og_question_answer_metabox_content' ) ) {

  add_action( 'add_meta_boxes', 'og_question_answer_metabox' );

  function og_question_answer_metabox() {
    add_meta_box( 'og_question_answers', __( 'Answers', 'og-survey' ), 'og_question_answer_metabox_content', 'question', 'normal' );
  }

  function og_question_answer_metabox_content($post) {
    $all_woo_products = get_posts( array( 'posts_per_page' => 5000, 'post_type' => 'product', 'post_status' => 'publish' ) );
    $all_sub_questions = get_posts( array( 'posts_per_page' => 5000, 'post_type' => 'sub-question', 'post_status' => 'publish' ) );
    $question_meta = get_post_meta($post->ID, '_question', true);
    if( !empty( $question_meta ) && is_array( $question_meta ) ) {
      foreach( $question_meta as $key => $all ) {
        foreach( $all as $i => $val ) {
          $modified_question_meta[$i][$key] = $val;
        }
      }
    } ?>
    <ul class="og-survey-metabox-ul" id="answer-metabox">
      <?php if( !empty( $modified_question_meta ) && is_array( $modified_question_meta ) ) : foreach( $modified_question_meta as $key => $val ) : ?>
        <li>
          <input type="hidden" value="<?php echo $key; ?>" />
          <div class="answer-part">
            <input type="text" name="question[answer][]" class="widefat enteranswer" placeholder="Enter the Answer Here.." value="<?php echo $val['answer']; ?>" required />
            <p style="font-size: 14px;"><input type="checkbox" class="show_subqstn_hide_relprod" <?php if( !empty( $val['sub_question_id'] ) ) echo 'checked'; ?> /> Has a Sub Question? </p>
          </div>
          <div class="others-part subqstn" <?php if( empty( $val['sub_question_id'] ) ) : ?>style="display: none;"<?php endif; ?>>
            <?php if( !empty( $all_sub_questions ) ) : ?>
            <table>
              <tbody>
                <?php foreach( $all_sub_questions as $sub_question ) : ?>
                  <tr><td><input type="radio" class="subuqestionid" name="question[sub_question_id][<?php echo $key; ?>][]" value="<?php echo $sub_question->ID; ?>" <?php if( $val['sub_question_id'][0] == $sub_question->ID ) echo 'checked'; ?> /> <?php echo $sub_question->post_title; ?> </td></tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else : ?>
              <p class="description">No Sub Questions added yet! But you can add a Sub Question by <a href="<?php echo admin_url( 'post-new.php?post_type=sub-question' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part relprod whatrecommend" <?php if( !empty( $val['sub_question_id'] ) ) : ?>style="display: none;"<?php endif; ?>>
            <table>
              <tbody>
                <tr><td><input type="radio" name="question[recommend][<?php echo $key; ?>][]" class="what_to_recommend" value="wooproduct" <?php if( $val['recommend'][0] == 'wooproduct' ) echo 'checked'; ?> <?php if( empty( $val['sub_question_id'] ) ) echo 'required'; ?> /> Recommend WooCommerce Product?</td></tr>
                <tr><td><input type="radio" name="question[recommend][<?php echo $key; ?>][]" class="what_to_recommend" value="other" <?php if( $val['recommend'][0] == 'other' ) echo 'checked'; ?> <?php if( empty( $val['sub_question_id'] ) ) echo 'required'; ?> /> Recommend Something Else?</td></tr>
              </tbody>
            </table>
          </div>
          <div class="others-part relprod wooproduct" <?php if( empty( $val['related_product_id'] ) ) : ?>style="display: none;"<?php endif; ?>>
            <?php if( !empty( $all_woo_products ) ) : ?>
              <table>
                <tbody>
                  <?php foreach( $all_woo_products as $product ) : ?>
                    <tr><td><input type="checkbox" name="question[related_product_id][<?php echo $key; ?>][]" value="<?php echo $product->ID; ?>" <?php if( in_array( $product->ID, $val['related_product_id'] ) ) echo 'checked'; ?> /> <?php echo $product->post_title; ?> </td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <p class="description">No products available! Add a product by <a href="<?php echo admin_url( 'post-new.php?post_type=product' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part relprod other" <?php if( empty( $val['recommend_other'] ) ) : ?>style="display: none;"<?php endif; ?>>
            <input type="text" class="widefat checkvalidshortcodeyoutubeurl" name="question[recommend_other][]" value='<?php echo $val['recommend_other']; ?>' placeholder="Enter Shortcode for Non-WooCommerce Products or Files or Videos OR Enter a YouTube URL.." />
          </div>
          <div class="others-part relprod whyrecommend" <?php if( !empty( $val['sub_question_id'] ) ) : ?>style="display: none;"<?php endif; ?>>
            <textarea class="widefat" name="question[why_recommend_this][]" rows="5" placeholder="Why You Recommend This.."><?php echo $val['why_recommend_this']; ?></textarea>
          </div>
          <button class="button-secondary answer-delete"> - </button>
          <?php if( (int)$key + 1 == count( $modified_question_meta ) ) : ?>
            <button class="button-primary" id="answer-add"> + </button>
          <?php endif; ?>
        </li>
      <?php endforeach; else : ?>
        <li>
          <input type="hidden" value="0" />
          <div class="answer-part">
            <input type="text" name="question[answer][]" class="widefat enteranswer" placeholder="Enter the Answer Here.." required />
            <p style="font-size: 14px;"><input type="checkbox" class="show_subqstn_hide_relprod" /> Has a Sub Question? </p>
          </div>
          <div class="others-part subqstn" style="display: none;">
            <?php if( !empty( $all_sub_questions ) ) : ?>
            <table>
              <tbody>
                <?php foreach( $all_sub_questions as $sub_question ) : ?>
                  <tr><td><input type="radio" name="question[sub_question_id][0][]" class="subuqestionid" value="<?php echo $sub_question->ID; ?>" /> <?php echo $sub_question->post_title; ?> </td></tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else : ?>
              <p class="description">No Sub Questions added yet! But you can add a Sub Question by <a href="<?php echo admin_url( 'post-new.php?post_type=sub-question' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part relprod whatrecommend">
            <table>
              <tbody>
                <tr><td><input type="radio" name="question[recommend][0][]" class="what_to_recommend" value="wooproduct" required /> Recommend WooCommerce Product?</td></tr>
                <tr><td><input type="radio" name="question[recommend][0][]" class="what_to_recommend" value="other" required /> Recommend Something Else?</td></tr>
              </tbody>
            </table>
          </div>
          <div class="others-part relprod wooproduct" style="display: none;">
            <?php if( !empty( $all_woo_products ) ) : ?>
              <table>
                <tbody>
                  <?php foreach( $all_woo_products as $product ) : ?>
                    <tr><td><input type="checkbox" name="question[related_product_id][0][]" value="<?php echo $product->ID; ?>" /> <?php echo $product->post_title; ?> </td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <p class="description">No products available! Add a product by <a href="<?php echo admin_url( 'post-new.php?post_type=product' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part relprod other" style="display: none;">
            <input type="text" class="widefat checkvalidshortcodeyoutubeurl" name="question[recommend_other][]" placeholder="Enter Shortcode for Non-WooCommerce Products or Files or Videos OR Enter a YouTube URL.." />
          </div>
          <div class="others-part relprod whyrecommend">
            <textarea class="widefat" name="question[why_recommend_this][]" rows="5" placeholder="Why You Recommend This.."></textarea>
          </div>
          <button class="button-secondary answer-delete"> - </button>
          <button class="button-primary" id="answer-add"> + </button>
        </li>
      <?php endif; ?>
    </ul>
  <?php }

  add_action( 'save_post', 'save_og_question_answer_metabox_content' );

  function save_og_question_answer_metabox_content($post_id) {
    global $post, $_POST;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( !current_user_can( 'edit_post', $post_id ) ) return;
    if( $post->post_type != 'question' ) return;

    update_post_meta( $post->ID, '_question', $_POST['question'] );
  }
}

/**
 * Register a sub-question post type.
 */
if( ! function_exists( 'og_sub_question_post_type_init' ) ) {
  
  add_action( 'init', 'og_sub_question_post_type_init' );

  function og_sub_question_post_type_init() {
    $labels = array(
      'name' => _x( 'Sub Questions', 'post type general name', 'og-survey' ),
      'singular_name' => _x( 'Sub Question', 'post type singular name', 'og-survey' ),
      'menu_name' => _x( 'Sub Questions', 'admin menu', 'og-survey' ),
      'name_admin_bar' => _x( 'Sub Question', 'add new on admin bar', 'og-survey' ),
      'add_new' => _x( 'Add New', 'sub-question', 'og-survey' ),
      'add_new_item' => __( 'Add New Sub Question', 'og-survey' ),
      'new_item' => __( 'New Sub Question', 'og-survey' ),
      'edit_item' => __( 'Edit Sub Question', 'og-survey' ),
      'view_item' => __( 'View Sub Question', 'og-survey' ),
      'all_items' => __( 'All Sub Questions', 'og-survey' ),
      'search_items' => __( 'Search Sub Questions', 'og-survey' ),
      'parent_item_colon' => __( 'Parent Sub Questions:', 'og-survey' ),
      'not_found' => __( 'No sub questions found.', 'og-survey' ),
      'not_found_in_trash' => __( 'No sub questions found in Trash.', 'og-survey' )
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'sub-question' ),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => null,
      'menu_icon' => 'dashicons-book',
      'supports' => array( 'title' )
    );

    register_post_type( 'sub-question', $args );
  }
}

/**
 * Add a Metabox to "Sub Question" Post Type
 */
if( ! function_exists( 'og_sub_question_answer_metabox' ) && ! function_exists( 'og_sub_question_answer_metabox_content' ) && ! function_exists( 'save_og_sub_question_answer_metabox_content' ) ) {

  add_action( 'add_meta_boxes', 'og_sub_question_answer_metabox' );

  function og_sub_question_answer_metabox() {
    add_meta_box( 'og_sub_question_answers', __( 'Answers', 'og-survey' ), 'og_sub_question_answer_metabox_content', 'sub-question', 'normal' );
  }

  function og_sub_question_answer_metabox_content($post) {
    $all_woo_products = get_posts( array( 'posts_per_page' => 5000, 'post_type' => 'product', 'post_status' => 'publish' ) );
    $sub_question_meta = get_post_meta( $post->ID, '_question', true );
    if( !empty( $sub_question_meta ) && is_array( $sub_question_meta ) ) {
			foreach( $sub_question_meta as $key => $all ) {
				foreach( $all as $i => $val ) {
					$modified_sub_question_meta[$i][$key] = $val;   
				}
			}
		} ?>
    <ul class="og-survey-metabox-ul" id="answer-metabox">
      <?php if( !empty( $modified_sub_question_meta ) && is_array( $modified_sub_question_meta ) ) : foreach( $modified_sub_question_meta as $key => $val ) : ?>
        <li>
          <input type="hidden" value="<?php echo $key; ?>" />
          <div class="answer-part">
            <input type="text" name="sub_question[answer][]" class="widefat enteranswer" placeholder="Enter the Answer Here.." value="<?php echo $val['answer']; ?>" required />
          </div>
          <div class="others-part">
            <table>
              <tbody>
                <tr><td><input type="radio" name="sub_question[recommend][<?php echo $key; ?>][]" class="what_to_recommend" value="wooproduct" <?php if( $val['recommend'][0] == 'wooproduct' ) echo 'checked'; ?> required /> Recommend WooCommerce Product?</td></tr>
                <tr><td><input type="radio" name="sub_question[recommend][<?php echo $key; ?>][]" class="what_to_recommend" value="other" <?php if( $val['recommend'][0] == 'other' ) echo 'checked'; ?> required /> Recommend Something Else?</td></tr>
              </tbody>
            </table>
          </div>
          <div class="others-part wooproduct" <?php if( $val['recommend'][0] == 'other' ) : ?>style="display: none;"<?php endif; ?>>
            <?php if (!empty($all_woo_products)) : ?>
              <table>
                <tbody>
                  <?php foreach ($all_woo_products as $product) : ?>
                    <tr><td><input type="checkbox" name="sub_question[related_product_id][<?php echo $key; ?>][]" value="<?php echo $product->ID; ?>" <?php if( in_array( $product->ID, $val['related_product_id'] ) ) echo 'checked'; ?> /> <?php echo $product->post_title; ?> </td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <p class="description">No products available! Add a product by <a href="<?php echo admin_url( 'post-new.php?post_type=product' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part other" <?php if( $val['recommend'][0] == 'wooproduct' ) : ?>style="display: none;"<?php endif; ?>>
            <input type="text" class="widefat checkvalidshortcodeyoutubeurl" name="sub_question[recommend_other][]" value='<?php echo $val['recommend_other']; ?>' placeholder="Enter Shortcode for Non-WooCommerce Products or Files or Videos OR Enter a YouTube URL.." />
          </div>
          <div class="others-part">
            <textarea class="widefat" name="sub_question[why_recommend_this][]" rows="5" placeholder="Why You Recommend This.."><?php echo $val['why_recommend_this']; ?></textarea>
          </div>
          <button class="button-secondary answer-delete"> - </button>
          <?php if( (int)$key + 1 == count( $modified_sub_question_meta ) ) : ?>
            <button class="button-primary" id="answer-add"> + </button>
          <?php endif; ?>
        </li>
      <?php endforeach; else : ?>
        <li>
          <input type="hidden" value="0" />
          <div class="answer-part">
            <input type="text" name="sub_question[answer][]" class="widefat enteranswer" placeholder="Enter the Answer Here.." required />
          </div>
          <div class="others-part">
            <table>
              <tbody>
                <tr><td><input type="radio" name="sub_question[recommend][0][]" class="what_to_recommend" value="wooproduct" required /> Recommend WooCommerce Product?</td></tr>
                <tr><td><input type="radio" name="sub_question[recommend][0][]" class="what_to_recommend" value="other" required /> Recommend Something Else?</td></tr>
              </tbody>
            </table>
          </div>
          <div class="others-part wooproduct" style="display: none;">
            <?php if (!empty($all_woo_products)) : ?>
              <table>
                <tbody>
                  <?php foreach ($all_woo_products as $product) : ?>
                    <tr><td><input type="checkbox" name="sub_question[related_product_id][0][]" value="<?php echo $product->ID; ?>" /> <?php echo $product->post_title; ?> </td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <p class="description">No products available! Add a product by <a href="<?php echo admin_url( 'post-new.php?post_type=product' ); ?>">Clicking Here</a>.</p>
            <?php endif; ?>
          </div>
          <div class="others-part other" style="display: none;">
            <input type="text" class="widefat checkvalidshortcodeyoutubeurl" name="sub_question[recommend_other][]" placeholder="Enter Shortcode for Non-WooCommerce Products or Files or Videos OR Enter a YouTube URL.." />
          </div>
          <div class="others-part">
            <textarea class="widefat" name="sub_question[why_recommend_this][]" rows="5" placeholder="Why You Recommend This.."></textarea>
          </div>
          <button class="button-secondary answer-delete"> - </button>
          <button class="button-primary" id="answer-add"> + </button>
        </li>
      <?php endif; ?>
    </ul>
  <?php }

  add_action( 'save_post', 'save_og_sub_question_answer_metabox_content' );

  function save_og_sub_question_answer_metabox_content($post_id) {
    global $post, $_POST;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( !current_user_can( 'edit_post', $post_id ) ) return;
    if( $post->post_type != 'sub-question' ) return;

    update_post_meta( $post->ID, '_question', $_POST['sub_question'] );
  }
}