<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_settings extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'setting_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'data_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'data_value' => array(
                'type' => 'TEXT',
            ),
        ));

        $this->dbforge->add_key('setting_id', TRUE);
        $this->dbforge->create_table('settings', TRUE);
    }

    public function down() {
        $this->dbforge->drop_table('settings', TRUE);
    }
}
