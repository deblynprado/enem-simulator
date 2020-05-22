<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="question" style="display:none" id="<?php echo get_the_ID(); ?>">
  <h3 class="question-category"><?php echo $categoryName; ?></h4>
	<h4 class="question-title"><?php echo __( 'Question', 'enem-simulator' ); echo (' '); echo $index+1; ?></h4>
  <p class="question-message"> <?php echo $post->post_content ?> </p>
  <div class="figure question-thumbnail">
    <?php the_post_thumbnail( 'post-thumbnail', 
            array(
              'class' => 'rounded, image-fluid',
              'title' => $questions->title
          )); 
    ?>
    <figcaption class="figure-caption text-right"><?php the_post_thumbnail_caption(); ?></figcaption>
  </div>
  <div class="question-options" data-question-index="<?php echo $index ?>">
    <?php 
      if( $fields ): 
        $name = substr( md5( serialize( $fields ) ), 0, 8 ); 
        $field = 0;
      ?>
      <?php foreach( $fields as $value ): $id = substr( md5( serialize( $value ) ), 0, 8 ); $field++; ?>
        <div class="custom-control custom-checkbox">
          <input type="hidden" name="answer_<?php echo $id ?>" id="answer_<?php echo $id ?>" value="<?php echo $value['correct_answer'][0] ?>">
          <input type="checkbox" class="custom-control-input" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $field ?>">
          <label class="custom-control-label" for="<?php echo $id ?>"><?php echo chr($field + 64); ?> - <?php echo $value['text_answer'] ?></label>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
  </div>
</div>