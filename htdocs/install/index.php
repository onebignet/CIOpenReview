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
session_start();
error_reporting(0);
@ini_set('display_errors', 0);
require_once('../application/libraries/Password.php');
define('INSTALLER', TRUE);
require_once("includes/installer.class.php");

$session_username = $_SESSION['session_username'];
$session_token = $_SESSION['session_token'];

$installer = new Installer();

include_once("includes/installer.header.php");

//If there is already a DB, we want them to validate their credentials...otherwise bas things can happen
if ($installer->db_exists() && !$installer->validate_token($session_username, $session_token)) {
    //validate Login form
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['login_form'])) {
            //Check for valid login
            if ($installer->login_manager($_POST['managerusername'], $_POST['managerpassword'])) {

                //Login successful, set the session vars and load stage 1
                $_SESSION['session_username'] = $installer->sanitize($_POST['managerusername']);
                $_SESSION['session_token'] = $installer->get_session_token();

                //Do we need to install anything or are we already up-to-date
                if (!$installer->is_install_needed()) {
                    include_once("includes/installer.no_install_needed.php");
                    exit;
                }
                include_once("includes/installer.stage_1.php");
                include_once("includes/installer.footer.php");

            } else {
                //Invalid login
                session_destroy();
                $installer->validation_error();
                include_once("includes/installer.stage_0.php");
                include_once("includes/installer.footer.php");
                exit;
            }

        }
    } else {
        //Display the login page
        session_destroy();
        $installer->validation_error();
        include_once("includes/installer.stage_0.php");
        include_once("includes/installer.footer.php");
        exit;
    }

}


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['precheck_form'])) {

        // Settings form submitted - show database settings form

        //Skip if the DB already exists and the structure updates have been applied
        if ($installer->db_exists() && $installer->update_database_structure()) {
            include_once("includes/installer.stage_3.php");

        } else {
            //database does not exist
            include_once("includes/installer.stage_2.php");
        }
        include_once("includes/installer.footer.php");
        exit;

    }

    if (isset($_POST['database_form'])) {

        if (!$installer->validate_database_vars()) {
            include_once("includes/installer.stage_2.php");

        } else if ($installer->create_database_file()) {
            include_once("includes/installer.stage_3.php");

        } else {
            include_once("includes/installer.stage_2.php");

        }
        include_once("includes/installer.footer.php");
        exit;
    }

    if (isset($_POST['info_form'])) {
// validate form

        if (!$installer->validate_site_vars()) {
            include_once("includes/installer.stage_3.php");

        } else if ($installer->load_site_vars_into_db() && $installer->complete_installation()) {
            include_once("includes/installer.stage_4.php");

        } else {
            include_once("includes/installer.stage_3.php");

        }
        include_once("includes/installer.footer.php");
        exit;
    }

} else {
    // Nothing submitted - show first page of installer

    //Do we need to install anything or are we already up-to-date
    if (!$installer->is_install_needed()) {
        include_once("includes/installer.no_install_needed.php");
        $installer->installer_delete_install_dir();
        exit;
    }

    include_once("includes/installer.stage_1.php");
    include_once("includes/installer.footer.php");
}


