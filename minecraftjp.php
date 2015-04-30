<?php
/**
Plugin Name: MinecraftJP
Plugin URI: https://minecraft.jp
Description: A WordPress plugin that allows you to login with minecraft.jp account
Author: Japan Minecraft Network
Author URI: https://minecraft.jp
Version: 1.0.0
 */
if (!defined('ABSPATH')) {
    die();
}

require(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'autoload.php');
require(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'vendor'  . DIRECTORY_SEPARATOR . 'autoload.php');

add_action('plugins_loaded', array('WPMinecraftJP\App', 'init'), 0, 0);

register_activation_hook(__FILE__, array('WPMinecraftJP\App', 'activate'));