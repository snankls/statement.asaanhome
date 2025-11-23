<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'fullname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_module' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'project_id' => array(
                'type' => 'TEXT',
            ),
            'team_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'mobile' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'address' => array(
                'type' => 'TEXT',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'image' => array(
                'type' => 'TEXT',
            ),
            'role_id' => array(
                'type' => 'TINYINT',
                'constraint' => '2',
                'unsigned' => TRUE,
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '2',
                'unsigned' => TRUE,
                'comment' => 'Enable, Disable',
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

        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->create_table('users', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('users', TRUE);
    }
}
