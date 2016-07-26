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
<div id="content">
    <p>&nbsp;</p>

    <p>&nbsp;</p>
    {{ if($message!=''): }}
    <p>&nbsp;</p>

    <h3 class="login_error">{{= $message }}</h3>

    <p>&nbsp;</p>

    {{ endif }}
    <div class="col-md-6 col-md-offset-3">
        <form class="loginform" action="{{= site_url('/manager/login/') }}" method="post">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Login to CIOpenReview Manager</h3>
                </div>
                <div class="box-body">
                    <label>{{= lang('manager_login_username') }}</label>
                    <input class="form-control" type="text" placeholder="Username" name="login_username"
                           id="login_username">
                    <br>
                    <label>{{= lang('manager_login_password') }}</label>
                    <input class="form-control" type="password" placeholder="Password" name="login_password"
                           id="login_password">

                </div>
                <div class="box-footer">
                    <div class="loginforgot">
                        <label>{{= anchor('manager/forgot_login',lang('manager_login_forgot')) }}</label>
                    </div>
                    <button type="submit" name="login_submit" id="button"
                            class="btn btn-primary btn-success" value="{{= lang('manager_login_submit') }}">{{= lang('manager_login_submit') }}</button>
                </div>
        </form>
    </div>