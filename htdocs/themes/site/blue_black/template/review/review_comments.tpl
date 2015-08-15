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
{{ if (isset($message)): }}
<div class="review_comment_message">{{= $message }}</div>
<script type="text/javascript">$JQ('#post_message').hide();</script>
{{ endif }}
<h2 class="heading">{{= lang('review_comment_heading') }}</h2>
{{ if ($show_visitor_rating > 0): }}
<p class="review_visitor_rating">{{= lang('review_comment_rating') }}&nbsp;&nbsp;<img
            src="{{= template_path() . 'design/images/' . $visitor_rating_image }}" alt=""></p>
{{ endif }}
{{ if ($comments_count > 0): }}
{{ foreach ($comments as $index=>$comment): }}
<div class="review_comment_message">
    {{ if ($show_visitor_rating > 0): }}
    <div class="review_comment_rating">
        {{ if ($comment->rating_image != ''): }}
        <img src="{{= template_path() . 'design/images/' . $comment->rating_image }}" alt=""/>
        {{ endif }}
    </div>
    {{ endif }}
    &#8220;{{= $comment->quotation }}&#8221; - <b>{{= $comment->source }}</b>&nbsp;&nbsp;
    <span class="review_comment_date">{{= timespan(strtotime($comment->last_modified),now()).' '.lang('review_ago') }}</span>
</div>
{{ if ($index+1 != count($comments)): }}
<div class="list_separator"></div>{{ endif }}
{{ endforeach }}
{{ else: }}
<h3>{{= lang('review_no_comments') }}</h3>
{{ endif }}