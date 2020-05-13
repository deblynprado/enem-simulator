<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="question" style="display:none" id="question-<?php echo $index ?>">
	<h4><?php the_title() ?></h4>
    <p> <?php the_content() ?> </p>
    <div>
        <?php if( $fields ): $name = substr( md5( serialize( $fields ) ), 0, 8 ); ?>
            <div>
              <?php foreach( $fields as $value ): $id = substr( md5( serialize( $value ) ), 0, 8 ); ?>
                <div class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" id="<?php echo $id ?>" name="<?php echo $name ?>">
                  <label class="custom-control-label" for="<?php echo $id ?>"><?php echo $value['text_answer'] ?></label>
                </div>
              <?php endforeach; ?>
            </div>
        <?php endif; ?>
	</div>
</div>