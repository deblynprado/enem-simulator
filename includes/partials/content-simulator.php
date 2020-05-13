<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="contaner">
  <div class="row content-simulator">
    <div class="col-12">
        <h2><?php echo __( 'Enem Simulator', 'enem-simulator' ) ?></h2>
        <p> <?php the_field( 'initial_message', 'option' ) ?> </p>
        <p> <?php echo __('Choose a desired category to start') ?> </p>
    </div>
    <div class="col-12">
      <select class="form-control form-control-lg" name="question_category" id="question_category">
        <?php
          $categories = get_field( 'question_categories', 'option' ); 
          foreach ($categories as $value) : ?>
            <option value="<?php echo $value[ 'question_category' ]->term_id ?>"><?php echo $value[ 'question_category' ]->name ?></option>
          <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" id="start-simulator"><?php echo __( 'Start Simulator', 'enem-simulator' ) ?></button>
    </div>
  </div>
  <div class="row">
    <form name="content-question-form" id="content-question-form" enctype="multipart/form-data" method="post">
      <div class="col-12 content-question">
        
      </div>
    </form>
    <div class="col-12 nav" style="display: none">
      <nav>
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous" id="previous-question">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next" id="next-question">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div class="row end-simulator" style="display: none">
    <div class="col-12">
      <button  type="submit" class="btn btn-primary" id="end-simulator"><?php echo __( 'End Simulator', 'enem-simulator' ) ?></button>
    </div>
  </div>
</div>