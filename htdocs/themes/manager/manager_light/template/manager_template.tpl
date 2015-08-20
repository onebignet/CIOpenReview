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
    <link rel="stylesheet" type="text/css" href="{{= manager_template_path() }}design/style.css">
    <script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
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
<body>
<div id="container">
    <div id="header">
        {{ if ($this->secure->is_manager_logged_in($this->session)): }}
        <p>
            <span class="manager_log">{{= lang('manager_logged_in_as').' ('.$this->session->userdata('name').') | '.anchor('/manager/logout',lang('manager_log_out')) }}</span>
        </p>
        {{ endif }}
    </div>
    <div id="navbar">
        <div id="version">{{= lang('version_number').$this->Setting_model->get_setting_by_name('version_string') }}</div>
    </div>
    <div id="main_section">
        {{ if(isset($sidebar)): }}
        {{= $sidebar }}
        {{ endif }}
        {{= $content }}
    </div>
    <div id="footer">
        <p>Copyright &copy;2015<a class="footerlink" href="http://ciopenreview.com">CIOpenReview</a></p>
    </div>
</div>
</body>
</html>
