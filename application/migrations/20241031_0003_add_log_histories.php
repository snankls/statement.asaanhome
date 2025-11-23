<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_log_histories_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'log_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'table_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'table_value' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_by_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_on' => array(
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ),
        ));

        $this->dbforge->add_key('log_id', TRUE);
        $this->dbforge->create_table('log_histories', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('log_histories', TRUE);
    }
}
