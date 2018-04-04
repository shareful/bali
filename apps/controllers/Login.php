<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct()
    {
        parent::__construct();        
    }

    public function index()
    {
        if ( $this->session->userdata('user_id'))
        {
            redirect('', 'refresh');
        }
        
        if ($this->input->post('username'))
        {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == TRUE)
            {
                $username = $this->input->post('username');
                $pw = $this->input->post('password');
                $this->user->login($username, $pw);

                if ($this->session->userdata('user_id'))
                {
                    redirect('', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User name and Password mismatch.');
                    redirect('login', 'refresh');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'User name and password field can\'t be blank.');
                redirect('login', 'refresh');
            }
        }
        else
        {
            $data['title'] = $this->config->item('company_name');
            $data['menu'] = 'login';
            $data['content'] = 'login';
            $this->load->view($this->config->item('admin_theme').'/login', $data);
        }
    }

    public function logout()
    {
        $data = array();
        $data['user_id'] = $this->session->userdata('user_id');
        $data['user_name'] = $this->session->userdata('user_name');
        // $data['user_email'] = $this->session->userdata('user_email');
        $data['user_type'] = $this->session->userdata('user_type');
        $data['company_id'] = $this->session->userdata('company_id');
        $data['user_company'] = $this->session->userdata('user_company');
        $data['company_name'] = $this->session->userdata('company_name');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }

    /*public function forgot_password()
    {
        if ($this->input->post('email'))
        {
            //send reset mail
            $this->form_validation->set_rules('email', 'Username', 'trim|required|valid_email|xss_clean');

            if ($this->form_validation->run() == TRUE)
            {
                $user = $this->MUsers->get_by_email(trim($this->input->post('email')));
                $company = $this->MCompanies->get_by_id($user['company_id']);
                // String helper to generate random code
                $this->load->helper('string');
                $code = random_string('alnum', 32);
                $this->MUsers->update_code($user['id'], $code);

                // Send mail to user's email address
                $config = array(
                    'mailtype' => 'html'
                    );
                $this->email->set_newline("\r\n");
                $this->email->initialize($config);

                $this->email->from($company['email'], $company['name']);
                $this->email->to(trim($this->input->post('email')));
                $this->email->subject('Reset your password');
                $data['code'] = $code;
                $message = $this->load->view('templates/forgot_password', $data, true);
                $this->email->message($message);
                if ($this->email->send())
                {
                    $this->session->set_flashdata('info', 'An email sent to your address with password reset link. Please check your mail and reset your password from that link.');
                }
                else
                {
                    $this->session->set_flashdata('error', 'An Unexpected Problem Occurred, Please Try Again Later.');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Please re-check your email address.');
            }

            redirect('', 'refresh');
        }
        else
        {
            $data['title'] = 'Forget Password - '.$this->config->item('company_name');
            $data['menu'] = 'forgot';
            $data['content'] = 'forgot';
            $this->load->view('forgot_password', $data);
        }
    }

    public function reset_password($code)
    {
        $user = $this->MUsers->get_by_code($code);
        if (isset($user['id']))
        {
            if ($this->input->post())
            {
                $this->form_validation->set_rules('password', 'New Password', 'trim|required|xss_clean|matches[passconf]');
                $this->form_validation->set_rules('passconf', 'Confirm Password', 'required');

                if ($this->form_validation->run() == TRUE)
                {
                    $this->MUsers->update_password($user['id']);
                    $this->session->set_flashdata('success', 'Password reset successfully, you can login with your new password now.');
                    redirect('', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Please re-check your password and confirm password filed.');
                    redirect('', 'refresh');
                }
            }
            else
            {
                $data['title'] = 'Reset Password - '.$this->config->item('company_name');
                $data['menu'] = 'reset';
                $data['content'] = 'reset';
                $data['code'] = $code;
                $this->load->view('reset_password', $data);
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Invalid code, please re-send the password re-set mail and try again.');
            redirect('', 'refresh');
        }
    }*/

}
