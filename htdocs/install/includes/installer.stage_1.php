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
if (!$installer) {
	exit('No direct script access allowed');
}

$post_max_size = ini_get('post_max_size') + 0;
$upload_max_filesize = ini_get('upload_max_filesize') + 0;

?>
<h2>Running Some Prechecks</h2>
<b>Before installation, please verify that you meet the minumum requirements for CIOpenReview</b>
<h3>Checking file and directory permissions</h3>

<table class="table">
	<tr><?php $installer->is_dir_writable("application/config"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads/ads"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads/ads/images"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads/site_logo"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads/images"); ?></tr>
	<tr><?php $installer->is_dir_writable("uploads/captcha"); ?></tr>
	<tr><?php $installer->is_dir_writable("sitemap.xml"); ?></tr>
</table>
<h3>Checking recommended server settings</h3>

<table class="table">
	<tr><?php $installer->is_more_than("post_max_size", $post_max_size, 8, "MB"); ?></tr>
	<tr><?php $installer->is_more_than("upload_max_filesize", $upload_max_filesize, 8, "MB"); ?></tr>
	<tr><?php $installer->is_on_or_off("safe_mode", ini_get('safe_mode'), "OFF"); ?></tr>
	<tr><?php $installer->is_on_or_off("register_globals", ini_get('register_globals'), "OFF"); ?></tr>

</table>

<h3>Checking PHP configuration...</h3>
<table class="table">
	<tr><?php $installer->check_server_software(); ?></tr>
	<tr><?php $installer->check_php_version(); ?></tr>
	<tr><?php $installer->check_url_rewrite(); ?></tr>
	<tr><?php $installer->check_function_exists("cURL Extension", "curl_init", "curl_init_fail"); ?></tr>
	<tr><?php $installer->check_function_exists("GD Support", "gd_info", "gd_info_fail"); ?></tr>
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
<?php $installer->show_precheck_errors_and_warnings(); ?>
<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="settings_form"/>
	<input type="submit" name="settings_submit" id="button"
	       class="btn btn-success " <?php echo $installer->is_critical_error() ? 'disabled="disabled"' : ""; ?>
	       value="Next Step"/>
</form>
<p style="margin-top: 50px;"></p>
