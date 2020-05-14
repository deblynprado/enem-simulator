<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="question" style="display:none">
  <h3 class="question-category"><?php echo $categoryName; ?></h4>
	<h4 class="question-title"><?php the_title(); echo (' '); echo $index+1; ?></h4>
  <p class="question-message"> <?php the_content() ?> </p>
  <div class="figure question-thumbnail">
    <?php the_post_thumbnail( 'post-thumbnail', 
            array(
              'class' => 'rounded',
              'title' => $questions->title
          )); 
    ?>
    <figcaption class="figure-caption text-right"><?php the_post_thumbnail_caption(); ?></figcaption>
  </div>
  <div class="question-options">
    <?php 
      if( $fields ): $name = substr( md5( serialize( $fields ) ), 0, 8 ); 
        $indexField = 0;
    ?>
      <?php foreach( $fields as $value ): $id = substr( md5( serialize( $value ) ), 0, 8 ); $indexField++; ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="<?php echo $id ?>" name="<?php echo $name ?>">
          <label class="custom-control-label" for="<?php echo $id ?>"><?php echo chr($indexField + 64); ?> - <?php echo $value['text_answer'] ?></label>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>