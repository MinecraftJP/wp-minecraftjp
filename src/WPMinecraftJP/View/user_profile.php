<table class="form-table">
    <tbody>
    <tr>
        <th><?php echo __('minecraft.jp', WPMinecraftJP\App::NAME); ?></th>
        <td>
            <?php if ($isLinked): ?>
                <a href="<?php echo home_url('/minecraftjp/unlink?token=' . wp_create_nonce('minecraftjp_unlink')); ?>" class="button button-secondary"><?php echo __('Unlink account', WPMinecraftJP\App::NAME); ?></a>
            <?php else: ?>
                <a href="<?php echo home_url('/minecraftjp/login?type=link'); ?>" class="button button-secondary"><?php echo __('Link account', WPMinecraftJP\App::NAME); ?></a>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>