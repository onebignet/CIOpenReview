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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="content-language" content="en"/>
	<title>CIOpenReview - Install</title>
	<link href="style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="container">
	<div id="header">
	</div>
	<div id="navbar"></div>
	<div id="main_section">
		<div id="content">
			<h1>CIOpenReview Installer</h1>

			<p>Full installation Instructions Are Available At <u><a target="_blank"
			                                                         href="http://ciopenreview.com">GitHub</a></u>
			</p>
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
						echo '<h3><font color="red">The username should be at least 4 characters long</font></h3>';
					} elseif (strlen($username) > 15) {
						echo '<h3><font color="red">The username should be no longer than 15 characters</font></h3>';
					} elseif (!ctype_alnum($username)) {
						echo '<h3><font color="red">The username should be letters and/or numbers only</font></h3>';
					} elseif (strlen($password) < 4) {
						echo '<h3><font color="red">The password should be at least 4 characters long</font></h3>';
					} elseif (strlen($password) > 15) {
						echo '<h3><font color="red">The password should be no longer than 15 characters</font></h3>';
					} elseif (strlen($sitename) < 1) {
						echo '<h3><font color="red">Please enter the Site Name</font></h3>';
					} elseif (strlen($siteemail) < 1) {
						echo '<h3><font color="red">Please enter the Site Email Address</font></h3>';
					} else {
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
												href="http://ciopenreview.com" target="_blank">CIOpenReview Page</a></b>
									</li>
								</ul>
								<br/>
								<p><b><span style="color:#ff0000;">IMPORTANT - You must now delete the /install folder, if you don't your site could be vulnerable to security attacks</span></b>
								</p>
								<?php
								exit;
							}
						}
						echo '<h3><font color="red">There was a problem connecting to the database</font></h3>';
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
						echo '<h3><font color="red">You have to fill in every form item</font></h3>';
						$fill_in_form = TRUE;
					} else {
						$connection = mysql_connect($hostname, $username, $password);
						if (!$connection) {
							// MySQL connection error
							echo '<h3><font color="red">Can\'t connect to the database using the details you provided, check the information and try again</font></h3><br /><b>This is the actual error message which might help you solve the problem:</b> "' . mysql_error() . '"';
							$connect_fail = TRUE;
						} else {
							// Successful connection - try to select database
							if (!mysql_select_db($database)) {
								echo '<h3><font color="red">Connected successfully but the database "' . htmlspecialchars($database) . '" was not found.</font></h3><br /><b>This is the actual error message which might help you solve the problem:</b> "' . mysql_error() . '"';
								$database_fail = TRUE;
							} else {
								// Check if the data is already in there - from a previous install attempt
								if (!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'review'"))) {
									// Get the SQL and import the data
									$queries = explode("\n", file_get_contents('install.sql'));
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
											echo '<h3><font color="red">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</font></h3><br />';
											exit;
										}
									} else {
										echo '<h3><font color="red">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</font></h3><br />';
										exit;
									}
								} else {
									// File is not writable
									echo '<h3><font color="red">There was a problem writing the database config file, please check the file \'application/config/database.php\' is writable then click <a href="">here</a> to try again</font></h3><br />';
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
				<b>We just need a little more information to complete your CIOpenReview installation... You can change
					any of this information after installation</b>
				<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="info_form"/>

					<div class="formblock">
						<div class="formleft">
							<label>Site Name
								<span class="small">The name of your website - as you want it to appear in the browser title</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="sitename" value="<?php if (isset($_POST['sitename']))
								echo htmlspecialchars($_POST['sitename']); ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>Site email
								<span class="small">When emails are sent to users, they will be sent 'from' this email address. It is also where messages from the contact form are sent, and also appears in RSS feeds</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="siteemail" value="<?php if (isset($_POST['siteemail']))
								echo htmlspecialchars($_POST['siteemail']); ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>Manager username
								<span class="small">Enter a username for a Manager level user (you can create more Managers after installation). min 4 characters, max 15 characters, letters and numbers only</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="managerusername" value="<?php if (isset($_POST['managerusername']))
								echo htmlspecialchars($_POST['managerusername']); ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>Manager password
								<span class="small">Enter a password for the Manager user... min 6 characters, max 15 characters</span>
							</label>
						</div>
						<div class="formright">
							<input type="password" name="managerpassword"/>
						</div>
					</div>
					<input type="submit" name="info_submit" id="button" value="Next Step"/>
				</form>
				<?php
				exit;
			}
			if ((isset($_POST['settings_form'])) OR ($fill_in_form === TRUE) OR ($connect_fail === TRUE) OR ($database_fail === TRUE)) {
				// Settings form submitted - show database settings form
				?>
				<h2>Setting Up The Database Connection</h2>
				<b>CIOpenReview needs to connect to your database. Enter your database connection details below. If you
					don't know this information, contact your hosting provider for help.</b><br/><br/>
				<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="database_form"/>

					<div class="formblock">
						<div class="formleft">
							<label>Hostname
								<span class="small">The hostname is often 'localhost' or '127.0.0.1' but databases are not always on the same server as your website files and folders. Check with your hosting provider for the correct settings for connecting to your database.</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="hostname" value="<?php if (isset($_POST['hostname']))
								echo htmlspecialchars($_POST['hostname']); ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>Database Name
								<span class="small">Note: Hosts sometimes add a prefix to the database name such as 'yoursite_' so the full database name would be 'yoursite_database'</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="database" value="<?php if (isset($_POST['database']))
								echo htmlspecialchars($_POST['database']); ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>User Name
								<span class="small">Enter a user name that has access to the database above. Again, sometimes the name will have a prefix</span>
							</label>
						</div>
						<div class="formright">
							<input type="text" name="username" value="<?php if (isset($_POST['username']))
								echo htmlspecialchars($_POST['username']) ?>"/>
						</div>
					</div>
					<div class="formblock">
						<div class="formleft">
							<label>Password
								<span class="small">Enter the user's password</span>
							</label>
						</div>
						<div class="formright">
							<input type="password" name="password"/>
						</div>
					</div>
					<input type="submit" name="database_submit" id="button" value="Next Step"/>
				</form>
				<?php
			} else {
				// Nothing submitted - show first page of installer
				echo '<h2>Checking file and directory permissions</h2>';
				$config_writable = is_writable('../application/config/');
				$uploads_writable = is_writable('../uploads/');
				$uploads_ads_writable = is_writable('../uploads/ads/');
				$uploads_ads_images_writable = is_writable('../uploads/ads/images/');
				$uploads_site_logo_writable = is_writable('../uploads/site_logo/');
				$uploads_images_writable = is_writable('../uploads/images/');
				$uploads_captcha_writable = is_writable('../uploads/captcha/');
				$sitemap_writable = is_writable('../sitemap.xml');
				$permissions_fail = $config_writable !== TRUE || $sitemap_writable !== TRUE || $uploads_writable !== TRUE || $uploads_ads_writable !== TRUE || $uploads_ads_images_writable !== TRUE || $uploads_site_logo_writable !== TRUE || $uploads_images_writable !== TRUE || $uploads_captcha_writable !== TRUE;
				echo '<b>"application/config"</b> directory is ';
				echo $config_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads"</b> directory is ';
				echo $uploads_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads/ads"</b> directory is ';
				echo $uploads_ads_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads/ads/images"</b> directory is ';
				echo $uploads_ads_images_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads/site_logo"</b> directory is ';
				echo $uploads_site_logo_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads/images"</b> directory is ';
				echo $uploads_images_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"uploads/captcha"</b> directory is ';
				echo $uploads_captcha_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<b>"sitemap.xml"</b> file is ';
				echo $sitemap_writable ? 'writeable ' . $image_start . 'tick' : 'not writeable ' . $image_start . 'cross';
				echo $image_end . '<br />';
				echo '<h2>Checking recommended server settings...</h2>';
				?>
				<div class="formblock">
					<div class="formleft">
						<label>post_max_size
						</label>
					</div>
					<div class="formright">
						<?php
						echo '<b>' . ini_get('post_max_size') . '</b>&nbsp;';
						$post_max_size = ini_get('post_max_size') + 0;
						echo ($post_max_size < 8) ? 'we recommend at least 8M' : $image_start . 'tick' . $image_end;
						?>
					</div>
				</div>
				<div class="formblock">
					<div class="formleft">
						<label>upload_max_filesize
						</label>
					</div>
					<div class="formright">
						<?php
						echo '<b>' . ini_get('upload_max_filesize') . '</b>&nbsp;&nbsp;';
						$upload_max_filesize = ini_get('upload_max_filesize') + 0;
						echo ($upload_max_filesize < 8) ? 'we recommend at least 8M' : $image_start . 'tick' . $image_end;
						?>
					</div>
				</div>
				<div class="formblock">
					<div class="formleft">
						<label>safe_mode
						</label>
					</div>
					<div class="formright">
						<?php
						echo (ini_get('safe_mode') == 1) ? 'On&nbsp;&nbsp;' . $image_start . 'cross' . $image_end . '&nbsp;Set "safe_mode" to "Off"' : 'Off&nbsp;&nbsp;' . $image_start . 'tick' . $image_end;
						?>
					</div>
				</div>
				<div class="formblock">
					<div class="formleft">
						<label>register_globals
						</label>
					</div>
					<div class="formright">
						<?php
						echo (ini_get('register_globals') == 1) ? 'On&nbsp;&nbsp;' . $image_start . 'cross' . $image_end . '&nbsp;Set "register_globals" to "Off" if possible' : 'Off&nbsp;&nbsp;' . $image_start . 'tick' . $image_end;
						?>
					</div>
				</div>
				<h2>Checking PHP configuration...</h2>
				<?php
				echo 'URL Rewriting (mod_rewrite):';
				$filename = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'test_rewrite.php';
				$handle = @fopen($filename, "r");
				if ($handle !== FALSE) {

					$contents = @fread($handle, 100);
					@fclose($handle);
					$rewrite_fail = trim($contents) !== 'yes';
				} else {
					$rewrite_unknown = TRUE;
				}
				echo $image_start;
				echo (trim($contents) == 'yes') ? 'tick' . $image_end : 'cross' . $image_end . ' <b> URL rewrites are required</b>';
				echo '<br /><br /><br />';
				echo 'cURL Support: ';
				echo $image_start;
				echo(function_exists('curl_init') ? 'tick' . $image_end : 'cross' . $image_end . ' <b> php cURL extension is required</b>');
				$cURL_fail = !function_exists('curl_init');
				echo '<br /><br /><br />';
				?>

				<?php
				if (function_exists('gd_info')) {
					$gd_array = gd_info();
					echo 'GD Version: <b>' . $gd_array['GD Version'] . '</b><br /><br />';
					echo 'FreeType Support: ';
					echo $image_start;
					echo ($gd_array['FreeType Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '<br /><br />';
					echo 'FreeType Linkage: <b>';
					echo $gd_array['FreeType Linkage'];
					echo '</b><br /><br />';
					echo 'GIF Read Support: <b>';
					echo $image_start;
					echo ($gd_array['GIF Read Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br /><br />';
					echo 'GIF Create Support: <b>';
					echo $image_start;
					echo ($gd_array['GIF Create Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br /><br />';
					echo 'JPEG Support: <b>';
					echo $image_start;
					echo (($gd_array['JPEG Support'] === TRUE) OR ($gd_array['JPG Support'] === TRUE)) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br /><br />';
					echo 'PNG Support: <b>';
					echo $image_start;
					echo ($gd_array['PNG Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br /><br />';
					echo 'WBMP Support: <b>';
					echo $image_start;
					echo ($gd_array['WBMP Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br /><br />';
					echo 'XBM Support: <b>';
					echo $image_start;
					echo ($gd_array['XBM Support'] === TRUE) ? 'tick' : 'cross';
					echo $image_end;
					echo '</b><br />';
				} else {
					echo 'No GD Support ' . $image_start . 'cross' . $image_end . '<b> php GD extension is required</b><br />';
					$gd_fail = TRUE;
				}
				echo '<br />';
				echo '<h2>Testing Mail Function....</h2>';
				$headers = 'From: install@ciopenreview.com' . "\r\n" .
					'Reply-To: install@ciopenreview.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				if (mail('null@localhost', 'Test', 'Test', $headers)) {
					echo '<b>The mail server seems to be responding correctly ' . $image_start . 'tick' . $image_end . '</b><br /><br />';
					echo '(Note: this is just a simple email function test - you might need to check your mail settings if you have any problems with your site sending emails)<br /><br />';
				} else {
					echo 'Email Test failed&nbsp;&nbsp;' . $image_start . 'cross' . $image_end . '&nbsp;&nbsp;Please check your mail server and settings<br /><br />';
					$mail_fail = TRUE;
				}
				?>
				<h1>Test Results</h1>
				<?php if (($gd_fail === FALSE) && ($cURL_fail === FALSE) && ($rewrite_fail === FALSE) && ($rewrite_unknown === FALSE) && ($mail_fail === FALSE) && ($permissions_fail === FALSE)) {
					?>
					<h2><?php echo $image_start . 'tick' . $image_end; ?><b> Your server meets all the requirements for
							running CIOpenReview!</b></h2>
					<?php
				} else {
					if ($cURL_fail === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> Your server does not have the cURL extension installed - you can still run the script but you will not be able to grab images from remote sites when you create reviews</b><br /><br />';
					}
					if ($rewrite_fail === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> URL rewriting is not working correctly or is not enabled. You can continue to install the script but URLs will not work until you have fixed this</b><br /><br />';
					}
					if ($rewrite_unknown === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> Could not confirm that URL rewriting is enabled or working correctly. You can continue to install the script but if some URLs give you "not found" errors, you should check URL rewriting is enabled</b><br /><br />';
					}
					if ($gd_fail === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> You do not have GD enabled - this is used to resize images and for security features when visitors post comments on your site (CAPTCHA). If you run the script without it, these functions will not work correctly and you might see error messages</b><br /><br />';
					}
					if ($mail_fail === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> The mail server did not respond to a simple email test. This means your site will not be able to send out emails</b><br /><br />';
					}
					if ($permissions_fail === TRUE) {
						echo $image_start . 'cross' . $image_end . '<b> Not all the required files and directories are writeable. You will not be able to install and run the script until you have made them writeable</b><br /><br />';
					}
					echo 'If you need help with the requirements above, you should contact your hosting provider.<br /><br />';
				}
				?>
				<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="settings_form"/>
					<input type="submit" name="settings_submit" id="button" value="Next Step"/>
				</form>
				<?php
			}
			?>
		</div>
	</div>
</div>
</body>
</html>
