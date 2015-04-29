<?php
namespace WPMinecraftJP\Controller;

class UserController extends Controller {
    public function __construct() {
        parent::__construct();

        add_action('login_form', array(&$this, 'actionLoginForm'));
        add_action('register_form', array(&$this, 'actionRegisterForm'));
        add_filter('login_message', array(&$this, 'filterLoginMessage'));
    }

    public function actionLoginForm() {
        $this->render('login');
    }

    public function actionRegisterForm() {
        $this->render('login');
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
}