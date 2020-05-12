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

<div class="question" style="display:none">
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