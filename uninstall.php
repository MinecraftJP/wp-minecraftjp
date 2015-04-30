<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

require(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'autoload.php');

// Delete options
\WPMinecraftJP\Configure::deleteAll();

