{{
/**
* CIOpenReview
*
* An Open Source Review Site Script
*
* @package        CIOpenReview
* @subpackage           site
* @author        CIOpenReview.com
* @copyright            Copyright (c) 2011, OpenReviewScript.org
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


<div id="content">
    {{ if($message!=''): }}
    <h3 class="login_error">{{= $message }}</h3>

    <p>&nbsp;</p>
    {{ endif }}
    <form class="loginform" action="{{= site_url('/register/') }}" method="post">
        <div class="formblock">
            <div class="loginleft">
                <label>{{= lang('site_register_email') }}</label>
            </div>
            <div class="loginright">
                <input class="userpass" type="text" name="register_email" id="register_email"
                       value="{{= set_value('register_email') }}">
                {{= form_error('register_email') }}
            </div>
            <div class="loginleft">
                <label>{{= lang('site_register_username') }}</label>
            </div>
            <div class="loginright">
                <input class="userpass" type="text" name="register_username" id="register_username"
                       value="{{= set_value('register_username') }}">
                {{= form_error('register_username') }}
            </div>
            <div class="loginleft">
                <label>{{= lang('site_register_password') }}</label>
            </div>
            <div class="loginright">
                <input class="userpass" type="password" name="register_password" id="register_password" value="">
                {{= form_error('register_password') }}
            </div>
            <div class="loginright">
                <input type="submit" name="register_submit" id="login_button"
                       value="{{= lang('site_register_submit') }}">
            </div>
        </div>
    </form>
</div>