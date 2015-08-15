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
<!-- START OF SIDEBAR SECTION -->
<div id="sidebar">

    <!-- START OF CONDITIONAL 'SEARCH' SECTION -->
    {{ if ($show_search): }}
    <div id="search">
        <p>{{= lang('sidebar_search') }}</p>

        <form method="post" action="{{= site_url("results/search") }}">
            <input type="text" name="keyword" id="search-text" value=""/>
            <input type="submit" id="search-submit" value="GO"/>
        </form>
    </div>
    {{ endif }}
    <!-- END OF 'SEARCH' SECTION -->

    <!-- START OF CONDITIONAL 'CATEGORIES' SECTION -->
    {{ if($show_categories): }}
    <div id="menu_header">
        <p>{{= lang('sidebar_categories') }}</p>
    </div>
    <ul>
        {{ foreach ($categories as $index=>$category): }}
        <li><p>{{= anchor('results/category/'.$category->seo_name, $category->name) }}</p></li>
        {{ if ($index+1 != count($categories)): }}
        <div class="menu_separator"></div>{{ endif }}
        {{ endforeach }}
    </ul>
    {{ endif }}
    <!-- END OF 'CATEGORIES' SECTION -->

    <!-- START OF CONDITIONAL 'RECENT REVIEWS' SECTION -->
    {{ if(($show_recent)&&($recent)): }}
    <div id="menu_header">
        <p>{{= lang('sidebar_recent') }}</p>
    </div>
    <ul>
        {{ foreach ($recent as $index=>$review): }}
        <li>
            <p>{{= anchor('review/show/'.$review->seo_title, '&#8220;'.character_limiter($review->title,50)).'&#8221;' }}</p>
        </li>
        {{ if ($index+1 != count($recent)): }}
        <div class="menu_separator"></div>{{ endif }}
        {{ endforeach }}
    </ul>
    {{ endif }}
    <!-- END OF 'RECENT REVIEWS' SECTION -->

    <!-- START OF CONDITIONAL 'SIDEBAR ADS' SECTION -->
    {{ if($sidebar_ads): }}
    {{ foreach ($sidebar_ads as $ad) : }}
    {{ if ($ad->visible_in_sidebar>0): }}
    {{ if ($ad->image!=''): }}
    <p class="sidebar_ad">
        {{= anchor($ad->link, $ad->image) }}
    </p>
    {{ endif }}
    {{ if ($ad->text!=''): }}
    <p class="sidebar_ad">
        {{= character_limiter($ad->text) }}
    </p>
    {{ endif }}
    {{ endif }}
    {{ endforeach }}
    {{ endif }}
    <!-- END OF 'SIDEBAR ADS' SECTION -->

    <!-- START OF CONDITIONAL 'TAG CLOUD' SECTION -->
    {{ if(isset($tagcloud)): }}
    <div id="menu_header">
        <p>{{= lang('sidebar_tag_cloud') }}</p>
    </div>
    {{ foreach ($tagcloud as $i => $tag): }}
    <form class="tagform" method="post" name="tagsearch{{= $i }}" action="{{= site_url("results/search") }}">
        <input id="keyword" type="hidden" name="keyword" value="{{= $tag[1] }}"/>
	<span style="font-size:{{= getCloudSize(16, 36, $cloudmin, $cloudmax, $tag[0]) }}px;">
	    <a onclick="javascript:dosearch('tagsearch{{= $i }}');">{{= highlight_keywords($tag[1], $keywords) }}</a>
	</span>
    </form>
    &nbsp;
    {{ endforeach }}
    {{ endif }}
    <!-- END OF 'TAG CLOUD' SECTION -->

</div>
<!-- END OF SIDEBAR SECTION -->