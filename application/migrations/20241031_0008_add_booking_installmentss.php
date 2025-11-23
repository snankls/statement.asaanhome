<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_booking_amounts_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'booking_amount_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'booking_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'inventory_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'serial' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'amount_date' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'amount' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'payment_method' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'reference' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'proof_image' => array(
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

        $this->dbforge->add_key('booking_amount_id', TRUE);
        $this->dbforge->create_table('booking_amounts', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('booking_amounts', TRUE);
    }
}
