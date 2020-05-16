<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="container">
  <div class="row simulator-header">
    <div class="col-10">
      <h2 class="simulator-title"><?php echo __( 'Enem Simulator', 'enem-simulator' ) ?></h2>
      <div class="simulator-initial-message">
        <p> <?php the_field( 'initial_message', 'option' ) ?> </p>
        <p> <?php echo __('Do as many simulated tests wish for you to have a good performance on the day of exam.', 'enem-simulator') ?> </p>
        <p> <?php echo __('Choose a desired category to start', 'enem-simulator') ?> </p>
      </div><!-- /.simulator-header -->
    </div>
  </div><!-- /.simulator-header -->
    
  <div class="row simulator-category-options">
    <?php if( get_field( 'question_categories', 'option' ) ) : ?>
      <div class="col-10">  
        <select class="custom-select" name="question_category" id="question_category">
          <?php
          $categories = get_field( 'question_categories', 'option' ); 
          foreach ($categories as $value) : ?>
          <option value="<?php echo $value[ 'question_category' ]->slug ?>"><?php echo $value[ 'question_category' ]->name ?></option>
          <?php endforeach; ?>
        </select>
      </div><!-- /.col -->
    <?php endif; ?>

    <div class="col-10 mt-4">
      <button class="btn btn-primary" id="start-simulator"><i class="fa fa-book"></i> <?php echo __( 'Start Simulator', 'enem-simulator' ) ?></button>
    </div><!-- /.col -->
  </div><!-- /.simulator-category-options -->

  <div class="row">
    <div class="col-10 simulator-categories">
      
    </div>
    <!-- progress -->
    <?php include ('simulator-progress.php'); ?>
    <!-- /progress -->
    <!-- timer -->
    <?php include ('simulator-timer.php'); ?>
    <!-- /timer -->
    <!-- pagination -->
    <?php include ( 'simulator-pagination.php' ); ?>
    <!-- /pagination -->
    <!-- footer -->
    <?php include ( 'simulator-footer.php' ); ?>
    <!-- /footer -->
  </div>
</div>