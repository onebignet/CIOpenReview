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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="content-language" content="en"/>
	<title>CIOpenReview - Install</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/custom.css">
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
					<img src="../themes/manager/manager_light/design/images/ciorlogo.png">

					<h1>CIOpenReview <?php echo $installer->get_version();?> Installer</h1>

					<div class="col-md-12">
						<p>Full installation Instructions Are Available on <u><a target="_blank"
						                                                         href="http://www.ciopenreview.com/guide/install">our
									Install Guide</a></u>
						</p>
					</div>
				</div>
			</div>
			<div class="row">