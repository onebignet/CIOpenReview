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
    private $build_number = "10004";
    private $version_string = "1.0.4";
    private $failure_codes = array(
        'php_version_fail' => "Your version of PHP is too old to be able to run CodeIgniter. You will not be able to install this script",
        'permissions_fail' => "Not all the required files and directories are writable. You will not be able to install and run the script until you have made them writable",
        'mail_fail' => "The mail server did not respond to a simple email test. This means your site will not be able to send out emails",
        'gd_fail' => "You do not have GD enabled - this is used to resize images and for security features when visitors post comments on your site (CAPTCHA). If you run the script without it, these functions will not work correctly and you might see error messages",
        'rewrite_fail_apache' => "URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have enabled mod_rewrite for Apache",
        'rewrite_fail_nginx' => 'URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have modified your NGINX config as seen <a href="http://wiki.nginx.org/Codeigniter">Here</a>',
        'rewrite_unknown' => 'Could not confirm that URL rewriting is enabled or working correctly. You can continue to install the script but if some URLs give you "not found" errors, you should check URL rewriting is enabled',
        'curl_init_fail' => "Your server does not have the cURL extension installed - you can still run the script but you will not be able to grab images from remote sites when you create reviews",

    );
    private $critical_errors = array(
        'php_version_fail',
        'permissions_fail',
    );
    private $error = array();
    private $error_list = array();
    private $path_to_database_file = "includes/database.php";
    private $path_to_sql_file = "includes/db_queries.php";
    private $path_to_config_file = '../application/config/database.php';

    private $db_hostname = NULL;
    private $db_name = NULL;
    private $db_username = NULL;
    private $db_password = NULL;

    private $manager_username = NULL;
    private $manager_password = NULL;

    private $site_name = NULL;
    private $site_email = NULL;

    private $username_min_length = 5;
    private $username_max_length = 25;
    private $password_min_length = 8;
    private $password_max_length = 25;

    private $session_username = NULL;
    private $session_token = NULL;

    //Verifies that a directory is writable
    public function is_dir_writable($dir_name)
    {
        if (is_writable("../" . $dir_name)) {
            $this->show_success($dir_name . " is Writable");
        } else {
            $this->show_error($dir_name . " is <b>NOT</b> wirtable");
            $this->set_error('permissions_fail');
        }
    }

    //Verfies that a certain value is more than the minimum requirement (ex PHP POST size)
    public function is_more_than($name, $value, $required, $append = "")
    {
        if ($value >= $required) {
            $this->show_success($name . " meets the minimum requirement of " . $required . $append);
        } else {
            $this->show_error($name . " does <b>NOT</b> meet the minimum requirement of " . $required . $append);
        }
    }

    //Verifies that certain functionality is enables or disabled (ex. register_globals)
    public function is_on_or_off($name, $value, $required, $error_key)
    {

        if (!$value || $value == 0) {
            $value = "OFF";
        }
        if ($value == 1 || strtolower($value) == "on") {
            $value = "ON";
        }

        if ($value == $required) {
            $this->show_success($name . " meets the requirement (" . $required . ")");
        } else {
            $this->set_error($error_key);
            $this->show_error($name . " <b>DOES NOT</b> meet the requirement (Needs to be <b>" . $required . "</b>)");
        }

    }

    //Check to see if we have the minimum PHP version to run CI3.0
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

    //Check to see if we have Apache, NGINX, or an unknown server
    public function check_server_software()
    {
        //If server is Apache or NGINX all is well, else warn that it is unknown
        if ($this->is_apache() || $this->is_nginx()) {
            $this->show_success("Server software is: " . $_SERVER['SERVER_SOFTWARE']);
        } else {
            $this->show_warning("Server software (" . $_SERVER['SERVER_SOFTWARE'] . ") is unknown. This may cause unpredictable results ");
        }
    }


    public function check_installed_version()
    {

        $current_version = $this->get_installed_version();

        if ($current_version) {
            $this->show_success("Current version of application: " . $current_version);
        } else {
            $this->show_success("Current version of application: Not Installed ");
        }

    }

    //Check to see if mod_rewrite is enabled for Apache, or try_files for NGINX
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
            //If NGINX, test index.php to verify CI is forwarding properly
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

    //Check to see if a PHP function exists
    public function check_function_exists($name, $function_name, $fail_code)
    {
        if (function_exists($function_name)) {
            $this->show_success($name . " support is enabled");
        } else {
            $this->set_error($fail_code);
            $this->show_error($name . " support is <b>NOT</b> enabled!");
        }
    }

    //Check to see if the mail() function works
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

    //Checks for critical errors
    public function is_critical_error()
    {
        //If no errors, return false
        if (count($this->error) == 0) {
            return FALSE;
        }

        foreach ($this->error as $error) {
            if (in_array($error, $this->critical_errors)) {
                //There is a critical error
                return TRUE;
            }
        }

        //No critical errors
        return FALSE;
    }

    //Display a summary of errprs/warning on sytage_1
    public function show_precheck_errors_and_warnings()
    {
        //If there are no errors
        if (count($this->error) == 0) {
            $this->alert_success("All checks have passed! You can install without issue!");

            return TRUE;
        }

        $error_string_list = array();
        //Look for critical errors
        foreach ($this->error as $error) {
            if (in_array($error, $this->critical_errors)) {
                //Display critical errors first
                $this->alert_error($this->failure_codes[$error]);

                return FALSE;
            }
            $error_string_list[] = $this->failure_codes[$error];
        }

        //If there are no critical errors, display the warnings
        $error_string = implode("<br><br>", $error_string_list);
        $this->alert_warning($error_string);

        return FALSE;

    }

    private function create_db_connection_from_config($verbose = TRUE)
    {
        $db = array();

        define('BASEPATH', 'basepath');

        if (is_file($this->path_to_config_file)) {

            include $this->path_to_config_file;

            //Connect to DB
            $mysqli = mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

            return $mysqli;

        } else {
            //database.php is missing
            if ($verbose) {
                $this->error_list[] = "Could not finalize DB configurations. Please ensure application/config/database.php exists";
            }
        }
        return NULL;
    }

    public function update_database_structure()
    {
        $db_queries = NULL;

        //If no install/update is needed...do not make changes!
        if (!$this->is_install_needed()) {
            return;
        }

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }

        //Cycle through the install.sql and execute all queries
        include($this->path_to_sql_file);
        foreach ($db_queries as $query) {
            $mysqli->query($query);
        }

        //Display errors, if any
        return $this->display_all_errors();
    }

    //Create the database.php config file needed by CI
    public function create_database_file()
    {

        //If no install/update is needed...do not make changes!
        if (!$this->is_install_needed()) {
            return TRUE;
        }

        //Load the database.php file and write it to disk
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
                    if ($this->update_database_structure()) {
                        return $this->display_all_errors();
                    } else {
                        //DB Structure Update Failed
                        $this->alert_error('There was a problem updating the database structure. Please restart the installation</div>');

                        return FALSE;
                    }

                } else {
                    //File was not written
                    $this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

                    return FALSE;
                }
            } else {
                //fwrite() failes
                $this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

                return FALSE;
            }
        } else {
            // File is not writable
            $this->alert_error('There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>');

            return FALSE;
        }


    }

    //Verify the database variables
    public function validate_database_vars()
    {
        $this->db_hostname = $this->sanitize($_POST['hostname']);
        $this->db_name = $this->sanitize($_POST['database']);
        $this->db_username = $this->sanitize($_POST['username']);
        $this->db_password = $this->sanitize($_POST['password']);

        //Do some sanity checks on the values
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

        //Attempt to connect to DB
        if ($this->db_hostname && $this->db_username && $this->db_password && $this->db_name) {

            $mysqli = mysqli_connect($this->db_hostname, $this->db_username, $this->db_password, $this->db_name);

            //Return false on error
            if ($mysqli->connect_error) {
                $this->error_list[] = $mysqli->connect_error;
            }

        }

        //Display errors if any
        return $this->display_all_errors();
    }

    //Validate the site settings
    public function validate_site_vars()
    {

        //Sanitize input
        $this->manager_username = $this->sanitize($_POST['managerusername']);
        $this->manager_password = $this->sanitize($_POST['managerpassword']);
        $this->site_name = $this->sanitize($_POST['sitename']);
        $this->site_email = $this->sanitize($_POST['siteemail']);

        //Do some sanity checks on the values
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

        //Display errors, if any
        return $this->display_all_errors();
    }

    //Update the database with the new site_vars;
    public function load_site_vars_into_db()
    {
        //If no install/update is needed...do not make changes!
        if (!$this->is_install_needed()) {
            return TRUE;
        }

        $user_count = 0;

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            $this->error_list[] = $mysqli->connect_error;
        } else {

            //Prepare data for mysql
            $this->manager_username = $mysqli->real_escape_string($this->manager_username);
            $this->manager_password = $mysqli->real_escape_string($this->manager_password);
            $this->site_name = $mysqli->real_escape_string($this->site_name);
            $this->site_email = $mysqli->real_escape_string($this->site_email);

            // check if users already exist. If so, do not delete and override the user. This is simply a bad idea
            $query = "SELECT `email` FROM `user`";
            if ($result = $mysqli->query($query)) {
                $user_count = $result->num_rows;
                $result->close();
            }

            if ($user_count == 0) {
                // insert manager level user
                $mysqli->query("INSERT INTO `user` (`name`, `email`, `password`, `level`) VALUES ('" . $this->manager_username . "', '" . $this->site_email . "', '" . password_hash($this->manager_password, PASSWORD_BCRYPT) . "', '10')");

            }

            // update settings
            $mysqli->query("UPDATE `setting` SET `value` = '" . $this->site_name . "' WHERE `name` ='site_name'");
            $mysqli->query("UPDATE `setting` SET `value` = '" . $this->site_email . "' WHERE `name` ='site_email'");
            chmod('../application/config', 0705);
        }

        //Display errors, if any
        return $this->display_all_errors();

    }

    //Add an error into the error array

    public function is_install_needed()
    {
        if ($this->get_installed_build() >= $this->build_number) {
            $this->alert_success("You are currently running the latest version of CIOpenReview (" . $this->version_string . ")");
            $this->delete_install_dir();

            return FALSE;
        }

        return TRUE;
    }

    //Show a basic green table row

    public function complete_installation()
    {

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            $this->error_list[] = "Could not select CIOpenReview database. Please ensure application/config/database.php is readable";
        } else {

            //prepare for mysql
            $this->build_number = $mysqli->real_escape_string($this->build_number);
            $this->version_string = $mysqli->real_escape_string($this->version_string);

            //Check the currently installed version of CIOpenReview/OpenReviewScript
            $installed_version = $this->get_installed_build();

            //If OpenReviewScript is installed, installed_version is NULL. Add DB entries
            if ($installed_version == NULL) {

                $query = "SELECT `id` FROM `setting` ORDER BY `id` DESC LIMIT 1";
                if ($result = $mysqli->query($query)) {
                    $last_id = $result->fetch_object();
                    $last_insert_id = $last_id->id;
                    $result->close();
                } else {
                    $last_insert_id = 99;
                }

                $build_code_id = $last_insert_id + 1;
                $version_string_id = $last_insert_id + 2;


                $mysqli->query("INSERT INTO `setting` (`id`, `name`, `value`) VALUES ('" . $build_code_id . "', 'build_code', '" . $this->build_number . "')");
                $mysqli->query("INSERT INTO `setting` (`id`, `name`, `value`) VALUES ('" . $version_string_id . "', 'version_string', '" . $this->version_string . "')");
            } else {
                //Update the version of CIOpenReview in the database
                $mysqli->query("UPDATE `setting` SET `value` = '" . $this->build_number . "' WHERE `name` = 'build_code'");
                $mysqli->query("UPDATE `setting` SET `value` = '" . $this->version_string . "' WHERE `name` = 'version_string'");
            }

            return TRUE;
        }

        //Display errors, if any
        if ($this->display_all_errors()) {
            session_destroy();
            $this->delete_install_dir();
        }

    }

    //Show a basic red table row

    public function delete_install_dir()
    {
        if ($this->remove_directory("../install")) {
            $this->alert_success('"install" directory has been automatically deleted for safety .');

            return TRUE;
        }
        $this->alert_error("Failed to automatically delete \"install\" dir. IMPORTANT - You must now delete the /install folder, if
		you don't your site could be vulnerable to security attacks");

        return FALSE;
    }

    //Show a basic yellow table row

    public function db_exists()
    {

        $user_count = 0;
        $setting_count = 0;

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }
        if ($result = $mysqli->query("select `email` from `user` LIMIT 1")) {
            $user_count = $result->num_rows;
            $result->close();
        }

        if ($result = $mysqli->query("select `value` from `setting` LIMIT 1")) {
            $setting_count = $result->num_rows;
            $result->close();
        }

        if ($user_count == 1 && $setting_count == 1) {
            return TRUE;
        }
        return FALSE;
    }


//Show a green alert

    public
    function login_manager($username, $password)
    {

        $userpass = NULL;
        $username = $this->sanitize($username);
        $password = $this->sanitize($password);

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }

        //prepare mysql
        $username = $mysqli->real_escape_string($username);
        $password = $mysqli->real_escape_string($password);

        //Validate user and set class tokens
        if ($result = $mysqli->query("select `password` from `user` WHERE `name`='" . $username . "' LIMIT 1")) {
            $row = $result->fetch_object();
            $userpass = $row->password;
            $result->close();
        }

        //Support for old style password
        if (strlen($userpass) <= 33) {
            //Check against password

            if (md5($password) == $userpass) {
                $this->set_session_vars($username, md5(md5($userpass)));

                return TRUE;
            }
        }

        if (password_verify($password, $userpass)) {
            $this->set_session_vars($username, md5(md5($userpass)));

            return TRUE;
        }

        return FALSE;
    }

//Show a yellow alert

    public
    function validate_token($username, $token)
    {

        $userpass = NULL;
        $username = $this->sanitize($username);
        $token = $this->sanitize($token);

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config();

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }

        //prepare mysql
        $username = $mysqli->real_escape_string($username);

        //Validate user and set class tokens
        if ($result = $mysqli->query("select `password` from `user` WHERE `name`='" . $username . "' LIMIT 1")) {
            $row = $result->fetch_object();
            $userpass = $row->password;
            $result->close();
        }
        if ($token == md5(md5($userpass))) {
            $this->set_session_vars($username, md5(md5($userpass)));

            return TRUE;
        }

        return FALSE;
    }

//Show a red alert

    public
    function validation_error()
    {
        $this->alert_error("Please provide your CIOpenReview login credentials in order to proceed.");
    }

//Is this Apache?

    public
    function set_session_vars($session_username, $session_token)
    {
        $this->session_username = $session_username;
        $this->session_token = $session_token;
    }

//Is this NGINX?

    public
    function get_session_token()
    {
        return $this->session_token;
    }

//Open a curl connection to a remote URL

    private
    function set_error($error_code)
    {
        $this->error[] = $error_code;
    }

//Check the installed build of CIOpenReview

    private
    function show_success($message)
    {
        echo '<td class="success">' . $message . '</td>';
    }

//Check the installed version of CIOpenReview

    private
    function show_error($message)
    {
        echo '<td class="danger">' . $message . '</td>';
    }


//Display all of the errors in the errors[] array

    private
    function show_warning($message)
    {
        echo '<td class="warning">' . $message . '</td>';
    }

//Is an install needed in the first place or are we already up-to-date?

    private
    function alert_success($message)
    {
        echo '<div class="alert alert-success">' . $message . '</div>';
    }

//Complete the installation by updating the DB build number. This should make the installer no-longer work.

    private
    function alert_warning($message)
    {
        echo '<div class="alert alert-warning">' . $message . '</div>';
    }

    private
    function alert_error($message)
    {
        echo '<div class="alert alert-danger">' . $message . '</div>';
    }

    private
    function is_apache()
    {
        if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), "apache") !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    private
    function is_nginx()
    {
        if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), "nginx") !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    private
    function curl_check($url)
    {
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");

            $response = curl_exec($curl);
            curl_close($curl);

            //We are looking for a specific response for apache/NGINX
            if (trim($response) == 'yes' || strpos($response, "404 Page Not Found") !== FALSE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    private
    function get_installed_build()
    {
        $db = array();
        $build = NULL;

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config(FALSE);

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }

        if ($result = $mysqli->query("select `value` from `setting` WHERE `name`='build_code'")) {
            $row = $result->fetch_object();
            $build = $row->value;
            $result->close();

        }

        return $build;
    }

    private
    function get_installed_version()
    {
        $db = array();
        $version = NULL;

        //Connect to DB
        $mysqli = $this->create_db_connection_from_config(FALSE);

        //Return false on error
        if (!$mysqli || $mysqli->connect_error) {
            return FALSE;
        }

        if ($result = $mysqli->query("select `value` from `setting` WHERE `name`='version_string'")) {
            $row = $result->fetch_object();
            $version = $row->value;
            $result->close();

        }

        if ($version == NULL) {
            $version = '1.0.1';
        }

        return $version;
    }

    private
    function display_all_errors()
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

    private
    function remove_directory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->remove_directory($file) : unlink($file);
        }
        rmdir($path);

        return TRUE;
    }

    private
    function sanitize($input)
    {
        $input = trim($input);
        return filter_var($input, FILTER_SANITIZE_STRING);

    }


}
