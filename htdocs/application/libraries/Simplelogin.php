<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

//Include stronger password-hashing
include_once("Password.php");

/**
 * Simplelogin Class
 * Author: Anthony Graddy
 *
 * Makes authentication simple
 *
 * Simplelogin is released to the public domain
 * (use it however you want to)
 *
 * Simplelogin expects this database setup
 * (if you are not using this setup you may
 * need to do some tweaking)
 */
class Simplelogin
{

	var $CI;
	var $user_table = 'user';

	function Simplelogin()
	{
		// get_instance does not work well in PHP 4
		// you end up with two instances
		// of the CI object and missing data
		// when you call get_instance in the constructor
		//$this->CI =& get_instance();
	}

	/**
	 * Create a user account
	 *
	 * @access    public
	 *
	 * @param           string
	 * @param    string
	 * @param    bool
	 *
	 * @return    bool
	 */
	function create($user = '', $password = '', $level = 1, $auto_login = TRUE)
	{
		//Put here for PHP 4 users
		$this->CI = &get_instance();
		//Make sure account info was sent
		if ($user === '' OR $password === '') {
			return FALSE;
		}
		//Check against user table
		$this->CI->db->where('name', $user);
		$query = $this->CI->db->getwhere($this->user_table);
		if ($query->num_rows() > 0) {
			//name already exists
			return FALSE;
		} else {
			//Encrypt password
			$password = password_hash($password, PASSWORD_BCRYPT);
			//Insert account into the database
			$data = array(
				'name'     => $user,
				'password' => $password,
			);
			$this->CI->db->set($data);
			if (!$this->CI->db->insert($this->user_table)) {
				//There was a problem!
				return FALSE;
			}
			$user_id = $this->CI->db->insert_id();
			//Automatically login to created account
			if ($auto_login) {
				//Destroy old session
				$this->CI->session->sess_destroy();
				//Create a fresh, brand new session
				//$this->CI->session->sess_create();
				//Set session data
				$this->CI->session->set_userdata(array('id' => $user_id, 'name' => $user));
				//Set logged_in to true
				$this->CI->session->set_userdata(array('logged_in' => TRUE));
			}

			//Login was successful
			return TRUE;
		}
	}

	/**
	 * Delete user
	 *
	 * @access    public
	 *
	 * @param integer
	 *
	 * @return    bool
	 */
	function delete($user_id)
	{
		//Put here for PHP 4 users
		$this->CI = &get_instance();
		if (!is_numeric($user_id)) {
			//There was a problem
			return FALSE;
		}
		if ($this->CI->db->delete($this->user_table, array('id' => $user_id))) {
			//Database call was successful, user is deleted
			return TRUE;
		} else {
			//There was a problem
			return FALSE;
		}
	}

	/**
	 * Login and sets session variables
	 *
	 * @access    public
	 *
	 * @param    string
	 * @param    string
	 *
	 * @return    bool
	 */
	function login($user = '', $password = '', $min_level = 0)
	{
		//Put here for PHP 4 users
		$this->CI = &get_instance();
		//Make sure login info was sent
		if ($user === '' OR $password === '') {
			return FALSE;
		}
		//Check if already logged in
		if ($this->CI->session->userdata('name') == $user) {
			//User is already logged in.
			return FALSE;
		}
		//Check against user table
		$this->CI->db->where('name', $user);
		$query = $this->CI->db->get_where($this->user_table);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			//Verify if old or new password type
			//Old
			if (strlen($row['password']) <= 33) {
				//Check against password

				if (md5($password) != $row['password']) {
					return FALSE;
				}

				//Update hash to new type and insert into DB
				$update = array(
					'password' => password_hash($password, PASSWORD_BCRYPT),
				);

				$this->CI->db->where('name', $user);
				$this->CI->db->update($this->user_table, $update);

			} else {
				//New Password Type
				if (!password_verify($password, $row['password'])) {
					return FALSE;
				}
			}

			//Check user level is high enough
			if ($row['level'] < $min_level) {

				return FALSE;
			}

			//Remove the password field
			unset($row['password']);
			//Set session data
			$this->CI->session->set_userdata($row);
			//Set logged_in to true
			$this->CI->session->set_userdata(array('logged_in' => TRUE));
			$this->CI->session->set_userdata('level', $row['level']);

			//Login was successful
			return TRUE;
		} else {
			//No database result found
			return FALSE;
		}
	}

	/**
	 * Logout user
	 *
	 * @access    public
	 * @return    void
	 */
	function logout()
	{
		//Put here for PHP 4 users
		$this->CI = &get_instance();
		//Destroy session
		$this->CI->session->sess_destroy();
	}
}