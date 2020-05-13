<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="contaner">
  <div class="row">
    <div class="col-12">
        <h2><?php echo __( 'Enem Simulator', 'enem-simulator' ) ?></h2>
        <p> <?php the_field( 'initial_message', 'option' ) ?> </p>
        <p> <?php echo __('Choose a desired category to start') ?> </p>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <select class="form-control form-control-lg" name="" id="">
        <?php
          $categories = get_field( 'question_categories', 'option' ); 
          foreach ($categories as $value) : ?>
            <option value="<?php echo $value[ 'question_category' ]->term_id ?>"><?php echo $value[ 'question_category' ]->name ?></option>
          <?php endforeach; ?>
        ?>
      </select>
    </div>
    <div class="col">
      <button class="btn btn-primary"><?php echo __( 'Start Simulator', 'enem-simulator' ) ?></button>
    </div>
  </div>
</div>