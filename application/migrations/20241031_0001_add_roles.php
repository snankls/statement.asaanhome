<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_roles_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'role_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'role_module' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'role' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'created_by_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'updated_by_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_on' => array(
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'updated_on' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));

        $this->dbforge->add_key('role_id', TRUE);
        $this->dbforge->create_table('roles', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('roles', TRUE);
    }
}
