<?php
namespace WPMinecraftJP;

class Helper {
    public $validationErrors = array();
    public $params = array();
    public $data = array();
    public $viewVars = array();
    public $locales = array();


    public function __($key) {
        if (!empty($this->viewVars['lang']) && !empty($this->locales[$this->viewVars['lang']][$key])) {
            return $this->locales[$this->viewVars['lang']][$key];
        } else {
            return $key;
        }
    }

    public function url($url = null, $full = null) {
        return Router::url($url, $full);
    }

    public function value($field) {
        list($model, $field) = explode('.', $field);

        if (empty($field) && isset($this->data[$model])) {
            return $this->data[$model];
        } else if (isset($this->data[$model][$field])) {
            return $this->data[$model][$field];
        } else {
            return null;
        }
    }

    public function error($fieldName, $text = null, $options = array()) {
        if (!isset($this->validationErrors[$fieldName])) return null;

        if (!empty($text)) {
            $error = $text;
        } else if (!empty($this->validationErrors[$fieldName])) {
            $error = $this->validationErrors[$fieldName];
        } else {
            $error = sprintf('Error in field %s', $fieldName);
        }

        if (isset($options['escape']) && $options['escape']) $error = h($error);

        if (isset($options['wrap']) && $options['wrap'] == false) {
            return $error;
        } else {
            return '<div class="error">' . $error . '</div>';
        }
    }

    public function flash() {
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

    public function output($text)
    {
        return $text;
    }
}