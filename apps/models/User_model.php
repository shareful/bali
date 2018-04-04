<?php

/**
 * This is Users Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class User_model extends My_Model {
	protected $_table = 'users';
	protected $primary_key = 'user_id';
	protected $protected_atributes = array('user_id');
	protected $belongs_to = array(
			'company'=>array(
				'model'=>'company_model',
				'primary_key'=>'company_id',
			)
		);

	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'user_type', 
               'label' => 'User Type',
               'rules' => 'required|callback_verify_user_type' ),
        array( 'field' => 'username', 
               'label' => 'User Login Id',
               'rules' => 'required' ),
        // array( 'field' => 'password',
        //        'label' => 'User Password',
        //        'rules' => 'required|min_length[6]' ),
        // array( 'field' => 'password_confirmation',
        //        'label' => 'Confirm Password',
        //        'rules' => 'required|matches[password]' ),
        // array( 'field' => 'company_id',
        //        'label' => 'Company',
        //        'rules' => 'required' ),
        array( 'field' => 'name',
               'label' => 'User Full Name',
               'rules' => 'required' ),
        /*array( 'field' => 'status',
               'label' => 'User Status',
               'rules' => 'required' ),*/
    );
	
	/**
	 * Contains Table fields
	 */
	private $field;
	
	public function __construct() {
		parent::__construct();
		// Initializing table fields with null
		$this->field = new stdClass;
		$this->field->user_id = null;
		$this->field->company_id = null;
		$this->field->username = null;
		$this->field->password = null;
		$this->field->name = null;
		$this->field->user_type = null;
		$this->field->status = null;
		$this->field->created = date('Y-m-d H:i:s', time());
		$this->field->created_by = $this->session->userdata('user_id');
		$this->field->modified = date('Y-m-d H:i:s', time());
		$this->field->modified_by = $this->session->userdata('user_id');
	}	

	/**
	 * Set Table field/column with value
	 * @access public
	 * @param String
	 * @param String
	 * @return Bolean
	 */
	public function set_value($key, $val) {
		if (array_key_exists($key, get_object_vars($this->field))) {
			// echo "{$key}={$val}<br>";
			$this->field->$key = $val;
			return true;
		}else{
			// echo "{$key}=Not Available<br>";
			return false;
		}
	}


	/**
	 * Get Table field/column value
	 * @access public
	 * @param String
	 * @return mixed
	 */
	public function get_value($key) {
		if (array_key_exists($key, get_object_vars($this->field))) {
			// echo "{$key}={$val}<br>";
			return $this->field->$key;
		}else{
			return false;
		}
	}
	

	/**
	 * parent::insert() method Overriding 
	 * @access public
	 * @param array
	 * @param bolean
	 * @return int|bolean
	 */
	public function insert($data=null, $skip_validation = false) {
		if (is_null($data)) {
			$data = (array) $this->field;
			// force to skip validation
			$skip_validation = true;
		}
		if ($data['password'] != "") {
			$data['password'] = do_hash($data['password']);
		}		
		return parent::insert($data,$skip_validation);		
	}
	
	/**
	 * parent::update() method Overriding 
	 * @access public
	 * @param array
	 * @param bolean
	 * @return int|bolean
	 */
	public function update($id, $data=null, $skip_validation = false) {
		if (is_null($data)) {
			$data = (array) $this->field;
			// force to skip validation
			$skip_validation = true;
		}
		if (isset($data['password']) AND $data['password'] != "") {
			$data['password'] = do_hash($data['password']);
		}		
		$data['modified'] = date('Y-m-d H:i:s', time());
		$data['modified_by'] = $this->session->userdata('user_id');
		return parent::update($id, $data, $skip_validation);		
	}

	/**
	 * Verify User type form validation callback
	 * @access public
	 * @param string
	 * @param bolean
	 */
	public function verify_user_type($user_type) {
		if (!in_array($user_type, array('sadmin','admin','user'))) {
			$this->form_validation->set_message('verify_user_type', "The user type is not valid.");
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * user login verify function using phone and password
	 * @access public
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function login_verify($username, $password) {
		$password = do_hash($password);
		$where = array();
		$where['status'] = 'Active';
		$where['username'] = $username;
		$where['password'] = $password;
		$user = parent::get_by($where);
		if (empty($user)) {
			return false;
		} else {
			return true;
		}

		// echo $this->db->last_query();
		// return $user;
	}

	/**
	 * user login verify function using phone and password
	 * @access public
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function login($username, $password) {	
		$password = do_hash($password);	
		$where = array();
		$where['status'] = 'Active';
		$where['username'] = $username;
		$where['password'] = $password;
		$user = parent::with('company')->get_by($where);
		if (empty($user)) {
			return false;
		} else {
			$data['user_id'] = $user->user_id;
			$data['username'] = $user->username;
			$data['user_name'] = $user->name;
			// $data['user_email'] = $user->email;
			$data['user_type'] = $user->user_type;
			$data['user_company'] = $user->company_id;
			$data['company_id'] = $user->company_id;
			if (isset($user->company->company_name)) {
				$data['company_name'] = $user->company->company_name;
			}

			// if ($company['logo'] != '')
			// {
			// 	$data['company_logo'] = 'uploads/companies/' . $company['logo'];
			// }
			// else
			// {
			// 	$data['company_logo'] = 'assets/'.$this->config->item('theme').'/img/logo.png';
			// }
			// $data['currency_symbol_position'] = $company['currency_symbol_position'];
			// $currency = $this->MCurrencies->get_by_id($company['currency_id']);
			// $data['currency_name'] = $currency['shortname'];
			// $data['currency_symbol'] = $currency['symbol'];

			$this->session->set_userdata($data);
			return true;
		}

		// echo $this->db->last_query();
		// return $user;
	}

	/**
	 * user old password check using user_id and password
	 * @access public
	 * @param integer
	 * @param string
	 * @return user object
	 */
	public function check_old_password ( $user_id, $password ) {
		$where = array();
		$where['user_id'] = $user_id;
		$where['password'] = do_hash($password);
		$user = parent::get_by($where);
		return $user;
	}
		
	/**
	 * Get all list of projects which are in same company  
	 * @access public
	 * @return array
	 */
	public function get_list_all(){
		$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$result = parent::order_by('name', 'asc')->get_many_by($where);
		return $result;
	}

}