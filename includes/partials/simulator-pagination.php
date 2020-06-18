<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="col-10 simulator-pagination" style="display: none">
  <nav>
    <ul class="pagination">
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Previous" id="previous-question">
          <span aria-hidden="true"><i class="fa fa-chevron-left"></i> <?php echo __('Previous', 'enem-simulator'); ?></span>
          <span class="sr-only"><?php _ex( 'Previous', 'Navigation to Previous Question', 'enem-simulator') ?></span>
        </a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Next" id="next-question">
          <span aria-hidden="true"><?php echo __('Next', 'enem-simulator'); ?> <i class="fa fa-chevron-right"></i></i></span>
          <span class="sr-only"><?php _ex( 'Next', 'Navigation to Next Question', 'enem-simulator') ?></span>
        </a>
      </li>
      <li class="page-item revise">
        <a class="page-link" href="#" aria-label="Next" id="enem-simulator-revise">
          <span aria-hidden="true"><?php echo __('Revise', 'enem-simulator'); ?> <i class="fa fa-check"></i></i></span>
          <span class="sr-only"><?php _ex( 'Revise', 'Revise simulator', 'enem-simulator') ?></span>
        </a>
      </li>
    </ul>
  </nav>
</div>