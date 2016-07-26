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
{{ if(isset($message)): }}
<div class="callout callout-warning">
    <p>{{= $message }}</p>
</div>
{{ endif }}
<form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
      action="{{= base_url() . 'manager/site_settings/edit' }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_edit_site_settings_title') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_site_settings_form_site_name') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('site_name', $site_name) }}"
                           name="site_name"
                           id="site_name">
                    {{= form_error('site_name') }}
                    <p class="help-block">{{= lang('manager_site_settings_form_site_name_info') }}</p>

                    <label>{{= lang('manager_site_settings_form_site_email') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('site_email', $site_email) }}"
                           name="site_email"
                           id="site_email">
                    {{= form_error('site_email') }}
                    <p class="help-block">{{= lang('manager_site_settings_form_site_email_info') }}</p>

                    <label>{{= lang('manager_site_settings_form_site_summary_title') }}</label>
                    <input class="form-control" type="text"
                           value="{{= set_value('site_summary_title', $site_summary_title) }}"
                           name="site_summary_title"
                           id="site_summary_title">
                    {{= form_error('site_summary_title') }}
                    <p class="help-block">{{= lang('manager_site_settings_form_site_summary_title_info') }}</p>

                    <label>{{= lang('manager_site_settings_form_site_summary_text') }}</label>
                    <input class="form-control" type="text"
                           value="{{= set_value('site_summary_text', $site_summary_text) }}"
                           name="site_summary_text"
                           id="site_summary_text">
                    {{= form_error('site_summary_text') }}
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_edit_settings_review_page_title') }}</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="debug" id="debug"
                                      type="checkbox" {{= $debug }}> {{= lang('manager_site_settings_form_debug') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_debug_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="show_visitor_rating" id="show_visitor_rating"
                                      type="checkbox" {{= $show_visitor_rating }}> {{= lang('manager_site_settings_form_review_show_visitor_rating') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_review_show_visitor_rating_info') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="captcha_verification" id="captcha_verification"
                                      type="checkbox" {{= $captcha_verification }}> {{= lang('manager_site_settings_form_review_captcha_comments') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_review_captcha_comments_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="thumbnail_is_link" id="thumbnail_is_link"
                                      type="checkbox" {{= $thumbnail_is_link }}> {{= lang('manager_site_settings_form_review_lightbox_or_link') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_review_lightbox_or_link_info') }}</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_featured_section_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_featured_section_title_info') }}</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="featured_section_home" id="featured_section_home"
                                      type="checkbox" {{= $featured_section_home }}> {{= lang('manager_site_settings_form_featured_section_home_page') }}
                        </label>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="featured_section_review" id="featured_section_review"
                                      type="checkbox" {{= $featured_section_review }}> {{= lang('manager_site_settings_form_featured_section_review_page') }}
                        </label>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="featured_section_article" id="featured_section_article"
                                      type="checkbox" {{= $featured_section_article }}> {{= lang('manager_site_settings_form_featured_section_article_page') }}
                        </label>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="featured_section_search" id="featured_section_search"
                                      type="checkbox" {{= $featured_section_search }}> {{= lang('manager_site_settings_form_featured_section_search_page') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_featured_section_featured_count') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('featured_count', $featured_count) }}"
                                   name="featured_count"
                                   id="featured_count">
                        </div>
                        {{= form_error('featured_count') }}
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_featured_section_featured_minimum') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('featured_minimum', $featured_minimum) }}"
                                   name="featured_minimum"
                                   id="featured_minimum">
                        </div>
                        {{= form_error('featured_minimum') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_sidebar_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_sidebar_title_info') }}</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="search_sidebar" id="search_sidebar"
                                      type="checkbox" {{= $search_sidebar }}> {{= lang('manager_site_settings_form_search_sidebar') }}
                        </label>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="recent_review_sidebar" id="recent_review_sidebar"
                                      type="checkbox" {{= $recent_review_sidebar }}> {{= lang('manager_site_settings_form_recent_reviews_sidebar') }}
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_number_of_reviews_sidebar') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('number_of_reviews_sidebar', $number_of_reviews_sidebar) }}"
                                   name="number_of_reviews_sidebar"
                                   id="number_of_reviews_sidebar">
                        </div>
                        {{= form_error('number_of_reviews_sidebar') }}
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="tag_cloud_sidebar" id="tag_cloud_sidebar"
                                      type="checkbox" {{= $tag_cloud_sidebar }}> {{= lang('manager_site_settings_form_tag_cloud_sidebar') }}
                        </label>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="categories_sidebar" id="categories_sidebar"
                                      type="checkbox" {{= $categories_sidebar }}> {{= lang('manager_site_settings_form_categories_sidebar') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_max_ads_sidebar_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_max_ads_sidebar_title_info') }}</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_sidebar_home_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_home_sidebar', $max_ads_home_sidebar) }}"
                                   name="max_ads_home_sidebar"
                                   id="max_ads_home_sidebar">
                        </div>
                        {{= form_error('max_ads_home_sidebar') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_sidebar_review_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_review_sidebar', $max_ads_review_sidebar) }}"
                                   name="max_ads_review_sidebar"
                                   id="max_ads_review_sidebar">
                        </div>
                        {{= form_error('max_ads_review_sidebar') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_sidebar_article_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_article_sidebar', $max_ads_article_sidebar) }}"
                                   name="max_ads_article_sidebar"
                                   id="max_ads_article_sidebar">
                        </div>
                        {{= form_error('max_ads_article_sidebar') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_sidebar_search_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_search_sidebar', $max_ads_search_sidebar) }}"
                                   name="max_ads_search_sidebar"
                                   id="max_ads_search_sidebar">
                        </div>
                        {{= form_error('max_ads_search_sidebar') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_sidebar_custom_pages') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_custom_page_sidebar', $max_ads_custom_page_sidebar) }}"
                                   name="max_ads_custom_page_sidebar"
                                   id="max_ads_custom_page_sidebar">
                        </div>
                        {{= form_error('max_ads_custom_page_sidebar') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_max_ads_lists_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_max_ads_lists_title_info') }}</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_lists_home_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_home_lists', $max_ads_home_lists) }}"
                                   name="max_ads_home_lists"
                                   id="max_ads_home_lists">
                        </div>
                        {{= form_error('max_ads_home_lists') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_lists_articles_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_articles_lists', $max_ads_articles_lists) }}"
                                   name="max_ads_articles_lists"
                                   id="max_ads_articles_lists">
                        </div>
                        {{= form_error('max_ads_articles_lists') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_max_ads_lists_results_page') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_ads_results_lists', $max_ads_results_lists) }}"
                                   name="max_ads_results_lists"
                                   id="max_ads_results_lists">
                        </div>
                        {{= form_error('max_ads_results_lists') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_approval_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_approval_title_info') }}</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="review_approval" id="review_approval"
                                      type="checkbox" {{= $review_approval }}> {{= lang('manager_site_settings_form_review_approval') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_review_approval_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="review_auto" id="review_auto"
                                      type="checkbox" {{= $review_auto }}> {{= lang('manager_site_settings_form_review_auto') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_review_auto_info') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="comment_approval" id="comment_approval"
                                      type="checkbox" {{= $comment_approval }}> {{= lang('manager_site_settings_form_comment_approval') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_comment_approval_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="comment_auto" id="comment_auto"
                                      type="checkbox" {{= $comment_auto }}> {{= lang('manager_site_settings_form_comment_auto') }}
                        </label>
                        <p class="help-block">{{= lang('manager_site_settings_form_comment_auto_info') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_site_settings_form_perpage_title') }}</h3>
            <p class="help-block">{{= lang('manager_site_settings_form_perpage_title_info') }}</p>
            <h4>{{= lang('manager_site_settings_form_perpage_site') }}</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_site_home') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_site_home', $perpage_site_home) }}"
                                   name="perpage_site_home"
                                   id="perpage_site_home">
                        </div>
                        {{= form_error('perpage_site_home') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_site_search') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_site_search', $perpage_site_search) }}"
                                   name="perpage_site_search"
                                   id="perpage_site_search">
                        </div>
                        {{= form_error('perpage_site_search') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_site_category') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_site_category', $perpage_site_category) }}"
                                   name="perpage_site_category"
                                   id="perpage_site_category">
                        </div>
                        {{= form_error('perpage_site_category') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_site_articles') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_site_articles', $perpage_site_articles) }}"
                                   name="perpage_site_articles"
                                   id="perpage_site_articles">
                        </div>
                        {{= form_error('perpage_site_articles') }}
                    </div>
                </div>
            </div>
            <h4>{{= lang('manager_site_settings_form_perpage_manager') }}</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_reviews') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_reviews', $perpage_manager_reviews) }}"
                                   name="perpage_manager_reviews"
                                   id="perpage_manager_reviews">
                        </div>
                        {{= form_error('perpage_manager_reviews') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_reviews_pending') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_reviews_pending', $perpage_manager_reviews_pending) }}"
                                   name="perpage_manager_reviews_pending"
                                   id="perpage_manager_reviews_pending">
                        </div>
                        {{= form_error('perpage_manager_reviews_pending') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_comments') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_comments', $perpage_manager_comments) }}"
                                   name="perpage_manager_comments"
                                   id="perpage_manager_comments">
                        </div>
                        {{= form_error('perpage_manager_comments') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_comments_pending') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_comments_pending', $perpage_manager_comments_pending) }}"
                                   name="perpage_manager_comments_pending"
                                   id="perpage_manager_comments_pending">
                        </div>
                        {{= form_error('perpage_manager_comments_pending') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_categories') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_categories', $perpage_manager_categories) }}"
                                   name="perpage_manager_categories"
                                   id="perpage_manager_categories">
                        </div>
                        {{= form_error('perpage_manager_categories') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_features') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_features', $perpage_manager_features) }}"
                                   name="perpage_manager_features"
                                   id="perpage_manager_features">
                        </div>
                        {{= form_error('perpage_manager_features') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_ratings') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_ratings', $perpage_manager_ratings) }}"
                                   name="perpage_manager_ratings"
                                   id="perpage_manager_ratings">
                        </div>
                        {{= form_error('perpage_manager_ratings') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_articles') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_articles', $perpage_manager_articles) }}"
                                   name="perpage_manager_articles"
                                   id="perpage_manager_articles">
                        </div>
                        {{= form_error('perpage_manager_articles') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_custom_pages') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_custom_pages', $perpage_manager_custom_pages) }}"
                                   name="perpage_manager_custom_pages"
                                   id="perpage_manager_custom_pages">
                        </div>
                        {{= form_error('perpage_manager_custom_pages') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_ads') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_ads', $perpage_manager_ads) }}"
                                   name="perpage_manager_ads"
                                   id="perpage_manager_ads">
                        </div>
                        {{= form_error('perpage_manager_ads') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_site_settings_form_perpage_manager_users') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('perpage_manager_users', $perpage_manager_users) }}"
                                   name="perpage_manager_users"
                                   id="perpage_manager_users">
                        </div>
                        {{= form_error('perpage_manager_users') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="settings_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_site_settings_form_submit_button') }}"/>
        </div>
    </div>
</form>