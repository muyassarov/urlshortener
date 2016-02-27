<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 2/27/16
 * Time: 12:53 PM
 */
class Redirect extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Urls');
    }

    public function index()
    {
        $short_url = $this->uri->segment(1);
        $result = $this->Urls->find_short_url($short_url);
        if (is_object($result))
        {
            $this->Urls->increase_counter($result->id, $result->counter);
            redirect($result->url_long, 'refresh');
        }
        else
        {
            $this->load->view('common/header');
            $this->load->view('error');
            $this->load->view('common/footer');
        }
    }
}