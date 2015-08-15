<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @author        CIOpenReview.com
 * @copyright           Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
 * @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
 * @link        http://CIOpenReview.com
 */
// ------------------------------------------------------------------------

/**    This file is part of CIOpenReview.
 *
 *    CIOpenReview is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    CIOpenReview is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with CIOpenReview.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * User model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class User_model extends CI_Model
{

	/*
	 * User model class constructor
	 */

	function User_model()
	{
		parent::__construct();
		$this->load->helper('security');
		$this->load->database();
	}

	/*
	 * addUser function
	 */

	function addUser($name, $password, $email, $level)
	{
		// add the user
		$password = do_hash($password, 'md5');
		$data = array(
			'name' => $name,
			'password' => $password,
			'email' => $email,
			'level' => $level
		);
		$this->db->insert('user', $data);
	}

	/*
	 * updateUser function
	 */

	function updateUser($id, $name, $password, $email, $level)
	{
		// update the user
		$data['name'] = $name;
		$data['level'] = $level;
		$data['email'] = $email;
		if ($password != 'PASSWORDSAME') {
			// password has been edited so update the password
			$data['password'] = do_hash($password, 'md5');
		};
		$this->db->where('id', $id);
		$this->db->update('user', $data);
	}

	/*
	 * deleteUser function
	 */

	function deleteUser($id)
	{
		// delete the user
		$this->db->where('id', $id);
		$this->db->delete('user');
	}

	/*
	 * countUsers function
	 */

	function countUsers()
	{
		// return total number of users
		return $this->db->count_all_results('user');
	}

	/*
	 * getUserById function
	 */

	function getUserById($id)
	{
		// return the user
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no result
		return FALSE;
	}

	/*
	 * managerEmailExists function
	 */

	function managerEmailExists($email, $id = 0)
	{
		// check if manager email address exists in users table... to prevent duplicates
		$this->db->where('email', $email);
		// ignore a user id... this is optional and is used when you want to ignore the current user when editing
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$this->db->limit(1);
		$query = $this->db->get('user');
		// return number of users with this email address
		if ($query->num_rows() > 0) {
			return $query->row()->id;
		}
		// no other users
		return FALSE;
	}

	/*
	 * resetPassword function
	 */

	function resetPassword($key)
	{
		// check the provided key and reset the user's password
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0) {
			// found the user
			$user_id = $query->row()->id;
			// create a new password
			$password = random_string('alnum', 12);
			$data['password'] = do_hash($password, 'md5');
			$data['key'] = '';
			$this->db->where('id', $user_id);
			// update the user
			$this->db->update('user', $data);
			// return the new password
			return $password;
		}
		// key not found
		return FALSE;
	}

	/*
	 * storeTemporaryKey function
	 */

	function storeTemporaryKey($user_id)
	{
		// put a temporary 64 character alphanumeric key in the user record
		$data['key'] = random_string('alnum', 64);
		$this->db->where('id', $user_id);
		// update the user
		$this->db->update('user', $data);
		// return the key
		return $data['key'];
	}

	/*
	 * getEmailFromKey function
	 */

	function getEmailFromKey($key)
	{
		// use the provided key to find the user's email address
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get('user');
		// return the email address
		if ($query->num_rows() > 0) {
			return $query->row()->email;
		}
		// no result
		return FALSE;
	}

	/*
	 * getUserByName function
	 */

	function getUserByName($name, $id = 0)
	{
		// return the user
		$this->db->where('name', $name);
		// optionally ignore a particular user
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$this->db->limit(1);
		$query = $this->db->get('user');
		// return the user
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no result
		return FALSE;
	}

	/*
	 * getAllUsers function
	 */

	function getAllUsers($limit, $offset = 0)
	{
		// return all users
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('user');
		// return the users
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no result
		return FALSE;
	}

	/*
	 * countUsersForLevel function
	 */

	function countUsersForLevel($level = 10)
	{
		// count the number of users for a particular level, default is 10
		$this->db->where('level >=', $level);
		// return number of users
		return $this->db->count_all_results('user');
	}

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */