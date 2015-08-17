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
$version="1.0.2beta";
}}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>{{= lang('manager_page_title') }} - {{= $this->config->item('site_name') }}</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="{{= manager_template_path() }}design/style.css">
    <script type="text/javascript" src="{{=  base_url() }}libs/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
            mode: "exact",
            theme: "advanced",
            elements: "description,pagecontent",
            plugins: "emotions,spellchecker,advhr,insertdatetime,preview",
            // Theme options - button# indicated the row# only
            theme_advanced_buttons1: "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
            theme_advanced_buttons2: "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,forecolor,backcolor|,advhr,,removeformat,|,charmap",
            theme_advanced_buttons3: "",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "bottom",
            theme_advanced_resizing: true
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
        <div id="version">{{= lang('version_number').$version }}</div>
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
