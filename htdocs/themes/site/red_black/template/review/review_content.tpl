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
<script type="text/javascript">
    var $JQ = jQuery.noConflict();

    $JQ(document).ready(function () {
        $JQ('#comment_form_container').hide();
        $JQ('#post_message').show();
        var options = {
            target: '#comments',
            type: 'post'
        };
        $JQ('#commentForm').submit(function () {
            $JQ(this).ajaxSubmit(options);
            return false;
        });
        var p1, p2 = {};
        $JQ('#comments').load('{{= site_url() }}review/display_comments/{{= $review->id }}', p1, function (str){});
        $JQ('#visitor_rating').load('{{= site_url() }}review/display_visitor_rating/{{= $review->id }}', p2, function (str){});

    });

    function show_form() {
        $JQ('#comment_form_container').show();
        $JQ('#post_message').hide();
    }
</script>

<!-- START OF 'CONTENT' SECTION -->
<div id="content">
    <!-- START OF CONDITIONAL 'FEATURED' SECTION -->
    {{ if ($featured_reviews >= $featured_minimum): }}
    <div class="featured">
        <h2 class="heading">{{= lang('article_page_featured_reviews') }}</h2>

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

    <!-- START OF CONDITIONAL 'ERROR' SECTION -->
    {{ if ($message != ''): }}
    <div id="error">
        <b>{{= $message }}</b>
    </div>
    {{ endif }}
    <!-- END OF 'ERROR' SECTION -->


    <!-- START OF 'REVIEW' SECTION -->
    <div class="review">

        <!-- START OF CONDITIONAL 'ADS' SECTION -->
        {{ if ($review_ads): }}
        {{ foreach ($review_ads as $ad): }}
        {{ if ($ad->visible_on_review_page > 0): }}
        <div class="review_ad_block">
            {{ if ($ad->image !== ''): }}
            <div class="ad_image">
                {{= anchor($ad->link, $ad->image) }}
            </div>
            {{ endif }}
            {{ if ($ad->text !== ''): }}
            <div class="ad_text">
                {{= character_limiter($ad->text) }}
            </div>
            {{ endif }}
        </div>
        {{ endif }}
        {{ break }}
        {{ endforeach }}
        {{ endif }}
        <!-- END OF 'ADS' SECTION -->

        <!-- START OF 'THUMBNAIL AND FEATURES' SECTION -->
        <div class="review_thumb_features_section">
            <div class="review_thumb">
                {{ if ($lightbox): }}
                {{= anchor($review->image_url, ' ', 'rel="lightbox" class="image" style="background: url('.$review->review_thumb_url.') no-repeat center center"') }}
            </div>
            <div class="review_click_image">{{= lang('review_larger_image') }}</div>
            {{ else: }}
            {{= anchor('recommends/this/' . $review->seo_title, ' ', 'class="image" style="background: url('.$review->review_thumb_url.') no-repeat center center"') }}
        </div>
        {{ endif }}
        {{ if ($features_count > 0): }}
        <div class="review_features">
            <h2>{{= lang('review_features') }}</h2>
            {{ foreach ($features as $feature): }}
            <p><b>{{= $feature->name }}: </b>{{= $feature->value }}</p>
            {{ endforeach }}
        </div>
        {{ endif }}
    </div>
    <!-- END OF 'THUMBNAIL AND FEATURES' SECTION -->

    <!-- START OF 'REVIEW CONTENT' SECTION -->
    <div id="review_content">
        <h2 class="heading">{{= anchor('recommends/this/' . $review->seo_title, $review->title) }}</h2>

        <!-- START OF 'RATINGS' SECTION -->
        {{ if ($ratings): }}
        <div class="rating_box">
            {{ foreach ($ratings as $rating): }}
            <div class="rating_value">
                <img src="{{= template_path() . 'design/images/' . $rating->rating_image }}" alt=""/>
            </div>
            <div class="rating_text">
                {{= $rating->rating_name }}
            </div>
            {{ endforeach }}
        </div>
        {{ endif }}
        <!-- END OF 'RATINGS' SECTION -->

        {{= auto_typography($review->description) }}

        <!-- START OF 'BUTTON' SECTION -->
        <div class="review_button_row">
            {{= anchor('recommends/this/' . $review->seo_title, lang('review_button'), 'class="review_button"') }}
        </div>
        <!-- END OF 'BUTTON' SECTION -->

    </div>
    <!-- END OF 'REVIEW CONTENT' SECTION -->

    <!-- START OF 'SOCIAL BOOKMARKS' SECTION -->
    <div class="review_social_bookmarks">
        <a target="_blank" href="http://www.freescriptsite.com/free-social-bookmark-script/"><img border="0"
                                                                                                  src="http://www.freescriptsite.com/free-social-bookmark-script/social1.gif"
                                                                                                  alt=""/></a>
        <SCRIPT type="text/javaScript"
                src="http://www.freescriptsite.com/free-social-bookmark-script/bookmark-script.js"></SCRIPT>
    </div>
    <!-- END OF 'SOCIAL BOOKMARKS' SECTION -->

    <!-- START OF 'COMMENTS' SECTION -->
    <div class="review_post_message" id="post_message">
        <a onclick="show_form()"
           style="text-decoration:underline">{{= lang('review_comment_post_comment_heading') }}</a>
    </div>
    <div id="comment_form" class="comment_form">
        <div id="comment_form_container">
            <div class="review_comment_form">
                <form id="commentForm" action="{{= site_url() }}review/comment_submit/{{= $review->id }}" method="post">
                    <div class="formblock">
                        <div class="formleft">
                            <label>{{= lang('review_comment_label_name') }}</label>
                        </div>
                        <div class="formright">
                            <input type="text" name="name"/>
                        </div>
                    </div>
                    <div class="formblock">
                        <div class="formleft">
                            <label>{{= lang('review_comment_label_comment') }}</label>
                        </div>
                        <div class="formright">
                            <textarea name="comment" cols="35" rows="4"></textarea>
                        </div>
                    </div>
                    {{ if ($show_visitor_rating > 0): }}
                    <div class="formblock">
                        <div class="formleft">
                            <label>{{= lang('review_comment_label_rating') }}</label>
                        </div>
                        <div class="formright">
                            <input name="rating" type="radio" class="star" value="1"/>
                            <input name="rating" type="radio" class="star" value="2"/>
                            <input name="rating" type="radio" class="star" value="3"/>
                            <input name="rating" type="radio" class="star" value="4"/>
                            <input name="rating" type="radio" class="star" value="5"/>
                        </div>
                    </div>
                    {{ endif }}
                    {{ if ($captcha_verification > 0): }}
                    <div class="formblock">
                        <div class="formleft">
                            <label>{{= lang('review_comment_label_captcha') }}</label>
                        </div>
                        <div class="formright">
                            <input class="captcha" type="text" name="captcha"/>
                            {{= $captcha_image }}
                        </div>
                    </div>
                    {{ endif }}
                    <div class="formblock">
                        <div class="formleft"></div>
                        <div class="formright">
                            <input type="hidden" name="comment_submitted" value="1"/>
                            <input type="submit" class="button" value="{{= lang('review_comment_submit_comment') }}"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="comments" id="comments"></div>
    <!-- END OF 'COMMENTS' SECTION -->

    <!-- START OF CONDITIONAL 'ADS' SECTION -->
    {{ if ($review_ads): }}
    {{ foreach ($review_ads as $ad) : }}
    {{ if ($ad->visible_on_review_page > 0): }}
    <div class="review_ad_block">
        {{ if ($ad->image !== ''): }}
        <div class="ad_image">
            {{= anchor($ad->link, $ad->image) }}
        </div>
        {{ endif }}
        {{ if ($ad->text !== ''): }}
        <div class="ad_text">
            {{= character_limiter($ad->text) }}
        </div>
        {{ endif }}
    </div>
    {{ endif }}
    {{ break }}
    {{ endforeach }}
    {{ endif }}
    <!-- END OF 'ADS' SECTION -->
</div>
</div>
<!-- END OF 'CONTENT' SECTION -->
