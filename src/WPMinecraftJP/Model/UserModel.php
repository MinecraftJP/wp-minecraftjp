<?php
namespace WPMinecraftJP\Model;

class UserModel extends Model {
    public $table = 'users';

    public function getUserIdBySub($sub) {
        $sql = $this->wpdb->prepare(
            'SELECT ID FROM ' . $this->tablePrefix . $this->table . ' AS `User` LEFT JOIN ' . $this->tablePrefix . 'usermeta AS `UserMeta` ON User.ID = UserMeta.user_id AND UserMeta.meta_key = "minecraftjp_sub" WHERE meta_value = %s', $sub);
        $row = $this->wpdb->get_row($sql, ARRAY_A);
        if (empty($row)) {
            return false;
        } else {
            return $row['ID'];
        }
    }
}