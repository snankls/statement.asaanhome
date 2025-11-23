<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_bookings_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'booking_id' => array(
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
            'inventory_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'property_type' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'unit_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'registration' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'customer_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'cnic' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
            ),
            'father_husband_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'customer_city' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'mobile' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'landline' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'email_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'mailing_address' => array(
                'type' => 'TEXT',
            ),
            'permanent_address' => array(
                'type' => 'TEXT',
            ),
            'cnic_front' => array(
                'type' => 'TEXT',
            ),
            'cnic_back' => array(
                'type' => 'TEXT',
            ),
            'image' => array(
                'type' => 'TEXT',
            ),
            'nominee_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'nominee_father_husband_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'nominee_cnic' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
            ),
            'relation' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'agency_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'agency_commission' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'booking_amount' => array(
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

        $this->dbforge->add_key('booking_id', TRUE);
        $this->dbforge->create_table('bookings', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('bookings', TRUE);
    }
}
