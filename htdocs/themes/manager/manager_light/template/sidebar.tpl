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


<ul class="sidebar-menu">
    <li>
        <a href="{{= base_url('manager/home') }}">
            <i class="fa fa-home"></i> <span>{{= lang('manager_menu_home') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/reviews') }}">
            <i class="fa fa-comment"></i> <span>{{= lang('manager_menu_reviews') }}</span>
        </a>
    </li>
    {{ if (pending_reviews_count()>0): }}
    <ul class="treeview-menu menu-open" style="display: block;">
        <li>
            <a href="{{= base_url('manager/reviews/pending') }}">
                <i class="fa fa-circle-o"></i><span>{{= lang('manager_menu_reviews_pending') }} </span>
                <span class="label label-primary pull-right">{{= pending_reviews_count() }}</span>
            </a>
        </li>
    </ul>
    {{ endif }}
    {{ if (pending_comments_count()>0): }}
    <ul class="treeview-menu menu-open" style="display: block;">
        <li>
            <a href="{{= base_url('manager/comments/pending') }}">
                <i class="fa fa-circle-o"></i><span>{{= lang('manager_menu_comments_pending') }} </span>
                <span class="label label-primary pull-right">{{= pending_comments_count() }}</span>
            </a>
        </li>
    </ul>
    {{ endif }}
    <li>
        <a href="{{= base_url('manager/categories') }}">
            <i class="fa fa-list"></i> <span>{{= lang('manager_menu_categories') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/features') }}">
            <i class="fa fa-bell"></i> <span>{{= lang('manager_menu_features') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/ratings') }}">
            <i class="fa fa-star"></i> <span>{{= lang('manager_menu_ratings') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/articles') }}">
            <i class="fa fa-book"></i> <span>{{= lang('manager_menu_articles') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/pages') }}">
            <i class="fa fa-pagelines"></i> <span>{{= lang('manager_menu_custom_pages') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/ads') }}">
            <i class="fa fa-dollar"></i> <span>{{= lang('manager_menu_ads') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/users') }}">
            <i class="fa fa-users"></i> <span>{{= lang('manager_menu_users') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/site_settings/edit') }}">
            <i class="fa fa-gear"></i> <span>{{= lang('manager_menu_site_settings') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/theme_settings/edit') }}">
            <i class="fa fa-eyedropper"></i> <span>{{= lang('manager_menu_theme_settings') }}</span>
        </a>
    </li>
    <li>
        <a href="{{= base_url('manager/maintenance') }}">
            <i class="fa fa-wrench"></i> <span>{{= lang('manager_menu_maintenance') }}</span>
        </a>
    </li>
    <li>
        <a href="http://ciopenreview.com">
            <i class="fa fa-support"></i> <span>{{= lang('manager_menu_support') }}</span>
        </a>
    </li>
</ul>
