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
<h2>Please login to CIOpenReview</h2>
<b>Before you can proceed with an upgrade, please log into CIOpenReview</b>
<div class="col-md-8">
	<form class="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="login_form"/>

		<div class="form-group">
			<label for="managerusername">Manager Username</label>
			<input type="text" class="form-control" id="managerusername" name="managerusername"
			       placeholder="Manager Username" value="<?php if (isset($_POST['managerusername']))
				echo htmlspecialchars($_POST['managerusername']); ?>">

			<p class="help-block">Enter your CIOpenReview Manager Username</p>
		</div>
		<div class="form-group">
			<label for="managerpassword">Manager Password</label>
			<input type="password" class="form-control" id="managerpassword" name="managerpassword"
			       placeholder="Manager Password" value="<?php if (isset($_POST['managerpassword']))
				echo htmlspecialchars($_POST['managerpassword']); ?>">

			<p class="help-block">Enter your CIOpenReview Manager Password</p>
		</div>

		<input type="submit" name="login_submit" id="button" class="btn btn-success" value="Validate"/>
	</form>
</div>
