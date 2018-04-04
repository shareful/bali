<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        if ( ! $this->session->userdata('user_id'))
        {
            redirect('login', 'refresh');
        }
        // $this->privileges = $this->MUser_privileges->get_by_ref_user($this->session->userdata('user_id'));
    }

    public function index()
    {        
        $data['title'] = $this->config->item('company_name');
        $data['menu'] = 'dashboard';
        
        if(is_ajax()){
            $this->load->view($this->config->item('admin_theme').'/dashboard', $data);
            return;
        }

        $data['content'] = $this->config->item('admin_theme').'/dashboard';
        // $data['privileges'] = $this->privileges;
        $this->load->view($this->config->item('admin_theme').'/template', $data);

    }

}
