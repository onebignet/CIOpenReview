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

    <h2 class="heading">{{= lang('home_latest_articles') }}</h2>

    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>

    {{ foreach ($allarticles as $index=>$article): }}
    <!-- START OF CONDITIONAL 'ADS' SECTION -->
    {{ if ($article_ads): }}
    <!-- CHANGE THE NUMBERS IN THE NEXT LINE TO CHANGE THE POSITIONS IN THE LIST WHERE ADS APPEAR -->
    {{ if (in_array($index, array(2, 6))) : }}
    <div class="block">
        {{ foreach ($article_ads as $ad) : }}
        {{ if ($ad->visible_in_lists > 0): }}
        <div class="lists_ad_block">
            {{ if ($ad->image != ''): }}
            <div class="ad_image">
                {{= anchor($ad->link, $ad->image) }}
            </div>
            {{ endif }}
            {{ if ($ad->text != ''): }}
            <div class="ad_text">
                {{= character_limiter($ad->text) }}
            </div>
            {{ endif }}
        </div>
        {{ endif }}
        {{ break }}
        {{ endforeach }}
    </div>
    {{ endif }}
    {{ endif }}
    <!-- END OF 'ADS' SECTION -->

    <!-- START OF 'ARTICLE ITEM' SECTION -->
    <div class="article_item">
        <div class="title">{{= anchor('article/show/'.$article->seo_title,$article->title) }}</div>
        <div class="description">{{= word_limiter($article->description,50) }}
            <div class="more_button_link">
                {{= anchor('article/show/'.$article->seo_title, lang('lists_read_more'),'class="more_button"') }}
            </div>
        </div>
    </div>
    <!-- END OF 'REVIEW ITEM' SECTION -->

    {{ endforeach }}
    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
</div>
<!-- END OF 'CONTENT' SECTION -->