{{
/**
* CIOpenReview
*
* An Open Source Review Site Script
*
* @package        CIOpenReview
* @subpackage          manager
* @author        CIOpenReview.com
* @copyright           Copyright (c) 2015 MindBrite.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
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
}}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>{{= lang('manager_page_title') }} - {{= $this->config->item('site_name') }}</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="{{= manager_template_path() }}assets/css/default.css">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{= manager_template_path() }}assets/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{= manager_template_path() }}assets/css/skins/skin-green.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            mode: "exact",
            elements: "description,pagecontent",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code",
                "insertdatetime media nonbreaking table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern imagetools"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons",
        });






    </script>
</head>
<body class="hold-transition skin-green sidebar-mini">
<!-- START OF NAVIGATION -->
<header class="main-header">
    <a class="logo"
       href="#"><span
                class="logo-lg"> CIOpenReview {{= lang('version_number').$this->Setting_model->get_setting_by_name('version_string') }}</span></a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{ if ($this->secure->is_manager_logged_in($this->session)): }}
                <li> {{= anchor('/manager/logout',lang('manager_logged_in_as').' ('.$this->session->userdata('name').') | '.lang('manager_log_out')) }}</li>
                {{ endif }}
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
    <!-- /.container-fluid -->
</header>
<!-- END OF NAVIGATION -->
<aside class="main-sidebar">
    <section class="sidebar">
        {{ if(isset($sidebar)): }}
        {{= $sidebar }}
        {{ endif }}
    </section>
</aside>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{= $page_header }}
            <small>{{= $page_description }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{= base_url('manager/home') }}"><i class="fa fa-dashboard"></i> Manager</a></li>
            <li class="active">{{= $current_page }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        {{= $content }}

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <p>Copyright &copy;2015 - {{= date("Y") }} <a class="footerlink" href="http://ciopenreview.com">CIOpenReview</a>
    </p>
</footer>
<!-- jQuery 2.2.4 -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="{{= manager_template_path() }}assets/js/app.min.js"></script>

</body>
</html>
