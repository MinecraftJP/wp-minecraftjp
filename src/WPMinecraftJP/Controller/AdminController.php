<?php
namespace WPMinecraftJP\Controller;

use WPMinecraftJP\App;

class AdminController extends Controller {
    private $group;
    public function __construct() {
        parent::__construct();
        $this->group = add_utility_page(__('MinecraftJP Settings', 'minecraftjp'), 'MinecraftJP', 'manage_options', 'minecraftjp', array(&$this, 'settings'), 'dashicons-cloud');
        add_filter('plugin_action_links', array(&$this, 'filterPluginActionLinks'), 10, 2);
        add_action('admin_notices', array(&$this, 'actionAdminNotices'));
        add_action('show_user_profile', array(&$this, 'actionShowUserProfile'));
    }

    public function settings() {
        if (!empty($_POST['updateSettings'])) {
            $fields = array('client_id', 'client_secret');
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    \WPMinecraftJP\Configure::write($field, $_POST[$field]);
                }
            }
            \WPMinecraftJP\Configure::write('avatar_enable', isset($_POST['avatar_enable']) && $_POST['avatar_enable'] == '1' ? 1 : 0);

            header('Location: ' . admin_url('?page=minecraftjp&success=' . urlencode(__('Settings saved.'))));
            exit;
        }

        $this->set('group', $this->group);
        $this->render('admin_settings');
    }

    public function actionAdminNotices() {
        $name = \WPMinecraftJP\App::NAME . '_flash';
        $messages = isset($_COOKIE[$name]) ? json_decode(stripcslashes($_COOKIE[$name]), true) : array();
        foreach ($messages as $message) {
            $class = isset($message['params']['class']) ? $message['params']['class'] : 'updated';
            print <<<_HTML_
<div class="{$class}">
<p><strong>{$message['message']}</strong></p>
</div>
_HTML_;
        }
        setcookie($name, '', time() - 3600);
    }

    public function actionShowUserProfile() {
        $this->set('isLinked', get_user_meta(get_current_user_id(), 'minecraftjp_sub', true));
        $this->render('user_profile');
    }

    public function filterPluginActionLinks($links, $file) {
        if ($file == plugin_basename(\WPMinecraftJP\App::getPluginFile())) {
            array_unshift($links, '<a href="' . admin_url('admin.php?page=minecraftjp') . '">' . __('Settings') . '</a>');
        }
        return $links;
    }
}