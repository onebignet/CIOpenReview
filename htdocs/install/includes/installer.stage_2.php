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
			<input type="text" class="form-control" id="hostname" name="hostname" placeholder="Hostname"
			       value="<?php if (isset($_POST['hostname']))
				       echo htmlspecialchars($_POST['hostname']); ?>">

			<p class="help-block">The hostname is often 'localhost' or '127.0.0.1' but databases are not
				always on the same server as your website files and folders. Check with your hosting
				provider for the correct settings for connecting to your database</p>
		</div>
		<div class="form-group">
			<label for="database">Database Name</label>
			<input type="text" class="form-control" id="database" name="database" placeholder="Database"
			       value="<?php if (isset($_POST['database']))
				       echo htmlspecialchars($_POST['database']); ?>">

			<p class="help-block">Note: Hosts sometimes add a prefix to the database name such as
				'yoursite_' so the full database name would be 'yoursite_database'</p>
		</div>
		<div class="form-group">
			<label for="username">Database Username</label>
			<input type="text" class="form-control" id="username" name="username"
			       placeholder="Database Username" value="<?php if (isset($_POST['username']))
				echo htmlspecialchars($_POST['username']); ?>">

			<p class="help-block">Enter a user name that has access to the database above. Again,
				sometimes the name will have a prefix</p>
		</div>
		<div class="form-group">
			<label for="password">Database Password</label>
			<input type="password" class="form-control" id="password" name="password"
			       placeholder="Database Password" value="<?php if (isset($_POST['password']))
				echo htmlspecialchars($_POST['password']); ?>">

			<p class="help-block">Enter the user's password</p>
		</div>
		<input type="submit" name="database_submit" id="button" class="btn btn-success"
		       value="Next Step"/>
	</form>

</div>
