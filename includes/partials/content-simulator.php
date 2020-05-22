<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="container simulator-content">
  <div class="row simulator-header">
    <div class="col-10">
      <h2 class="simulator-title"><?php echo __( 'Enem Simulator', 'enem-simulator' ) ?></h2>
      <div class="simulator-initial-message">
        <p> <?php echo enem_simulator_get_option( 'initial_message' ) ?> </p>
        <p> <?php echo __('Do as many simulated tests wish for you to have a good performance on the day of exam.', 'enem-simulator') ?> </p>
        <?php if( count( $categories ) > 0 ) : ?>
        <p> <?php echo __('Choose a desired category to start', 'enem-simulator') ?> </p>
        <?php endif; ?>
      </div><!-- /.simulator-header -->
    </div>
  </div><!-- /.simulator-header -->
    
  <div class="row simulator-category-options">
    <?php if( get_field( 'question_categories', 'option' ) ) : ?>
      <div class="col-10">  
        <?php if( count( $categories ) > 0 ) : ?>
        <select class="custom-select" name="question_category" id="question_category">
          <?php foreach ($categories as $value) : ?>
          <option value="<?php echo $value[ 'slug' ] ?>"><?php echo $value[ 'name' ] ?></option>
          <?php endforeach; ?>
        </select>
        <?php endif;?>
      </div><!-- /.col -->
    <?php endif; ?>

    <div class="col-10 mt-4">
      <?php if( count( $categories ) > 0 ) : ?>
      <button class="btn btn-primary" id="start-simulator"><i class="fa fa-book"></i> <?php echo __( 'Start Simulator', 'enem-simulator' ) ?></button>
      <?php else : ?>
      <p class="text-danger"><?php echo __( 'No categories found', 'enem-simulator' ) ?></p>
      <?php endif; ?>
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
        <h3 class="text-uppercase"><?php echo __( 'Browse in the test', 'enem-simulator' ) ?></h3>
        <ul>
          <li class="text-success text-uppercase"><?php echo __( 'Answered', 'enem-simulator' ) ?></li>
          <li class="text-danger text-uppercase"><?php echo __( 'Not viewed', 'enem-simulator' ) ?></li>
          <li class="text-warning text-uppercase"><?php echo __( 'Viewed', 'enem-simulator' ) ?></li>
        </ul>
        <h4 class="text-uppercase"><?php echo __( 'Knowledge areas', 'enem-simulator' ) ?></h4>
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
    <div class="simulator-result" style="display: none;">
      <div class="col-12">
        <h3 class="text-uppercase"><?php echo __( 'Simulator Result', 'enem-simulator' ) ?></h3>
        <ul class="mb-5">
          <li class="text-success text-uppercase"><?php echo __( 'Correct', 'enem-simulator' ) ?></li>
          <li class="text-danger text-uppercase"><?php echo __( 'Wrong', 'enem-simulator' ) ?></li>
          <li class="text-warning text-uppercase"><?php echo __( 'Not answered', 'enem-simulator' ) ?></li>
        </ul>
        <h4><?php echo __( 'Knowledge areas', 'enem-simulator' ) ?></h4>
        <table class="simulator-result-table table">
          <thead>
            <tr>
              <th><?php echo __( 'Description', 'enem-simulator' ) ?></th>
              <th><?php echo __( 'Rate', 'enem-simulator' ) ?></th>
              <th><?php echo __( 'Successes', 'enem-simulator' ) ?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($categories as $value) : ?>
            <tr class="<?php echo $value['slug']; ?>">
              <td><?php echo $value[ 'name' ] ?></td>
              <td class="enem-simulator-rate">0</td>
              <td class="enem-simulator-successes">0</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="col-12">
        <h4 class="text-uppercase"><?php echo __( 'Click on question and check the result', 'enem-simulator' ) ?></h4>
        <div class="simulator-result-categories">
          
        </div>
      </div>
    </div>
    <!-- /simulator-result -- -->
  </div>
</div>
<!-- enem-simulator-modal-finish -- -->
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'Back', 'enem-simulator' ) ?></button>
        <button type="button" class="btn btn-primary" id="finisish-simulator"><?php echo __( 'Finish', 'enem-simulator' ) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /enem-simulator-modal-finish -- -->
<!-- enem-simulator-modal-alter -- -->
<div class="modal enem-simulator-modal" tabindex="-1" role="dialog" id="enem-simulator-modal-alter">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo __( 'Alteration', 'enem-simulator' ) ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo __( 'Do you really want alter this question?', 'enem-simulator' ) ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="alter-simulator-dismiss" data-dismiss="modal"><?php echo __( 'No', 'enem-simulator' ) ?></button>
        <button type="button" class="btn btn-primary" id="alter-simulator" data-dismiss="modal"><?php echo __( 'Yes', 'enem-simulator' ) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /enem-simulator-modal-alter -- -->
<!-- enem-simulator-modal-register -- -->
<div class="modal enem-simulator-modal" tabindex="-1" role="dialog" id="enem-simulator-modal-register">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo __( 'Register', 'enem-simulator' ) ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="form-group">
            <label for="name"><?php echo __( 'Name', 'enem-simulator' ) ?></label>
            <input type="text" class="form-control" name="name" id="name" value="">
          </div>
          <div class="form-group">
            <label for="mail"><?php echo __( 'Mail', 'enem-simulator' ) ?></label>
            <input type="text" class="form-control" name="mail" id="mail" value="">
          </div>
          <div class="form-group">
            <label for="whatsapp"><?php echo __( 'WhatsApp', 'enem-simulator' ) ?></label>
            <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'Skip', 'enem-simulator' ) ?></button>
        <button type="submit" class="btn btn-primary" id="register-simulator" data-dismiss="modal"><?php echo __( 'Save', 'enem-simulator' ) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /enem-simulator-modal-register -- -->
<!-- enem-simulator-modal-new-simulator -- -->
<div class="modal enem-simulator-modal" tabindex="-1" role="dialog" id="enem-simulator-modal-new-simulator">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo __( 'New Simulator', 'enem-simulator' ) ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo __( 'Do you really want start a newly simulator?', 'enem-simulator' ) ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'No', 'enem-simulator' ) ?></button>
        <button type="button" class="btn btn-primary" id="new-simulator" data-dismiss="modal"><?php echo __( 'Yes', 'enem-simulator' ) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /enem-simulator-modal-new-simulator -- -->