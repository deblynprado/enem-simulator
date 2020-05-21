<?php

function enem_simulator_install() {
  global $wpdb;

  $table_name = $wpdb->prefix . 'enem_simulator_users';

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
          `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(500) NULL,
          `mail` VARCHAR(500) NULL,
          `whatsapp` VARCHAR(500) NULL,
          `created_at` DATETIME NULL,
          `updated_at` DATETIME NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}

register_activation_hook( __FILE__, array( 'enem-simulator', 'enem_simulator_install' ) );