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
    <div class="myform">
        <div class="header_row">{{= lang('manager_edit_site_settings_title') }}</div>
        <p>&nbsp;</p>
        {{ if(isset($message)): }}
        <p>&nbsp;</p>

        <h3>{{= $message }}</h3>

        <p>&nbsp;</p>
        {{ endif }}
        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/site_settings/edit' }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_site_name') }}
                        <span class="small">{{= lang('manager_site_settings_form_site_name_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="site_name" id="site_name"
                           value="{{= set_value('site_name', $site_name) }}"/>
                    {{= form_error('site_name') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_site_email') }}
                        <span class="small">{{= lang('manager_site_settings_form_site_email_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="site_email" id="site_email"
                           value="{{= set_value('site_email', $site_email) }}"/>
                    {{= form_error('site_email') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_site_summary_title') }}
                        <span class="small">{{= lang('manager_site_settings_form_site_summary_title_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="site_summary_title" id="site_summary_title"
                           value="{{= set_value('site_summary_title', $site_summary_title) }}"/>
                    {{= form_error('site_summary_title') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_site_summary_text') }}
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="site_summary_text" id="site_summary_text"
                           value="{{= set_value('site_summary_text', $site_summary_text) }}"/>
                    {{= form_error('site_summary_text') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_debug') }}</label>
                </div>
                <div class="formright">
                    <input name="debug" id="debug" type="checkbox" {{= $debug }}>
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <b>{{= lang('manager_edit_settings_review_page_title') }}
                    </b>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_review_show_visitor_rating') }}
                        <span class="small">{{= lang('manager_site_settings_form_review_show_visitor_rating_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="show_visitor_rating" id="show_visitor_rating"
                           type="checkbox" {{= $show_visitor_rating }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_review_captcha_comments') }}
                        <span class="small">{{= lang('manager_site_settings_form_review_captcha_comments_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="captcha_verification" id="captcha_verification"
                           type="checkbox" {{= $captcha_verification }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_review_lightbox_or_link') }}
                        <span class="small">{{= lang('manager_site_settings_form_review_lightbox_or_link_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="thumbnail_is_link" id="thumbnail_is_link" type="checkbox" {{= $thumbnail_is_link }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_featured_section_title') }}</b>
                    </label>
                </div>
                <div class="formright">
                    <span class="small"
                          style="text-align:left">{{= lang('manager_site_settings_form_featured_section_title_info') }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_home_page') }}</label>
                </div>
                <div class="formright">
                    <input name="featured_section_home" id="featured_section_home"
                           type="checkbox" {{= $featured_section_home }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_review_page') }}</label>
                </div>
                <div class="formright">
                    <input name="featured_section_review" id="featured_section_review"
                           type="checkbox" {{= $featured_section_review }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_article_page') }}</label>
                </div>
                <div class="formright">
                    <input name="featured_section_article" id="featured_section_article"
                           type="checkbox" {{= $featured_section_article }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_search_page') }}</label>
                </div>
                <div class="formright">
                    <input name="featured_section_search" id="featured_section_search"
                           type="checkbox" {{= $featured_section_search }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_featured_count') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="featured_count" id="featured_count" size="2"
                           value="{{= set_value('featured_count', $featured_count) }}"/>
                    {{= form_error('featured_count') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_featured_section_featured_minimum') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="featured_minimum" id="featured_minimum" size="2"
                           value="{{= set_value('featured_minimum', $featured_minimum) }}"/>
                    {{= form_error('featured_minimum') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_sidebar_title') }}</b>
                    </label>
                </div>
                <div class="formright">
                    <span class="small"
                          style="text-align:left">{{= lang('manager_site_settings_form_sidebar_title_info') }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_search_sidebar') }}</label>
                </div>
                <div class="formright">
                    <input name="search_sidebar" id="search_sidebar" type="checkbox" {{= $search_sidebar }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_recent_reviews_sidebar') }}</label>
                </div>
                <div class="formright">
                    <input name="recent_review_sidebar" id="recent_review_sidebar"
                           type="checkbox" {{= $recent_review_sidebar }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_number_of_reviews_sidebar') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="number_of_reviews_sidebar" id="number_of_reviews_sidebar"
                           size="2" value="{{= set_value('number_of_reviews_sidebar', $number_of_reviews_sidebar) }}"/>
                    {{= form_error('number_of_reviews_sidebar') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_tag_cloud_sidebar') }}</label>
                </div>
                <div class="formright">
                    <input name="tag_cloud_sidebar" id="tag_cloud_sidebar" type="checkbox" {{= $tag_cloud_sidebar }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_categories_sidebar') }}</label>
                </div>
                <div class="formright">
                    <input name="categories_sidebar" id="categories_sidebar" type="checkbox" {{= $categories_sidebar }}>
                </div>
            </div>

            <div class="formleft">
                <label>
                    <b>{{= lang('manager_site_settings_form_max_ads_sidebar_title') }}</b>
                </label>
            </div>
            <div class="formright">
                <span class="small"
                      style="text-align:left">{{= lang('manager_site_settings_form_max_ads_sidebar_title_info') }}</span>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_sidebar_home_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_home_sidebar" id="max_ads_home_sidebar" size="2"
                           value="{{= set_value('max_ads_home_sidebar', $max_ads_home_sidebar) }}"/>
                    {{= form_error('max_ads_home_sidebar') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_sidebar_review_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_review_sidebar" id="max_ads_review_sidebar" size="2"
                           value="{{= set_value('max_ads_review_sidebar', $max_ads_review_sidebar) }}"/>
                    {{= form_error('max_ads_review_sidebar') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_sidebar_article_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_article_sidebar" id="max_ads_article_sidebar"
                           size="2" value="{{= set_value('max_ads_article_sidebar', $max_ads_article_sidebar) }}"/>
                    {{= form_error('max_ads_article_sidebar') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_sidebar_search_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_search_sidebar" id="max_ads_search_sidebar" size="2"
                           value="{{= set_value('max_ads_search_sidebar', $max_ads_search_sidebar) }}"/>
                    {{= form_error('max_ads_search_sidebar') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_sidebar_custom_pages') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_custom_page_sidebar" id="max_ads_custom_page_sidebar"
                           size="2"
                           value="{{= set_value('max_ads_custom_page_sidebar', $max_ads_custom_page_sidebar) }}"/>
                    {{= form_error('max_ads_custom_page_sidebar') }}
                </div>
            </div>
            <div class="formleft">
                <label>
                    <b>{{= lang('manager_site_settings_form_max_ads_lists_title') }}</b>
                </label>
            </div>
            <div class="formright">
                <span class="small"
                      style="text-align:left">{{= lang('manager_site_settings_form_max_ads_lists_title_info') }}</span>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_lists_home_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_home_lists" id="max_ads_home_lists" size="2"
                           value="{{= set_value('max_ads_home_lists', $max_ads_home_lists) }}"/>
                    {{= form_error('max_ads_home_lists') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_lists_articles_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_articles_lists" id="max_ads_articles_lists" size="2"
                           value="{{= set_value('max_ads_articles_lists', $max_ads_articles_lists) }}"/>
                    {{= form_error('max_ads_articles_lists') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_max_ads_lists_results_page') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_ads_results_lists" id="max_ads_results_lists" size="2"
                           value="{{= set_value('max_ads_results_lists', $max_ads_results_lists) }}"/>
                    {{= form_error('max_ads_results_lists') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_approval_title') }}</b>
                    </label>
                </div>
                <div class="formright">
                    <span class="small"
                          style="text-align:left">{{= lang('manager_site_settings_form_approval_title_info') }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_review_approval') }}
                        <span class="small">{{= lang('manager_site_settings_form_review_approval_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="review_approval" id="review_approval" type="checkbox" {{= $review_approval }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_review_auto') }}
                        <span class="small">{{= lang('manager_site_settings_form_review_auto_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="review_auto" id="review_auto" type="checkbox" {{= $review_auto }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_comment_approval') }}
                        <span class="small">{{= lang('manager_site_settings_form_comment_approval_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="comment_approval" id="comment_approval" type="checkbox" {{= $comment_approval }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_comment_auto') }}
                        <span class="small">{{= lang('manager_site_settings_form_comment_auto_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="comment_auto" id="comment_auto" type="checkbox" {{= $comment_auto }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_perpage_title') }}</b>
                    </label>
                </div>
                <div class="formright">
                    <span class="small"
                          style="text-align:left">{{= lang('manager_site_settings_form_perpage_title_info') }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_perpage_site') }}</b>
                    </label>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_site_home') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_site_home" id="perpage_site_home" size="2"
                           value="{{= set_value('perpage_site_home', $perpage_site_home) }}"/>
                    {{= form_error('perpage_site_home') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_site_search') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_site_search" id="perpage_site_search" size="2"
                           value="{{= set_value('perpage_site_search', $perpage_site_search) }}"/>
                    {{= form_error('perpage_site_search') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_site_category') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_site_category" id="perpage_site_category" size="2"
                           value="{{= set_value('perpage_site_category', $perpage_site_category) }}"/>
                    {{= form_error('perpage_site_category') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_site_articles') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_site_articles" id="perpage_site_articles" size="2"
                           value="{{= set_value('perpage_site_articles', $perpage_site_articles) }}"/>
                    {{= form_error('perpage_site_articles') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>
                        <b>{{= lang('manager_site_settings_form_perpage_manager') }}</b>
                    </label>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_reviews') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_reviews" id="perpage_manager_reviews"
                           size="2" value="{{= set_value('perpage_manager_reviews', $perpage_manager_reviews) }}"/>
                    {{= form_error('perpage_manager_reviews') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_reviews_pending') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_reviews_pending"
                           id="perpage_manager_reviews_pending" size="2"
                           value="{{= set_value('perpage_manager_reviews_pending', $perpage_manager_reviews_pending) }}"/>
                    {{= form_error('perpage_manager_reviews_pending') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_comments') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_comments" id="perpage_manager_comments"
                           size="2" value="{{= set_value('perpage_manager_comments', $perpage_manager_comments) }}"/>
                    {{= form_error('perpage_manager_comments') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_comments_pending') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_comments_pending"
                           id="perpage_manager_comments_pending" size="2"
                           value="{{= set_value('perpage_manager_comments_pending', $perpage_manager_comments_pending) }}"/>
                    {{= form_error('perpage_manager_comments_pending') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_categories') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_categories" id="perpage_manager_categories"
                           size="2"
                           value="{{= set_value('perpage_manager_categories', $perpage_manager_categories) }}"/>
                    {{= form_error('perpage_manager_categories') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_features') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_features" id="perpage_manager_features"
                           size="2" value="{{= set_value('perpage_manager_features', $perpage_manager_features) }}"/>
                    {{= form_error('perpage_manager_features') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_ratings') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_ratings" id="perpage_manager_ratings"
                           size="2" value="{{= set_value('perpage_manager_ratings', $perpage_manager_ratings) }}"/>
                    {{= form_error('perpage_manager_ratings') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_articles') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_articles" id="perpage_manager_articles"
                           size="2" value="{{= set_value('perpage_manager_articles', $perpage_manager_articles) }}"/>
                    {{= form_error('perpage_manager_articles') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_custom_pages') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_custom_pages"
                           id="perpage_manager_custom_pages" size="2"
                           value="{{= set_value('perpage_manager_custom_pages', $perpage_manager_custom_pages) }}"/>
                    {{= form_error('perpage_manager_custom_pages') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_ads') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_ads" id="perpage_manager_ads" size="2"
                           value="{{= set_value('perpage_manager_ads', $perpage_manager_ads) }}"/>
                    {{= form_error('perpage_manager_ads') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_site_settings_form_perpage_manager_users') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="perpage_manager_users" id="perpage_manager_users" size="2"
                           value="{{= set_value('perpage_manager_users', $perpage_manager_users) }}"/>
                    {{= form_error('perpage_manager_users') }}
                </div>
            </div>

            <input type="submit" name="settings_submit" id="button"
                   value="{{= lang('manager_site_settings_form_submit_button') }}"/>
        </form>
    </div>
</div>