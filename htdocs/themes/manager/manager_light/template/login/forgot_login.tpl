{{
/**
* CIOpenReview
*
* An Open Source Review Site Script based on OpenReviewScript
*
* @package        CIOpenReview
* @subpackage          manager
* @author        CIOpenReview.com
* @copyright           Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
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

    {{ if($message!=''): }}
    <div class="callout callout-warning">
        <p>{{= $message }}</p>
    </div>
    {{ endif }}

    <div class="col-md-6 col-md-offset-3">
        <form class="loginform" action="{{= site_url('/manager/forgot_login') }}" method="post">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Forgotten Password</h3>
                </div>
                <div class="box-body">
                    <label>{{= lang('manager_login_forgot_email_info') }}</label>
                    <input class="form-control" type="text" type="text" name="login_email" id="login_email"
                           value="{{= set_value('login_email') }}">
                    {{= form_error('login_email') }}
                </div>
                <div class="box-footer">
                    <button type="submit" name="login_forgot_submit" id="button"
                            class="btn btn-primary btn-success"
                            value="{{= lang('manager_login_forgot_submit') }}">{{= lang('manager_login_forgot_submit') }}</button>
                </div>
        </form>
    </div>
</div>