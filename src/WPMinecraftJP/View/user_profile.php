<table class="form-table">
    <tbody>
    <tr>
        <th><?php echo __('minecraft.jp', WPMinecraftJP\App::NAME); ?></th>
        <td>
            <?php if ($isLinked): ?>
                <p class="description"><?php echo __('Minecraft Username', WPMinecraftJP\App::NAME); ?>: <?php echo get_user_meta(get_current_user_id(), 'minecraftjp_username', true); ?></p>

                <p><a href="<?php echo home_url('/minecraftjp/unlink?token=' . wp_create_nonce('minecraftjp_unlink')); ?>" class="button button-secondary"><?php echo __('Unlink account', WPMinecraftJP\App::NAME); ?></a></p>
            <?php else: ?>
                <p><a href="<?php echo home_url('/minecraftjp/login?type=link'); ?>" class="button button-secondary"><?php echo __('Link account', WPMinecraftJP\App::NAME); ?></a></p>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>