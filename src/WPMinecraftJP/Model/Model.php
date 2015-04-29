<?php
namespace WPMinecraftJP\Model;

class Model {
    protected $wpdb;
    public $tablePrefix;
    public $table;
    protected $schema = array();

    public function __construct() {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->tablePrefix = $wpdb->prefix;
    }

    public function save($data) {
        if (empty($data['id'])) { // insert
            if (isset($this->schema['created']) && empty($data['created'])) $data['created'] = time();

            $format = array();
            foreach ($data as $k => $v) {
                if (!isset($this->schema[$k])) {
                    unset($data[$k]);
//                } else if ($v === null) {
//                    $format[] = null;
                } else {
                    $format[] = $this->schema[$k];
                }
            }

            return $this->wpdb->insert($this->tablePrefix . $this->table, $data, $format);
        } else { // update
            $id = $data['id'];
            unset($data['id']);

            $format = array();
            foreach ($data as $k => $v) {
                if (!isset($this->schema[$k])) {
                    unset($data[$k]);
//                } else if ($v === null) {
//                    $format[] = null;
                } else {
                    $format[] = $this->schema[$k];
                }
            }
            if (count($data) == 0) return 0;
            return $this->wpdb->update($this->tablePrefix . $this->table, $data, array('id' => $id), $format);
        }
    }

    public function findAll() {
        $prepare = $this->wpdb->prepare('SELECT * FROM ' . $this->tablePrefix . $this->table);
        return $this->wpdb->get_results($prepare, ARRAY_A);
    }

    public function read($fields, $id) {
        return $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->tablePrefix . $this->table . ' WHERE id = %d', $id), ARRAY_A);
    }

    public function delete($id) {
        $sql = $this->wpdb->prepare('DELETE FROM ' . $this->tablePrefix . $this->table . ' WHERE id = %d', $id);
        return $this->wpdb->query($sql);
    }
}