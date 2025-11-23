<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RolesSeeder extends CI_Controller {

    public function index()
    {
        $this->load->database();

        // Insert default roles
        $data = [
            [
                'role_id' => 1,
                'role_module' => 'All',
                'role' => 'Admin',
                'created_by_id' => 0,
                'updated_by_id' => 0,
                'created_on' => '2022-04-23 01:49:01',
                'updated_on' => '0000-00-00 00:00:00',
            ],
            [
                'role_id' => 2,
                'role_module' => 'Projects & Finance',
                'role' => 'User',
                'created_by_id' => 0,
                'updated_by_id' => 0,
                'created_on' => '2022-04-23 01:49:01',
                'updated_on' => '0000-00-00 00:00:00',
            ],
            [
                'role_id' => 6,
                'role_module' => 'Projects & Finance',
                'role' => 'Viewer',
                'created_by_id' => 0,
                'updated_by_id' => 0,
                'created_on' => '2024-04-01 16:24:46',
                'updated_on' => '0000-00-00 00:00:00',
            ],
            [
                'role_id' => 7,
                'role_module' => 'CRM',
                'role' => 'Manager',
                'created_by_id' => 0,
                'updated_by_id' => 0,
                'created_on' => '2024-10-04 04:41:04',
                'updated_on' => '0000-00-00 00:00:00',
            ],
            [
                'role_id' => 8,
                'role_module' => 'CRM',
                'role' => 'Individual',
                'created_by_id' => 0,
                'updated_by_id' => 0,
                'created_on' => '2024-10-04 04:41:04',
                'updated_on' => '0000-00-00 00:00:00',
            ],
        ];

        // Insert each role into the roles table
        foreach ($data as $role) {
            $this->db->insert('roles', $role);
        }

        echo "Default roles inserted successfully!";
    }
}
