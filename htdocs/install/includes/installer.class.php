<?php
/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          install
 * @author        OpenReviewScript.org
 * @copyright           Copyright (c) 2015 CIOpenReview.com
 * @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
 * @link        http://ciopenreview.com
 */
// ------------------------------------------------------------------------
//
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
?>
<?php

defined('INSTALLER') OR exit('No direct script access allowed');

class Installer
{


	//Initialize some variables that will be needed
	private $minimum_php_version_code = "50204";
	private $minimum_php_version_string = "5.2.4";
	private $build_number = "10002";
	private $version_string = "1.0.2";
	private $failure_codes = array(
		'php_version_fail'    => "Your version of PHP is too old to be able to run CodeIgniter. You will not be able to install this script",
		'permissions_fail'    => "Not all the required files and directories are writable. You will not be able to install and run the script until you have made them writable",
		'mail_fail'           => "The mail server did not respond to a simple email test. This means your site will not be able to send out emails",
		'gd_fail'             => "You do not have GD enabled - this is used to resize images and for security features when visitors post comments on your site (CAPTCHA). If you run the script without it, these functions will not work correctly and you might see error messages",
		'rewrite_fail_apache' => "URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have enabled mod_rewrite for Apache",
		'rewrite_fail_nginx'  => 'URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have modified your NGINX config as seen <a href="http://wiki.nginx.org/Codeigniter">Here</a>',
		'rewrite_unknown'     => 'Could not confirm that URL rewriting is enabled or working correctly. You can continue to install the script but if some URLs give you "not found" errors, you should check URL rewriting is enabled',
		'curl_init_fail'      => "Your server does not have the cURL extension installed - you can still run the script but you will not be able to grab images from remote sites when you create reviews",

	);
	private $critical_errors = array(
		'php_version_fail',
		'permissions_fail',
	);
	private $error = array();
	private $error_list = array();
	private $path_to_database_file = "database.php";
	private $path_to_config_file = '../application/config/database.php';

	private $db_hostname = NULL;
	private $db_name = NULL;
	private $db_username = NULL;
	private $db_password = NULL;

	private $manager_username = NULL;
	private $manager_password = NULL;

	private $site_name = NULL;
	private $site_email = NULL;

	private $username_min_length = 3;
	private $username_max_length = 15;
	private $password_min_length = 3;
	private $password_max_length = 15;

	public function is_dir_writable($dir_name)
	{
		if (is_writable("../" . $dir_name)) {
			$this->show_success($dir_name . " is Writable");
		} else {
			$this->show_error($dir_name . " is <b>NOT</b> wirtable");
			$this->set_error('permissions_fail');
		}
	}

	public function is_more_than($name, $value, $required, $append = "")
	{
		if ($value >= $required) {
			$this->show_success($name . " meets the minimum requirement of " . $required . $append);
		} else {
			$this->show_error($name . " does <b>NOT</b> meet the minimum requirement of " . $required . $append);
		}
	}

	public function is_on_or_off($name, $value, $required, $error_key)
	{

		if (!$value || $value == 0) {
			$value = "OFF";
		}
		if ($value == 1 || strtolower($value) == "on") {
			$value == "ON";
		}

		if ($value == $required) {
			$this->show_success($name . " meets the requirement (" . $required . ")");
		} else {
			$this->set_error($error_key);
			$this->show_error($name . " <b>DOES NOT</b> meet the requirement (Needs to be <b>" . $required . "</b>)");
		}

	}

	public function check_php_version()
	{
		//Older versions to not have PHP_VERSION_ID
		if (!defined('PHP_VERSION_ID')) {
			$version = explode('.', PHP_VERSION);

			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}

		//Verify PHP meets minimum version ID
		if (PHP_VERSION_ID >= $this->minimum_php_version_code) {
			$this->show_success("PHP Version is greater than " . $this->minimum_php_version_string);
		} else {
			$this->set_error("php_version_fail");
			$this->show_error("PHP Version is older than " . $this->minimum_php_version_string . ". Script will <b>NOT RUN</b>");
		}
	}

	public function check_server_software()
	{
		//If server is Apache or NGINX all is well, else warn that it is unknown
		if ($this->is_apache() || $this->is_nginx()) {
			$this->show_success("Server software is: " . $_SERVER['SERVER_SOFTWARE']);
		} else {
			$this->show_warning("Server software (" . $_SERVER['SERVER_SOFTWARE'] . ") is unknown. This may cause unpredictable results ");
		}
	}

	public function check_url_rewrite()
	{

		if ($this->is_apache()) {
			//If apache, the .htaccess file should work
			$filename = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'test_rewrite.php';
			if ($this->curl_check($filename)) {
				$this->show_success("Your Apache server has the correct mod_rewrite settings");
			} else {
				$this->set_error('rewrite_fail_apache');
				$this->show_error("Your Apache server <b>DOES NOT</b> have the correct mod_rewrite settings. Please ensure mod_rewrite is enabled");
			}
		} else if ($this->is_nginx()) {
			$filename = 'http://' . $_SERVER['HTTP_HOST'] . '/nginx_rewrite_test';
			if ($this->curl_check($filename)) {
				$this->show_success("Your NGINX server has the correct try_files settings");
			} else {
				$this->set_error('rewrite_fail_nginx');
				$this->show_error('Your NGINX server <b>DOES NOT</b> have the correct try_files settings. Please ensure nginx.conf is properly configured as outlined <a href="http://wiki.nginx.org/Codeigniter">Here</a>');
			}
		} else {
			$this->set_error('rewrite_unknown');
			$this->show_warning("Unable to detect server type/version or rewrite configs");
		}
	}

	public function check_function_exists($name, $function_name, $fail_code)
	{
		if (function_exists($function_name)) {
			$this->show_success($name . " support is enabled");
		} else {
			$this->set_error($fail_code);
			$this->show_error($name . " support is <b>NOT</b> enabled!");
		}
	}

	public function check_email_system()
	{
		$headers = 'From: install@ciopenreview.com' . "\r\n" .
			'Reply-To: install@ciopenreview.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		if (mail('null@localhost', 'Test', 'Test', $headers)) {
			$this->show_success("The mail server seems to be responding correctly");
		} else {
			$this->show_error("The mail server <strong>IS NOT</strong> responding correctly");
			$this->set_error("mail_fail");
		}
	}

	public function is_critical_error()
	{
		if (count($this->error) == 0) {
			return FALSE;
		}

		foreach ($this->error as $error) {
			if (in_array($error, $this->critical_errors)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function show_precheck_errors_and_warnings()
	{
		if (count($this->error) == 0) {
			$this->alert_success("All checks have passed! You can install without issue!");

			return TRUE;
		}

		$error_string_list = array();
		foreach ($this->error as $error) {
			if (in_array($error, $this->critical_errors)) {
				$this->alert_error($this->failure_codes[$error]);

				return FALSE;
			}
			$error_string_list[] = $this->failure_codes[$error];
		}

		$error_string = implode("<br><br>", $error_string_list);
		$this->alert_warning($error_string);

		return FALSE;

	}

	public function create_database_file()
	{
		if (!$this->validate_database_vars()) {
			include_once("installer.stage_2.php");
			exit;
		}

		if (mysql_connect($this->db_hostname, $this->db_username, $this->db_password)) {
			if (mysql_select_db($this->db_name)) {

				$queries = explode(";\n", file_get_contents('install.sql'));
				foreach ($queries as $query) {
					mysql_query($query);
				}

				$installed_version = $this->get_installed_version();

				if ($installed_version == NULL) {
					mysql_query("INSERT INTO `setting` (`id`, `name`, `value`) VALUES ('59', 'build_code', '" . $this->build_number . "')");
					mysql_query("INSERT INTO `setting` (`id`, `name`, `value`) VALUES ('60', 'version_string', '" . $this->version_string . "')");
				} else {
					mysql_query("UPDATE `setting` SET `value` = '" . $this->build_number . "' WHERE `name` = 'build_code')");
					mysql_query("UPDATE `setting` SET `value` = '" . $this->version_string . "' WHERE `name` = 'version_string')");
				}

			} else {
				$this->error_list[] = 'Unable to select the database "' . $this->db_name . '" Please go back and try again';
			}
		} else {
			$this->error_list[] = 'Unable to connect to the database on host "' . $this->db_username . '@' . $this->db_hostname . '" Please go back and try again';
		}


		$database_file = file_get_contents($this->path_to_database_file);
		$database_file = str_replace("{{hostname}}", $this->db_hostname, str_replace("{{database}}", $this->db_name, str_replace("{{username}}", $this->db_username, str_replace("{{password}}", $this->db_password, $database_file))));

		$file = fopen($this->path_to_config_file, 'wb+');
		if (is_writable($this->path_to_config_file)) {
			$write = fwrite($file, $database_file);
			if ($write !== FALSE) {
				// Database config file written ok
				fclose($file);
				if (file_exists($this->path_to_config_file)) {
					//Everything should be complete, just verify there were no errors
					return $this->display_all_errors();
				} else {
					$this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

					return FALSE;
				}
			} else {
				$this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

				return FALSE;
			}
		} else {
			// File is not writable
			$this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

			return FALSE;
		}


	}

	public function validate_database_vars()
	{
		$error_list = array();

		$this->db_hostname = trim($_POST['hostname']);
		$this->db_name = trim($_POST['database']);
		$this->db_username = trim($_POST['username']);
		$this->db_password = trim($_POST['password']);

		if ($this->db_name == NULL) {
			$this->error_list[] = "Database name cannot be empty";
		}
		if ($this->db_hostname == NULL) {
			$this->error_list[] = "Database hostname cannot be empty";
		}
		if ($this->db_username == NULL) {
			$this->error_list[] = "Database username cannot be empty";
		} else if (strlen($this->db_username) <= $this->username_min_length) {
			$this->error_list[] = 'Database username should be more than ' . $this->username_min_length . ' characters long';
		}
		if ($this->db_password == NULL) {
			$this->error_list[] = "Database password cannot be empty";
		} else if (strlen($this->db_password) <= $this->password_min_length) {
			$this->error_list[] = 'Database password should be more than ' . $this->password_min_length . ' characters long';
		}

		if ($this->db_hostname && $this->db_username && $this->db_password && $this->db_name) {

			if (mysql_connect($this->db_hostname, $this->db_username, $this->db_password)) {
				if (!mysql_select_db($this->db_name)) {
					$this->error_list[] = 'Unable to select the database "' . $this->db_name . '" Please go back and try again';
				}
			} else {
				$this->error_list[] = 'Unable to connect to the database on host "' . $this->db_username . '@' . $this->db_hostname . '" Please go back and try again';
			}
		}

		return $this->display_all_errors();
	}

	public function validate_site_vars()
	{
		$error_list = array();

		$this->manager_username = trim($_POST['managerusername']);
		$this->manager_password = trim($_POST['managerpassword']);
		$this->site_name = trim($_POST['sitename']);
		$this->site_email = trim($_POST['siteemail']);

		if ($this->manager_username == NULL) {
			$this->error_list[] = 'The username cannot be empty';

		} else if (strlen($this->manager_username) < $this->username_min_length || strlen($this->manager_username) > $this->username_max_length) {
			$this->error_list[] = 'The username should be between ' . $this->username_min_length . ' and ' . $this->username_max_length . ' characters long';

		} else if (!ctype_alnum($this->manager_username)) {
			$this->error_list[] = 'The username should be letters and/or numbers only';

		}

		if ($this->manager_password == NULL) {
			$this->error_list[] = 'The password cannot be empty';

		} else if (strlen($this->manager_password) < $this->password_min_length || $this->manager_password > $this->password_max_length) {
			$this->error_list[] = 'The password should be between ' . $this->password_min_length . ' and ' . $this->password_max_length . ' characters long';
		}

		if ($this->site_name == NULL) {
			$this->error_list[] = 'The site name cannot be empty';

		}

		if ($this->site_email == NULL) {
			$this->error_list[] = 'The site email cannot be empty';

		} else if (!filter_var($this->site_email, FILTER_VALIDATE_EMAIL)) {
			$this->error_list[] = 'The site email needs to be a valid email address';
		}

		return $this->display_all_errors();
	}

	public function load_site_vars_into_db()
	{
		$error_list = array();
		$db = array();

		define('BASEPATH', 'whatever');
		include $this->path_to_config_file;
		if (mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'])) {
			if (mysql_select_db($db['default']['database'])) {
				// delete all users - in case there has been a previous installation attempt
				mysql_query("DELETE FROM `user` WHERE `name` = '" . $this->manager_username . "'");
				// insert manager level user
				mysql_query("INSERT INTO `user` (`name`, `email`, `password`, `level`) VALUES ('" . $this->manager_username . "', '" . $this->site_email . "', '" . password_hash($this->manager_password, PASSWORD_BCRYPT) . "', '10')");
				// update settings
				mysql_query("UPDATE `setting` SET `value` = '" . $this->site_name . "' WHERE `name` ='site_name'");
				mysql_query("UPDATE `setting` SET `value` = '" . $this->site_email . "' WHERE `name` ='site_email'");
				chmod('../application/config', 0705);
			} else {
				$this->error_list[] = 'Unable to select the database "' . $this->db_name . '" Please go back and try again';
			}
		} else {
			$this->error_list[] = 'Unable to connect to the database on host "' . $this->db_username . '@' . $this->db_hostname . '" Please go back and try again';
		}

		return $this->display_all_errors();

	}

	private function set_error($error_code)
	{
		$this->error[] = $error_code;
	}

	private function show_success($message)
	{
		echo '<td class="success">' . $message . '</td>';
	}

	private function show_error($message)
	{
		echo '<td class="danger">' . $message . '</td>';
	}

	private function show_warning($message)
	{
		echo '<td class="warning">' . $message . '</td>';
	}

	private function alert_success($message)
	{
		echo '<div class="alert alert-success">' . $message . '</div>';
	}

	private function alert_warning($message)
	{
		echo '<div class="alert alert-warning">' . $message . '</div>';
	}

	private function alert_error($message)
	{
		echo '<div class="alert alert-danger">' . $message . '</div>';
	}

	private function is_apache()
	{
		if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), "apache") !== FALSE) {
			return TRUE;
		}

		return FALSE;
	}

	private function is_nginx()
	{
		if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), "nginx") !== FALSE) {
			return TRUE;
		}

		return FALSE;
	}

	private function curl_check($url)
	{
		if (function_exists('curl_init')) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");

			$response = curl_exec($curl);
			curl_close($curl);

			if (trim($response) == 'yes' || strpos($response, "404 Page Not Found") !== FALSE) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	private function get_installed_version()
	{
		$query = mysql_query("select `value` from 'setting' WHERE name='build_code' ");
		$row = mysql_fetch_row($query);

		return $row['value'];
	}

	private function display_all_errors()
	{
		if (sizeof($this->error_list) !== 0) {

			$error_string = implode("<br><br>", $this->error_list);
			$this->alert_error($error_string);

			unset($this->error_list);
			$this->error_list = array();

			return FALSE;

		}

		return TRUE;
	}

}
