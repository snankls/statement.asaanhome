<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('file');
        $this->CI->load->library('migration');
    }

    public function latest() {
        // Run the migrations
        if ($this->CI->migration->latest() === FALSE) {
            show_error($this->CI->migration->error_string());
        } else {
            echo "Migrations run successfully!";
        }
    }
}
