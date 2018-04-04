<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	/**
	 * Super Admin login manu / apps
	 */
	// protected $sadmin_apps = array(
	// 	'Account' => array(
	// 		'My Account' => 'myaccount'
	// 	),
	// 	'Administration' => array(
	// 		'Staff Information' => 'staff',
	// 		'Participents' => 'participent',
	// 		'Organizations' => 'organization',
	// 		'Admin Users' => 'adminusers',			
	// 		'Org Users' => 'users',			
	// 		'Upcoming Events' => 'events',			
	// 		'Event Reports' => 'events/reports',
	// 	),
	// 	'Settings' => array(
	// 		'Location' => 'location',
	// 		// 'Districts' => 'district',
	// 		// 'Upazilas' => 'upazilla',
	// 		// 'Unions' => 'union',
	// 		'Projects' => 'project',
	// 		'Stackholder Category' => 'stackholder',
	// 		'Unit Information' => 'unitinfo',
	// 		'Event Type' => 'eventtype',
	// 		'Objective / Outcome' => 'objective',			
	// 		'Participent Group' => 'peoplegroup',
	// 		'Activity / Subactivity' => 'activity',
	// 		'School' => 'school',
	// 	),
	// );

	/**
	 * Organization Admin login manu / apps
	 */
	// protected $orgadmin_apps = array(
	// 	'Account' => array(
	// 		'My Account' => 'myaccount'
	// 	),
	// 	'Administration' => array(
	// 		'Organization Info' => 'orginfo',			
	// 		'Users' => 'users',			
	// 		'Activity' => 'activity',			
	// 		'Participents' => 'participent',			
	// 		'Upcoming Events' => 'events',			
	// 		'Event Reports' => 'events/reports',			
	// 		// 'Activity Report' => 'activityreport',			
	// 	),
	// );

	/**
	 * Organization Admin login manu / apps
	 */
	// protected $user_apps = array(
	// 	'Account' => array(
	// 		'My Accounts' => 'myaccount'
	// 	),
	// 	'Administration' => array(
	// 		// 'Organization' => 'organization',			
	// 		// 'Users' => 'users',			
	// 		'Activity' => 'activity',			
	// 		// 'Activity Report' => 'activityreport',			
	// 		'Participents' => 'participent',			
	// 		'Events' => 'events',			
	// 		// 'Reports' => 'events/reports',			
	// 	),
	// );


	public function __construct() {
        parent::__construct();       
    }
	
	public function getUserId () {
		return $this->session->userdata('user_id');
	}
		
	public function isValidDate($date, $format = 'Y-m-d H:i') {
	    $version = explode('.', phpversion());
	    if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
	        $d = DateTime::createFromFormat($format, $date);
	    } else {
	        $d = new DateTime(date($format, strtotime($date)));
	    }
	    return $d && $d->format($format) == $date;
	}
	
	public function assignPostData ( &$objectmdl, $object=null ) {
		if (!is_callable(array($objectmdl,'set_value'))) {
			// exit("$objectmdl->set_value() not defined!");
			return false;
		}
		$array_ele = (is_null($object) ? $_POST : $object);
		foreach ($array_ele as $key => $val) {
			if ($val == '') 
				$val = null;
			if (strpos($key, 'date') !== FALSE && $val != null) {
				$val = custom_standard_date(date_human_to_unix($val), 'MYSQL');
			}
			// Update the field value 
			$objectmdl->set_value($key, $val);
		}		
	}
	
	public function assignObject ( $object, $debug=FALSE ) {
		if (is_array($object) OR is_object($object)) {
			foreach($object as $key => $val) {
				$this->tpl->assign($key, $val);
				if ($debug) echo $key . " => " .  $val . "<br>";
			}
		}
	}
		
	/*public function assignMain() {
		if ($this->session->userdata('logged') === TRUE) { // user logged
			switch ($this->session->userdata('user_type')) {
				case 'sadmin':
				case 'admin':
					$this->tpl->assign("menu", $this->sadmin_apps);
					$this->tpl->assign("home_app", "myaccount");
					break;				
				case 'orgadmin':
					$this->tpl->assign("menu", $this->orgadmin_apps);
					$this->tpl->assign("home_app", "myaccount");
					
					break;				
				case 'user':
					$this->tpl->assign("menu", $this->user_apps);
					$this->tpl->assign("home_app", "myaccount");

					break;				
				default:
					break;
			}
			$user = $this->user->get_by('user_id', $this->session->userdata('user_id'));
			$this->tpl->assign("user", $user);		
		}
	}*/
        
		    
}
