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

<div>
	<h4><?php the_title() ?></h4>
    <p>
        <?php the_content() ?>
    </p>
    <div>
        <?php if( $fields ): ?>
            <ul>
            <?php foreach( $fields as $name => $value ): ?>
                <li><?php echo $value['text_answer'] ?></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
	</div>
</div>