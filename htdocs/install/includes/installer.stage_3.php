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

if ($installer->db_exists()){
	$site_name = $installer->get_site_setting("site_summary_text");
	$site_email = $installer->get_site_setting("site_email");
} else {
	$site_name = $_POST['sitename'];
	$site_email = $_POST['siteemail'];
}

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
			       placeholder="Site Name" value="<?php if (isset($site_name))
				echo htmlspecialchars($site_name); ?>">

			<p class="help-block">The name of your website - as you want it to appear in the browser
				title</p>
		</div>
		<div class="form-group">
			<label for="siteemail">Site Email</label>
			<input type="text" class="form-control" id="siteemail" name="siteemail"
			       placeholder="Site Email" value="<?php if (isset($site_email))
				echo htmlspecialchars($site_email); ?>">

			<p class="help-block">When emails are sent to users, they will be sent 'from' this email
				address. It is also where messages from the contact form are sent, and also appears in
				RSS feeds</p>
		</div>
		<?php if ($installer->db_exists()) { ?>
			<input type="hidden" name="managerusername" value="predefined">
			<input type="hidden" name="managerpassword" value="predefined">
		<?php } else { ?>
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
		<?php } ?>
		<input type="submit" name="info_submit" id="button" class="btn btn-success" value="Next Step"/>
	</form>
</div>
