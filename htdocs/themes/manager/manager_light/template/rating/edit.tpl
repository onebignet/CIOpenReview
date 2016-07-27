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
<div class="callout callout-warning" xmlns="http://www.w3.org/1999/html">
    <p>{{= $message }}</p>
</div>
{{ endif }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/rating/edit/' . $rating->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_rating_edit_title') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_rating_form_name') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('name', $rating->name) }}"
                           name="name"
                           id="name">
                    {{= form_error('name') }}
                    <p class="help-block">{{= lang('manager_rating_form_name_info') }}</p>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="rating_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_rating_form_submit_button') }}"/>
        </div>
    </div>
</form>