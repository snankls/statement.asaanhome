<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_inventories_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'inventory_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'project_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'property_type' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'floor_block' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'unit_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'payment_plan' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'unit_size' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'unit_category' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'total_price' => array(
                'type' => 'INT',
                'null' => FALSE,
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'comment' => 'Available 1, Booked 2',
            ),
            'created_on' => array(
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'updated_on' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
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
        ));

        $this->dbforge->add_key('inventory_id', TRUE);
        $this->dbforge->create_table('inventories', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('inventories', TRUE);
    }
}
