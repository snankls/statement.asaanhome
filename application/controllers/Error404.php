<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
      $data['title'] = "Error 404";
      $data['page'] = "404";
      $this->load->library('Front_Layout', $data);
    }
}
?>