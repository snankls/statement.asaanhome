<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SeederController extends CI_Controller {

    public function roles() {
        $this->load->library('RolesSeeder');
        $seeder = new RolesSeeder();
        $seeder->run();
    }
}
