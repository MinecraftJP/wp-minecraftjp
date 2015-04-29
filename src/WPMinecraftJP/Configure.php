<?php
namespace WPMinecraftJP;

class Configure {
    protected static $config = array(
        'client_id' => '',
        'client_secret' => '',
        'avatar_enable' => true,
    );

    public static function load() {
        reset(self::$config);
        while (list($key, ) = each(self::$config)) {
            if (($value = get_option(App::NAME . '_' . $key)) !== false) {
                self::$config[$key] = $value;
            }
        }
    }

    public static function read($key = null) {
        if (empty($key)) {
            return self::$config;
        } else if (isset(self::$config[$key])) {
            return self::$config[$key];
        } else {
            return null;
        }
    }

    public static function write($key, $value = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::$config[$k] = $v;
                update_option(App::NAME . '_' . $k, $v);
            }
        } else {
            self::$config[$key] = $value;
            update_option(App::NAME . '_' . $key, $value);
        }
    }

    public static function out($key = null) {
        echo htmlspecialchars(Configure::read($key));
    }
}