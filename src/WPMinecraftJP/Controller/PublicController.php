<?php
namespace WPMinecraftJP\Controller;

use WPMinecraftJP\App;
use WPMinecraftJP\Configure;

class PublicController extends Controller {
    public $uses = array('User');

    public function login() {
        $minecraftjp = $this->getMinecraftJP();

        $_SESSION['auth_type'] = !empty($_GET['type']) ? $_GET['type'] : 'login';

        $minecraftjp->logout();
        $url = $minecraftjp->getLoginUrl(array(
            'scope' => 'openid profile email',
            'redirect_uri' => home_url('/minecraftjp/doLogin'),
        ));

        wp_redirect($url, 302);
    }

    public function doLogin() {
        $minecraftjp = $this->getMinecraftJP();

        $authType = !empty($_SESSION['auth_type']) ? $_SESSION['auth_type'] : 'login';
        $redirectTo = $_SESSION['redirect_to'];
        $mcjpUser = $minecraftjp->getUser();

        if ($authType == 'link') {
            if (!empty($mcjpUser)) {
                $userId = get_current_user_id();
                $existsUserId = $this->User->getUserIdBySub($mcjpUser['sub']);
                if (!empty($existsUserId) && $existsUserId != $userId) {
                    $this->setFlash(__('This account is already linked.', App::NAME), 'default', array('class' => 'error'));
                } else {
                    update_user_meta($userId, 'minecraftjp_sub', $mcjpUser['sub']);
                    update_user_meta($userId, 'minecraftjp_uuid', $mcjpUser['uuid']);
                    $this->setFlash(__('Minecraft.jp account linked successfully.', App::NAME));
                }
            } else {
                $this->setFlash(__('Authorization denied.', App::NAME), 'default', array('class' => 'error'));
            }
            wp_safe_redirect(admin_url('profile.php'));
       } else {
            if (!empty($mcjpUser)) {
                $userId = $this->User->getUserIdBySub($mcjpUser['sub']);
                if (!$userId) {
                    $result = wp_create_user($mcjpUser['preferred_username'] . '@minecraft.jp', wp_generate_password(), $mcjpUser['email']);
                    if (is_wp_error($result)) {
                        $this->setFlash(__('username or email is already taken.', App::NAME), 'default', array('class' => 'error'));
                        wp_safe_redirect(site_url('wp-login.php'));
                        exit;
                    } else {
                        $userId = $result;
                        wp_update_user(array(
                            'ID' => $userId,
                            'user_url' => !empty($mcjpUser['website']) ? $mcjpUser['website'] : $mcjpUser['profile'],
                            'display_name' => $mcjpUser['preferred_username'],
                        ));
                        update_user_meta($userId, 'nickname', $mcjpUser['preferred_username']);
                        update_user_meta($userId, 'minecraftjp_sub', $mcjpUser['sub']);
                        update_user_meta($userId, 'minecraftjp_uuid', $mcjpUser['uuid']);
                    }
                }
                wp_set_auth_cookie($userId, true);
                $user = get_user_by('id', $userId);
                if ((empty($redirectTo) || $redirectTo == 'wp-admin/' || $redirectTo == admin_url())) {
                    if (is_multisite() && !get_active_blog_for_user($userId) && !is_super_admin($userId)) {
                        $redirectTo = user_admin_url();
                    } else {
                        if (is_multisite() && !$user->has_cap('read')) {
                            $redirectTo = get_dashboard_url($userId);
                        } else {
                            if (!$user->has_cap('edit_posts')) {
                                $redirectTo = admin_url('profile.php');
                            }
                        }
                    }
                }
                wp_safe_redirect($redirectTo);
                exit;
            } else {
                $this->setFlash(__('Authorization denied.', App::NAME), 'default', array('class' => 'error'));
                wp_safe_redirect(site_url('wp-login.php'));
                exit;
            }
        }
    }

    public function unlink() {
        $userId = get_current_user_id();
        if (!empty($userId)) {
            delete_user_meta($userId, 'minecraftjp_sub');
            delete_user_meta($userId, 'minecraftjp_uuid');
            $this->setFlash(__('Minecraft.jp account unlinked successfully.', App::NAME));
        }
        wp_safe_redirect(admin_url('profile.php'));
    }

    private function getMinecraftJP() {
        $clientId = Configure::read('client_id');
        $clientSecret = Configure::read('client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            echo 'Not configured.';
            exit;
        }

        return new \MinecraftJP(array(
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
        ));
    }
}