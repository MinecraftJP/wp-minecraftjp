<div class="wrap">
    <header>
        <h2><?php echo __('MinecraftJP Settings', WPMinecraftJP\App::NAME); ?></h2>
    </header>

    <?php if (!empty($success)) { ?>
        <div class="updated settings-error">
            <p><strong><?php echo htmlspecialchars($success); ?></strong></p>
        </div>
    <?php } else if (!empty($error)) { ?>
        <div class="error settings-error">
            <p><strong><?php echo htmlspecialchars($error); ?></strong></p>
        </div>
    <?php } ?>

    <form action="<?php echo admin_url('admin.php?page=minecraftjp'); ?>" method="post">
        <input type="hidden" name="token" value="<?php echo wp_create_nonce('minecraftjp_settings'); ?>"/>

        <h3 class="clear"><?php echo __('Application Settings', WPMinecraftJP\App::NAME); ?></h3>

        <p><?php printf(__('You need to create a App in minecraft.jp App Console. <a href="%s" target="__blank">Click here</a>', WPMinecraftJP\App::NAME), 'https://minecraft.jp/developer/apps'); ?></p>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php echo __('Application Type', WPMinecraftJP\App::NAME); ?></th>
                <td><code><?php echo __('Service Account', WPMinecraftJP\App::NAME); ?></code></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Client ID', WPMinecraftJP\App::NAME); ?></th>
                <td><input name="client_id" type="text" value="<?php WPMinecraftJP\Configure::out('client_id'); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Client Secret', WPMinecraftJP\App::NAME); ?></th>
                <td><input name="client_secret" type="text" value="<?php WPMinecraftJP\Configure::out('client_secret'); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Callback URI', WPMinecraftJP\App::NAME); ?></th>
                <td><code><?php echo home_url('/minecraftjp/doLogin'); ?></code></td>
            </tr>
            </tbody>
        </table>

        <h3 class="clear"><?php echo __('Avatar Settings', WPMinecraftJP\App::NAME); ?></h3>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php echo __('Avatar Display'); ?></th>
                <td><input type="checkbox" id="avatarEnable" name="avatar_enable" value="1" <?php echo (\WPMinecraftJP\Configure::read('avatar_enable') == 1 ? ' checked="checked"' : ''); ?>/><label for="avatarEnable"><?php echo __('Show Minecraft Avatar', \WPMinecraftJP\App::NAME); ?></label></td>
            </tr>
            </tbody>
        </table>

        <h3 class="clear"><?php echo __('User Settings', WPMinecraftJP\App::NAME); ?></h3>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php echo __('Membership'); ?></th>
                <td><input type="checkbox" id="avatarEnable" name="force_users_can_register" value="1" <?php echo (\WPMinecraftJP\Configure::read('avatar_enable') == 1 ? ' checked="checked"' : ''); ?>/><label for="avatarEnable"><?php echo __('Force users can register', \WPMinecraftJP\App::NAME); ?></label></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Username Suffix', WPMinecraftJP\App::NAME); ?></th>
                <td>
                    <input name="username_suffix" type="text" value="<?php WPMinecraftJP\Configure::out('username_suffix'); ?>" class="regular-text">
                    <p class="description"><?php echo __('You can add a suffix to username in order to avoid conflicts with existing username. (e.g. "@minecraft.jp")', WPMinecraftJP\App::NAME); ?></p>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="submit" value="<?php echo __('Save Changes'); ?>" name="updateSettings" class="button button-primary"/>
    </form>
</div>
