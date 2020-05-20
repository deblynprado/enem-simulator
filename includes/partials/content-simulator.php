<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="container simulator-content">
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
          $categories = enem_simulator_get_categories(); 
          foreach ($categories as $value) : ?>
          <option value="<?php echo $value[ 'slug' ] ?>"><?php echo $value[ 'name' ] ?></option>
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
<div class="container">
  <div class="row">
    <!-- simulator-nav -->
    <div class="simulator-nav" style="display: none;">
      <div class="col-12">
        <h3><?php echo __( 'Browse in the test', 'enem-simulator' ) ?></h3>
        <ul>
          <li class="text-success">RESPONDEU</li>
          <li class="text-danger">NÃO VISUALIZADA</li>
          <li class="text-warning">VISUALIZADA</li>
        </ul>
        <h4><?php echo __( 'Knowledge areas', 'enem-simulator' ) ?></h4>
      </div>
      <div class="col-12">
        <div class="simulator-nav-categories">

        </div>
      </div>
    </div>
    <!-- /simulator-nav -- -->
  </div>
</div>
<div class="container">
  <div class="row">
    <!-- simulator-result -->
    <div class="simulator-result">
      <div class="col-12">
        <h3><?php echo __( 'Simulator Result', 'enem-simulator' ) ?></h3>
        <h4><?php echo __( 'Knowledge areas', 'enem-simulator' ) ?></h4>
        <?php $categories = enem_simulator_get_categories(); ?>
        <table class="simulator-result-table table">
          <thead>
            <tr>
              <th style="width:100%;"><?php echo __( 'Description', 'enem-simulator' ) ?></th>
              <th><?php echo __( 'Rate', 'enem-simulator' ) ?></th>
              <th><?php echo __( 'Successes', 'enem-simulator' ) ?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($categories as $value) : ?>
            <tr>
              <td><?php echo $value[ 'name' ] ?></td>
              <td>0</td>
              <td>0</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="col-12">
        <div class="simulator-result-categories">

        </div>
      </div>
    </div>
    <!-- /simulator-result -- -->
  </div>
</div>
<!-- enem-simulator-modal -- -->
<div class="modal enem-simulator-modal" tabindex="-1" role="dialog" id="enem-simulator-modal-finish">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo __( 'Confirmation', 'enem-simulator' ) ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo __( 'Do you really want end the current simulator and see the results?', 'enem-simulator' ) ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="finisish-simulator"><?php echo __( 'Finish', 'enem-simulator' ) ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'Back', 'enem-simulator' ) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /enem-simulator-modal -- -->