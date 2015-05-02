<?php
namespace WPMinecraftJP\Controller;

use WPMinecraftJP\App;

class UserController extends Controller {
    public function __construct() {
        parent::__construct();

        add_action('login_form', array(&$this, 'actionLoginForm'));
        add_action('register_form', array(&$this, 'actionRegisterForm'));
        add_action('login_head', array(&$this, 'actionLoginHead'));
        add_filter('login_message', array(&$this, 'filterLoginMessage'), 10, 1);
        add_filter('get_avatar', array(&$this, 'filterGetAvatar'), 10, 5);
    }

    public function actionLoginForm() {
        $loginUrl = home_url('/minecraftjp/login');
        if (!empty($_GET['redirect_to'])) {
            $loginUrl .= '?redirect_to=' . $_GET['redirect_to'];
        }
        $this->set(compact('loginUrl'));
        $this->render('login');
    }

    public function actionRegisterForm() {
        $loginUrl = home_url('/minecraftjp/login');
        if (!empty($_GET['redirect_to'])) {
            $loginUrl .= '?redirect_to=' . $_GET['redirect_to'];
        }
        $this->set(compact('loginUrl'));
        $this->render('login');
    }

    public function actionLoginHead() {
        printf('<link rel="stylesheet" href="%s" type="text/css" media="all" />', plugins_url('css/login.css', App::getPluginFile()));
    }

    public function filterLoginMessage($loginMessage) {
        $name = \WPMinecraftJP\App::NAME . '_flash';
        $messages = isset($_COOKIE[$name]) ? json_decode(stripcslashes($_COOKIE[$name]), true) : array();
        setcookie($name, '', time() - 3600, '/');

        foreach ($messages as $message) {
            $class = isset($message['params']['class']) ? $message['params']['class'] : 'updated';
            $loginMessage .= <<<_HTML_
<div id="login_error">
<p><strong>{$message['message']}</strong></p>
</div>
_HTML_;
        }

        return $loginMessage;
    }

    public function filterGetAvatar($avatar, $idOrEmail, $size, $default, $alt) {
        if (is_numeric($idOrEmail) && \WPMinecraftJP\Configure::read('avatar_enable')) {
            $username = get_user_meta($idOrEmail, 'minecraftjp_username', true);
            if ($username) {
                $url = 'https://avatar.minecraft.jp/' . $username . '/minecraft/' . $size . '.png';
                $avatar = sprintf('<img alt="%s" src="%s" class="avatar avatar-%d photo" height="%d" width="%d" />', esc_attr($alt), $url, $size, $size, $size);
            }
        }

        return $avatar;
    }
}