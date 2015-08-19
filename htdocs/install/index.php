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
error_reporting(0);
require_once('../application/libraries/Password.php');
define('INSTALLER', TRUE);
require_once("includes/installer.class.php");

$installer = new Installer();

include_once("includes/installer.header.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['info_form'])) {
// validate form

		if (!$installer->validate_site_vars()) {
			include_once("includes/installer.stage_3.php");

		} else if ($installer->load_site_vars_into_db()) {
			include_once("includes/installer.stage_4.php");

		} else {
			include_once("includes/installer.stage_3.php");

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

	if (isset($_POST['settings_form'])) {
		// Settings form submitted - show database settings form
		include_once("includes/installer.stage_2.php");
		include_once("includes/installer.footer.php");
		exit;

	}

} else {
	// Nothing submitted - show first page of installer
	include_once("includes/installer.stage_1.php");
	include_once("includes/installer.footer.php");
}


