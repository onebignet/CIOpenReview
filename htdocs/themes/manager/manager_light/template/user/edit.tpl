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
{{ if(isset($message)): }}
<div class="callout callout-warning">
    <p>{{= $message }}</p>
</div>
{{ endif }}
{{ if ((($last_manager) && ($user->level >= 10))): }}
<div class="callout callout-warning">
    <p>{{= lang('manager_user_last_user') }}</p>
</div>
{{ endif }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/user/edit/' . $user->id }}">
    {{ if ((($last_manager) && ($user->level >= 10))): }}
    <input type="hidden" name="level" id="level" value="10"/>
    {{ endif }}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_user_edit_name') }}</h3>
            <div class="pull-right">
                {{= anchor('manager/users', lang('manager_user_manage_back_to_users'), array('class' => 'btn btn-default')) }}
            </div>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_user_form_name') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('name', $user->name) }}"
                           name="name"
                           id="name">
                    {{= form_error('name') }}
                    {{ if(isset($name_error)): }}
                    <span class="label label-danger">{{= $name_error }}</span>
                    {{ endif }}
                    <p class="help-block">{{= lang('manager_user_form_name_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_user_form_password') }}</label>
                    <input class="form-control" type="password" value="{{= set_value('password', $user->password) }}"
                           name="password"
                           id="password">
                    {{= form_error('password') }}
                    <p class="help-block">{{= lang('manager_user_form_password_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_user_form_email') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('email', $user->email) }}"
                           name="email"
                           id="email">
                    {{= form_error('email') }}
                    {{ if(isset($email_error)): }}
                    <span class="label label-danger">{{= $email_error }}</span>
                    {{ endif }}
                    <p class="help-block">{{= lang('manager_user_form_email_info') }}</p>
                </div>
            </div>
            {{ if ( ! (($last_manager) && ($user->level >= 10))): }}
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_user_form_level') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('level', $user->level) }}"
                           name="level"
                           id="level">
                    {{= form_error('level') }}
                    <p class="help-block">{{= lang('manager_user_form_level_info') }}</p>
                </div>
            </div>
            {{ endif }}
        </div>
        <div class="box-footer">
            <input type="submit" name="user_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_user_form_submit_button') }}"/>
        </div>
    </div>
</form>