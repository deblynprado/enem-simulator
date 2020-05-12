<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Enem Simulator
*
* @package           EnemSimulator
* @author            Deblyn Prado, Walfrido Oliveira
* @copyright         2019 Deblyn Prado, Walfrido Oliveira
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Enem Simulator
* Plugin URI:        https://github.com/deblynprado/enem-simulator
* Description:       Allows your users to generate random tests and check their knowledge.
* Version:           1.0.0
* Requires at least: 5.0
* Requires PHP:      7.2
* Author:            Deblyn Prado, Walfrido Oliveira
* Author URI:        https://example.com
* Text Domain:       enem-simulator
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

?>

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
    <div class="col-12 content-question">
    </div>
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
</div>