<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="container">
  <div class="row simulator-header">
    <div class="col-10">
      <h2><?php echo __( 'Enem Simulator', 'enem-simulator' ) ?></h2>
      <p> <?php the_field( 'initial_message', 'option' ) ?> </p>
      <p> <?php echo __('Do as many simulated tests wish for you to have a good performance on the day of exam.', 'enem-simulator') ?> </p>
      <p> <?php echo __('Choose a desired category to start', 'enem-simulator') ?> </p>
    </div>
  </div><!-- /.simulator-header -->
    
  <div class="row simulator-category-options">
    <?php if( get_field( 'question_categories', 'option' ) ) : ?>
      <div class="col-10">  
        <select class="custom-select" name="question_category" id="question_category">
          <?php
          $categories = get_field( 'question_categories', 'option' ); 
          foreach ($categories as $value) : ?>
          <option value="<?php echo $value[ 'question_category' ]->term_id ?>"><?php echo $value[ 'question_category' ]->name ?></option>
          <?php endforeach; ?>
        </select>
      </div><!-- /.col -->
    <?php endif; ?>

    <div class="col-10 mt-4">
      <button class="btn btn-primary" id="start-simulator"><i class="fa fa-book"></i> <?php echo __( 'Start Simulator', 'enem-simulator' ) ?></button>
    </div><!-- /.col -->
  </div>
</div><!-- /.simulator-category-options -->

<div class="row">
  <form name="content-question-form" id="content-question-form" enctype="multipart/form-data" method="post">
    <div class="col-10 content-question">
      
    </div>
  </form>
  <!-- pagination -->
  <?php include ( 'simulator-pagination.php' ); ?>
  <!-- /pagination -->
</div>
<div class="row end-simulator" style="display: none">
  <div class="col-10">
    <button  type="submit" class="btn btn-primary" id="end-simulator"><?php echo __( 'End Simulator', 'enem-simulator' ) ?></button>
  </div>
</div>
</div>