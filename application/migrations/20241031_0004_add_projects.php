<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_projects_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'project_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'project_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'property_types' => array(
                'type' => 'TEXT',
            ),
            'area_unit' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'project_city' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'image' => array(
                'type' => 'TEXT',
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

        $this->dbforge->add_key('project_id', TRUE);
        $this->dbforge->create_table('projects', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('projects', TRUE);
    }
}
