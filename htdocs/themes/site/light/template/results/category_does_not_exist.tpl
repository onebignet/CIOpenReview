{{
/**
*    This file is part of CIOpenReview - free review software licensed under the GNU General Public License version 2
*    Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
*    http://CiOpenReview.com
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
<!-- START OF 'CONTENT' SECTION -->
<div id="content">
    <!-- START OF CONDITIONAL 'FEATURED' SECTION -->
    {{ if ($featured_reviews >= $featured_minimum): }}
    <div class="featured">
        <h2 class="heading">{{= lang('review_page_featured_reviews') }}</h2>

        <div id="featured">
            <div class="featured_block">
                <p class="featured_title">{{= anchor('review/show/' . $featured[0]->seo_title, highlight_keywords(character_limiter($featured[0]->title,40),$keywords )) }}</p>

                <p class="featured_description">{{= character_limiter(strip_tags($featured[0]->description), 180) }}</p>
                {{= anchor('review/show/' . $featured[0]->seo_title,lang('featured_more'),'class="read_more"') }}
            </div>
            <div class="featured_block">
                <p class="featured_title">{{= anchor('review/show/' . $featured[1]->seo_title, highlight_keywords(character_limiter($featured[1]->title,40),$keywords )) }}</p>

                <p class="featured_description">{{= character_limiter(strip_tags($featured[1]->description), 180) }}</p>
                {{= anchor('review/show/' . $featured[1]->seo_title,lang('featured_more'),'class="read_more"') }}
            </div>
            <div class="featured_block">
                <p class="featured_title">{{= anchor('review/show/' . $featured[2]->seo_title, highlight_keywords(character_limiter($featured[2]->title,40),$keywords )) }}</p>

                <p class="featured_description">{{= character_limiter(strip_tags($featured[2]->description), 180) }}</p>
                {{= anchor('review/show/' . $featured[2]->seo_title,lang('featured_more'),'class="read_more"') }}
            </div>
        </div>
    </div>
    {{ endif }}
    <!-- END OF 'FEATURED' SECTION -->
    <h2 class="heading">{{= lang('results_cat_not_exist_1') }}{{= $category_seo_name }}{{= lang('results_cat_not_exist_2') }}</h2>
</div>
<!-- END OF 'CONTENT' SECTION -->