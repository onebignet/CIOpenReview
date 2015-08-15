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
    <div class="header_row">{{= lang('manager_articles_title') }}</div>
    <p class="nav_links"><b>{{= anchor('manager/article/add',lang('manager_articles_add_article')) }}</b></p>

    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
    <div class="break"></div>
    {{ foreach ($allarticles as $result): }}
    <div class="manager_row">
        <p class="manager_left">{{= anchor('article/show/'.$result->seo_title, character_limiter($result->title, 50),'target="_blank"') }}</p>

        <p class="manager_narrow">{{= anchor('manager/article/edit/'.$result->id, lang('manager_article_list_edit')) }}</p>

        <p class="manager_narrow">{{= anchor('manager/article/delete/'.$result->id, lang('manager_article_list_delete'),'id="darkblue"') }}</p>
    </div>
    {{ endforeach }}
    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
</div>