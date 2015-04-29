<?php
namespace WPMinecraftJP\Controller;

use WPMinecraftJP\App;

class Controller {
    protected $viewVars = array();
    protected $validationErrors = array();
    protected $baseDir;
    public $uses = array();
    public $params = array();

    public function __construct()
    {
        $this->baseDir = realpath(dirname(__FILE__) . '/../');

        if (isset($_POST)) {
            $this->params['form'] = $_POST;
            if (isset($this->params['form']['data'])) {
                $this->data = $this->params['form']['data'];
            }
        }

        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $this->params['url']);
        $this->params['url'] = array_merge($_GET, $this->params['url']);


        foreach ($this->uses as $model) {
            $modelClass = 'WPMinecraftJP\\Model\\' . $model . 'Model';
            $this->$model = new $modelClass();
        }
    }

    public static function init() {
        return $instance = new static;
    }

    public function dispatch($action) {
        if (method_exists($this, $action)) {
            call_user_func(array(&$this, $action));
            return true;
        } else {
            return false;
        }
    }

    protected function set($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->viewVars[$k] = $v;
            }
        } else {
            $this->viewVars[$name] = $value;
        }
    }

    protected function invalidate($name, $message = true) {
        $this->validationErrors[$name] = $message;
    }

    public function validates()
    {
        if (empty($this->validationErrors)) {
            return true;
        } else {
            return false;
        }
    }

    public function setFlash($message, $element = 'default', $params = array()) {
        $messages = isset($_COOKIE[App::NAME . '_flash']) ? json_decode($_COOKIE[App::NAME . '_flash'], true) : array();
        $messages[] = array(
            'message' => $message,
            'element' => $element,
            'params' => $params,
        );
        setcookie(App::NAME . '_flash', json_encode($messages), time() + 3600, '/');
    }

    protected function render($name, $return = false) {
        extract($this->viewVars);

        $viewFile = $this->baseDir . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $name . '.php';

        $helper = new \WPMinecraftJP\Helper();
        foreach ($this as $key => $value) {
            if (property_exists($helper, $key)) $helper->$key = $value;
        }

        if ($return) ob_start();

        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            echo '<p>requested view was not found. (' . $name . ')</p>';
        }

        if ($return) return ob_get_clean();
    }
}