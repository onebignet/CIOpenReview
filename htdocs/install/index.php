<?php
/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          install
 * @author        OpenReviewScript.org
 * @copyright           Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
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
$image_start = '<img src="images/';
$image_end = '.png">';
$gd_fail = FALSE;
$cURL_fail = FALSE;
$rewrite_fail = FALSE;
$rewrite_unknown = FALSE;
$mail_fail = FALSE;
$fill_in_form = FALSE;
$connect_fail = FALSE;
$database_fail = FALSE;
$show_info_form = FALSE;
$path_to_database_file = 'database.php';
$path_to_config_file = '../application/config/database.php';
require_once('../application/libraries/Password.php');

function check_writeable_dir($dirname, $value, $fail_code = "permissions_fail")
{
	if ($value) {
		return '<tr><td class="success"><strong>' . $dirname . '</strong> is writeable</td></tr>';
	} else {
		$$fail_code = TRUE;

		return '<tr><td class="danger"><strong>' . $dirname . '</strong> is <strong>NOT</strong> writeable!</td></tr>';
	}
}

function check_int_mb($name, $value, $required, $fail_code = "post_max_fail")
{
	if ($value >= $required) {
		return '<tr><td class="success"><strong>' . $name . '</strong> (' . $value . 'MB) meets requirements</td></tr>';
	} else {
		$$fail_code = TRUE;

		return '<tr><td class="danger"><strong>' . $name . '</strong> needs to be at least <strong>' . $required . '</strong>MB!</td></tr>';
	}
}

function check_on_off($name, $value, $required, $fail_code)
{
	if (!$value || $value == 0) {
		$value = "OFF";
	}
	if ($value == 1) {
		$value == "ON";
	}
	if ($value == $required) {
		return '<tr><td class="success"><strong>' . $name . '</strong> (' . $value . ') meets requirements</td></tr>';
	} else {
		$$fail_code = TRUE;

		return '<tr><td class="danger"><strong>' . $name . '</strong> needs to be <strong>' . $required . '</strong>!</td></tr>';
	}
}

function check_url_rewrite($value)
{
	if ($value == "yes") {
		return '<tr><td class="success"><strong>URL Rewrites</strong> meet requirements</td></tr>';
	} else {
		return '<tr><td class="warning"><strong>URL Rewrites</strong> are not working! If you are on Apache, please enable mod_rewrite! If you are on NGINX, <a href="http://wiki.nginx.org/Codeigniter">Check Here</a></td></tr>';
	}
}

function check_function_exists($name, $value, $fail_code)
{
	if (function_exists($value)) {
		return '<tr><td class="success"><strong>' . $name . '</strong> support ENABLED</td></tr>';
	} else {
		$$fail_code = TRUE;

		return '<tr><td class="danger"><strong>' . $name . '</strong> support <strong>DISABLED!</strong></td></tr>';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="content-language" content="en"/>
	<title>CIOpenReview - Install</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="custom.css">
</head>

<body class="has-navbar-fixed-top page-index">
<!-- START OF NAVIGATION -->
<nav class="navbar navbar-default navbar-static-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
			        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">CIOpenReview Installer</a>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>
<!-- END OF NAVIGATION -->
<!-- START OF 'CONTAINER' SECTION -->
<div id="content">
	<div class="container">
		<div class="inner">
			<div class="row">
				<div class="col-md-12 text-center">
					<img src="images/ciorlogo.png">

					<h1>CIOpenReview Installer</h1>

					<div class="col-md-12">
						<p>Full installation Instructions Are Available on <u><a target="_blank"
						                                                         href="http://www.ciopenreview.com/guide/install">our
									Install Guide</a></u>
						</p>
					</div>
				</div>
			</div>
			<div class="row">

				<?php
				if ($_SERVER['REQUEST_METHOD'] == "POST") {
				if (isset($_POST['info_form'])) {
				// validate form
				$username = trim($_POST['managerusername']);
				$password = trim($_POST['managerpassword']);
				$sitename = trim($_POST['sitename']);
				$siteemail = trim($_POST['siteemail']);
				$show_info_form = TRUE;
				if (strlen($username) < 4) {
					echo '<div class="alert alert-danger" role="alert">The username should be at least 4 characters long</div>';
				} elseif (strlen($username) > 15) {
					echo '<div class="alert alert-danger" role="alert">The username should be no longer than 15 characters</div>';
				} elseif (!ctype_alnum($username)) {
					echo '<div class="alert alert-danger" role="alert">The username should be letters and/or numbers only</div>';
				} elseif (strlen($password) < 4) {
					echo '<div class="alert alert-danger" role="alert">The password should be at least 4 characters long</div>';
				} elseif (strlen($password) > 15) {
					echo '<div class="alert alert-danger" role="alert">The password should be no longer than 15 characters</div>';
				} elseif (strlen($sitename) < 1) {
					echo '<div class="alert alert-danger" role="alert">Please enter the Site Name</div>';
				} elseif (strlen($siteemail) < 1) {
					echo '<div class="alert alert-danger" role="alert">Please enter the Site Email Address</div>';
				}
				else {
				// update database
				define('BASEPATH', 'whatever');
				include $path_to_config_file;
				if (mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'])) {
				if (mysql_select_db($db['default']['database'])) {
				// delete all users - in case there has been a previous installation attempt
				mysql_query("DELETE FROM `user`");
				// insert manager level user
				mysql_query("INSERT INTO `user` (`name`, `email`, `password`, `level`) VALUES ('" . $username . "', '" . $siteemail . "', '" . password_hash($password, PASSWORD_BCRYPT) . "', '10')");
				// update settings
				mysql_query("UPDATE `setting` SET `value` = '" . $sitename . "' WHERE `name` ='site_name'");
				mysql_query("UPDATE `setting` SET `value` = '" . $siteemail . "' WHERE `name` ='site_email'");
				chmod('../application/config', 0705);
				// installation complete - show final page
				?>
				<h2>Installation Complete - Your new site is ready!</h2>
				<ul>
					<li>To go to your site <b><a href="../">click here</a></b></li>
					<li>to visit your manager area <b><a href="../manager" target="_blank">click
								here</a></b></li>
					<li>and for more help and support visit our <b><a
								href="http://ciopenreview.com/guide/userguide" target="_blank">CIOpenReview
								User Guide</a></b>
					</li>
				</ul>
				<br/>

				<div class="alert alert-warning" role="alert"><b>IMPORTANT - You must now delete the /install folder, if
						you don't your site could be vulnerable to security attacks</b></div>
			</div>
			<?php
			exit;
			}
							}
			echo '<div class="alert alert-danger" role="alert">There was a problem connecting to the database<br />Mysql Error ' . mysql_error() . '</div>';
			exit;
						}
					}
			if (isset($_POST['database_form'])) {
				// Database form submitted
				$hostname = trim($_POST['hostname']);
				$database = trim($_POST['database']);
				$username = trim($_POST['username']);
				$password = trim($_POST['password']);
				if (($hostname == '') OR ($database == '') OR ($username == '') OR ($password == '')) {
					echo '<div class="alert alert-danger" role="alert">You have to fill in every form item</div>';
					$fill_in_form = TRUE;
						} else {
					$connection = mysql_connect($hostname, $username, $password);
					if (!$connection) {
						// MySQL connection error
						echo '<div class="alert alert-danger" role="alert">Can\'t connect to the database using the details you provided, check the information and try again<br /><b>This is the actual error message which might help you solve the problem:</b> "' . mysql_error() . '"</div>';
						$connect_fail = TRUE;
							} else {
						// Successful connection - try to select database
						if (!mysql_select_db($database)) {
							echo '<div class="alert alert-danger" role="alert">Connected successfully but the database "' . htmlspecialchars($database) . '" was not found.<br /><b>This is the actual error message which might help you solve the problem:</b> "' . mysql_error() . '"</div>';
							$database_fail = TRUE;
						} else {
							// Check if the data is already in there - from a previous install attempt
							if (!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'review'"))) {
								// Get the SQL and import the data
								$queries = explode(";\n", file_get_contents('install.sql'));
								foreach ($queries as $query) {
									mysql_query($query);
								}
									}
							// Get the database config file and replace the strings with connection details
							$database_file = file_get_contents($path_to_database_file);
							$database_file = str_replace("{{hostname}}", $hostname, str_replace("{{database}}", $database, str_replace("{{username}}", $username, str_replace("{{password}}", $password, $database_file))));
							$file = fopen($path_to_config_file, 'wb+');
							if (is_writable($path_to_config_file)) {
								$write = fwrite($file, $database_file);
								if ($write !== FALSE) {
									// Database config file written ok
									fclose($file);
									if (file_exists($path_to_config_file)) {
										// Set flag to show site information form
										$show_info_form = TRUE;
									} else {
										echo '<div class="alert alert-danger" role="alert">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>';
										exit;
									}
										} else {
									echo '<div class="alert alert-danger" role="alert">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</div>';
											exit;
										}
									} else {
								// File is not writable
								echo '<div class="alert alert-danger" role="alert">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</font></div>';
										exit;
									}
								}
							}
						}
					}
				}
			if ($show_info_form === TRUE) {
				// User fills in some information about the site and chooses a manager username and password
				?>
				<h2>Site and Manager Details</h2>
				<b>We just need a little more information to complete your CIOpenReview installation... You can
					change
					any of this information after installation</b>
				<div class="col-md-8">
					<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<input type="hidden" name="info_form"/>

						<div class="form-group">
							<label for="sitename">Site Name</label>
							<input type="text" class="form-control" id="sitename" name="sitename"
							       placeholder="Site Name" value="<?php if (isset($_POST['sitename']))
								echo htmlspecialchars($_POST['sitename']); ?>">

							<p class="help-block">The name of your website - as you want it to appear in the browser
								title</p>
						</div>
						<div class="form-group">
							<label for="siteemail">Site Email</label>
							<input type="text" class="form-control" id="siteemail" name="siteemail"
							       placeholder="Site Email" value="<?php if (isset($_POST['siteemail']))
								echo htmlspecialchars($_POST['siteemail']); ?>">

							<p class="help-block">When emails are sent to users, they will be sent 'from' this email
								address. It is also where messages from the contact form are sent, and also appears in
								RSS feeds</p>
						</div>
						<div class="form-group">
							<label for="managerusername">Manager Username</label>
							<input type="text" class="form-control" id="managerusername" name="managerusername"
							       placeholder="Manager Username" value="<?php if (isset($_POST['managerusername']))
								echo htmlspecialchars($_POST['managerusername']); ?>">

							<p class="help-block">Enter a username for a Manager level user (you can create more
								Managers after installation). min 4 characters, max 15 characters, letters and numbers
								only</p>
						</div>
						<div class="form-group">
							<label for="managerpassword">Manager Password</label>
							<input type="password" class="form-control" id="managerpassword" name="managerpassword"
							       placeholder="Manager Password" value="<?php if (isset($_POST['managerpassword']))
								echo htmlspecialchars($_POST['managerpassword']); ?>">

							<p class="help-block">Enter a password for the Manager user... min 6 characters, max 15
								characters</p>
						</div>

						<input type="submit" name="info_submit" id="button" class="btn btn-success" value="Next Step"/>
					</form>
					</div>
				<?php
				exit;
			}
			if ((isset($_POST['settings_form'])) OR ($fill_in_form === TRUE) OR ($connect_fail === TRUE) OR ($database_fail === TRUE)) {
				// Settings form submitted - show database settings form
				?>
				<h2>Setting Up The Database Connection</h2>
				<b>CIOpenReview needs to connect to your database. Enter your database connection details below.
					If you
					don't know this information, contact your hosting provider for help.</b><br/><br/>
					<div class="col-md-8">
				<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="database_form"/>
					<div class="form-group">
<label for="hostname">Hostname</label>
<input type="text" class="form-control" id="hostname" name="hostname" placeholder="Hostname"  value="<?php if (isset($_POST['hostname']))
								echo htmlspecialchars($_POST['hostname']); ?>">
<p class="help-block">The hostname is often 'localhost' or '127.0.0.1' but databases are not always on the same server as your website files and folders. Check with your hosting provider for the correct settings for connecting to your database</p>
</div>
<div class="form-group">
<label for="database">Database Name</label>
<input type="text" class="form-control" id="database" name="database" placeholder="Database" value="<?php if (isset($_POST['database']))
								echo htmlspecialchars($_POST['database']); ?>">
<p class="help-block">Note: Hosts sometimes add a prefix to the database name such as 'yoursite_' so the full database name would be 'yoursite_database'</p>
</div>
<div class="form-group">
<label for="username">Database Username</label>
<input type="text" class="form-control" id="username" name="username" placeholder="Database Username" value="<?php if (isset($_POST['username']))
								echo htmlspecialchars($_POST['username']); ?>">
<p class="help-block">Enter a user name that has access to the database above. Again, sometimes the name will have a prefix</p>
</div>
<div class="form-group">
<label for="password">Database Password</label>
<input type="password" class="form-control" id="password" name="password" placeholder="Database Password" value="<?php if (isset($_POST['password']))
								echo htmlspecialchars($_POST['password']); ?>">
<p class="help-block">Enter the user's password</p>
</div>
					<input type="submit" name="database_submit" id="button" class="btn btn-success" value="Next Step"/>
				</form>

					</div>

				<?php
			} else {
			// Nothing submitted - show first page of installer
			?>
			<h2>Running Some Prechecks</h2>
				<b>Before installation, please verify that you meet the minumum requirements for CIOpenReview</b>
					<h3>Checking file and directory permissions</h3>
			<?php
			$config_writable = is_writable('../application/config/');
			$uploads_writable = is_writable('../uploads/');
			$uploads_ads_writable = is_writable('../uploads/ads/');
			$uploads_ads_images_writable = is_writable('../uploads/ads/images/');
			$uploads_site_logo_writable = is_writable('../uploads/site_logo/');
			$uploads_images_writable = is_writable('../uploads/images/');
			$uploads_captcha_writable = is_writable('../uploads/captcha/');
			$sitemap_writable = is_writable('../sitemap.xml');
			$permissions_fail = $config_writable !== TRUE || $sitemap_writable !== TRUE || $uploads_writable !== TRUE || $uploads_ads_writable !== TRUE || $uploads_ads_images_writable !== TRUE || $uploads_site_logo_writable !== TRUE || $uploads_images_writable !== TRUE || $uploads_captcha_writable !== TRUE;
			echo '<table class="table">';
			echo check_writeable_dir("application/config", $config_writable);
			echo check_writeable_dir("uploads", $uploads_writable);
			echo check_writeable_dir("uploads/ads", $uploads_ads_writable);
			echo check_writeable_dir("uploads/ads/images", $uploads_ads_images_writable);
			echo check_writeable_dir("uploads/site_logo", $uploads_site_logo_writable);
			echo check_writeable_dir("uploads/images", $uploads_images_writable);
			echo check_writeable_dir("uploads/captcha", $uploads_captcha_writable);
			echo check_writeable_dir("sitemap.xml", $sitemap_writable);
			echo '</table>';


			echo '<h3>Checking recommended server settings</h3>';
			?>
			<table class="table">
				<?php
				$post_max_size = ini_get('post_max_size') + 0;
				$upload_max_filesize = ini_get('upload_max_filesize') + 0;
				echo check_int_mb("post_max_size", $post_max_size, 8);
				echo check_int_mb("upload_max_filesize", $upload_max_filesize, 8);
				echo check_on_off("safe_mode", ini_get('safe_mode'), "OFF");
				echo check_on_off("register_globals", ini_get('register_globals'), "OFF");
				?>
			</table>

			<h3>Checking PHP configuration...</h3>
			<table class="table">

				<?php
				$filename = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'test_rewrite.php';
				$handle = @fopen($filename, "r");
				if ($handle !== FALSE) {

					$contents = @fread($handle, 100);
					@fclose($handle);
					$rewrite_fail = trim($contents) !== 'yes';
				} else {
					$rewrite_unknown = TRUE;
				}

				echo check_url_rewrite(trim($contents));
				echo check_function_exists("cURL Extension", "curl_init", "curl_init_fail");
				echo check_function_exists("GD Support", "gd_info", "gd_info_fail");
				?>
				</table>

				<?php
				echo '<h2>Testing Mail Function....</h2>';
				echo '(Note: this is just a simple email function test - you might need to check your mail settings if you have any problems with your site sending emails)<br /><br />';
				echo '<table class="table">';
				$headers = 'From: install@ciopenreview.com' . "\r\n" .
					'Reply-To: install@ciopenreview.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				if (mail('null@localhost', 'Test', 'Test', $headers)) {
					echo '<tr><td class="success"">The mail server seems to be responding correctly</td></tr>';
					} else {
					echo '<tr><td class="success"">The mail server <strong>IS NOT</strong> responding correctly</td></tr>';
					$mail_fail = TRUE;
				}
				?>
				</table>
				<h1>Test Results</h1>
				<?php if (($gd_info_fail === FALSE) && ($curl_init_fail === FALSE) && ($rewrite_fail === FALSE) && ($rewrite_unknown === FALSE) && ($mail_fail === FALSE) && ($permissions_fail === FALSE)) {
					echo '<div class="alert alert-success" role="alert"><b> Your server meets all the
							requirements for
							running CIOpenReview!</b></div>';
				} else {
					if ($curl_init_fail === TRUE) {
						echo '<div class="alert alert-info" role="alert"><b> Your server does not have the cURL extension installed - you can still run the script but you will not be able to grab images from remote sites when you create reviews</b></div>';
					}
					if ($rewrite_fail === TRUE) {
						echo '<div class="alert alert-info" role="alert"><b> URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have fixed this</b></div>';
					}
					if ($rewrite_unknown === TRUE) {
						echo '<div class="alert alert-info" role="alert"><b> Could not confirm that URL rewriting is enabled or working correctly. You can continue to install the script but if some URLs give you "not found" errors, you should check URL rewriting is enabled</b></div>';
					}
					if ($gd_info_fail === TRUE) {
						echo '<div class="alert alert-warning" role="alert"><b> You do not have GD enabled - this is used to resize images and for security features when visitors post comments on your site (CAPTCHA). If you run the script without it, these functions will not work correctly and you might see error messages</b></div>';
					}
					if ($mail_fail === TRUE) {
						echo '<div class="alert alert-info" role="alert"><b> The mail server did not respond to a simple email test. This means your site will not be able to send out emails</b></div>';
					}
					if ($permissions_fail === TRUE) {
						echo '<div class="alert alert-danger" role="alert"><b> Not all the required files and directories are writeable. You will not be able to install and run the script until you have made them writeable</b></div>';
					}
					echo 'If you need help with the requirements above, you should contact your hosting provider.<br /><br />';
				}
				?>
				<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="settings_form"/>
					<input type="submit" name="settings_submit" id="button" class="btn btn-success" value="Next Step"/>
				</form>
				<?php
				}
				?>
		</div>
		</div>
	</div>
</div>
</div>
</body>
</html>
