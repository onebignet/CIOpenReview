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
<div class="box">

    <div class="box-header">
        <h3 class="box-title">{{= lang('manager_articles_title') }}</h3>
        {{= anchor('manager/article/add', lang('manager_articles_add_article'), array('class' => 'btn btn-success', 'style' => 'margin-left: 20px;')) }}

        <div class="box-tools">
            {{= lang('manager_page') }}{{= $pagination }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
            <tr>
                <th class="col-md-10">Article Name</th>
                <th>Actions</th>
            </tr>
            {{ foreach ($allarticles as $result): }}
            <tr>
                <td>{{= anchor('article/show/'.$result->seo_title, character_limiter($result->title, 50),'target="_blank"') }}</td>
                <td>
                    {{= anchor('manager/article/edit/'.$result->id, lang('manager_article_list_edit'), array('class' => 'btn btn-default')) }}
                    {{= anchor('manager/article/delete/'.$result->id, lang('manager_article_list_delete'), array('class' => 'btn btn-danger')) }}
                </td>
            </tr>
            {{ endforeach }}
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>