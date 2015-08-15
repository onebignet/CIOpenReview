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
    <div class="header_row">{{= lang('manager_users_title') }}</div>
    <p class="nav_links"><b>{{= anchor('manager/user/add',lang('manager_users_add_user')) }}</b></p>

    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
    <div class="break"></div>
    {{ foreach ($allusers as $result): }}
    <div class="manager_row">
        <p class="manager_left">{{= $result->name }}</p>

        <p class="manager_narrow">{{= $result->level }}</p>

        <p class="manager_narrow">{{= anchor('manager/user/edit/'.$result->id, lang('manager_user_list_edit')) }}</p>
        {{ if(($enough_managers_to_delete) OR ($result->level<10)): }}
        <p class="manager_narrow">{{= anchor('manager/user/delete/'.$result->id, lang('manager_user_list_delete'),'id="darkblue"') }}</p>
        {{ else: }}
        <p class="manager_narrow" style="width:150px;">{{= lang('manager_user_list_cant_delete') }}</p>
        {{ endif }}
    </div>
    {{ endforeach }}
    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
</div>