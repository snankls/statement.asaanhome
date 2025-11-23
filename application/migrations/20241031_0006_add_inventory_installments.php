<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_inventory_installments_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'installment_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'inventory_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'amount' => array(
                'type' => 'INT',
                'null' => FALSE,
            ),
        ));

        $this->dbforge->add_key('installment_id', TRUE);
        $this->dbforge->create_table('inventory_installments', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('inventory_installments', TRUE);
    }
}
