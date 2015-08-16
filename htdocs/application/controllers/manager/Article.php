<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @author        CIOpenReview.com
 * @copyright           Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
 * @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
 * @link        http://ciopenreview.com
 */
// ------------------------------------------------------------------------

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

/**
 * Article management controller class
 *
 * Allows manager to add, edit or delete an article
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Article extends CI_Controller
{

	/*
	 * Article controller class constructor
	 */

	function Article()
	{
		parent::__construct();
		$this->load->model('Article_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * add function
	 *
	 * display 'article/add' view, validate form data and add new article to the database
	 */

	function add()
	{
		debug('manager/article page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// create '$article' variable for use in the view
		$article->title = '';
		$article->description = '';
		$article->link_text = '';
		$article->link_url = '';
		$article->meta_keywords = '';
		$article->meta_description = '';
		$data['article'] = $article;
		// check form data was submitted
		if ($this->input->post('article_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'title',
					'label' => lang('manager_article_form_validation_title'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'description',
					'label' => lang('manager_article_form_validation_description'),
					'rules' => 'required'
				),
				array(
					'field' => 'link_text',
					'label' => lang('manager_article_form_validation_link_text'),

				),
				array(
					'field' => 'link_url',
					'label' => lang('manager_article_form_validation_link_url'),

				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_article_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_article_form_validation_meta_description'),
					'rules' => 'max_length[255]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				debug('loading "article/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for adding to database
				$title = $this->input->post('title');
				$description = $this->input->post('description');
				$link_text = $this->input->post('link_text');
				$link_url = $this->input->post('link_url');
				$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
				$meta_description = str_replace('"', '', $this->input->post('meta_description'));
				// add the article
				debug('add the article');
				$new_article_id = $this->Article_model->addArticle($title, $description, $link_text, $link_url, $meta_keywords, $meta_description);
				// message displayed when page reloads
				$data['message'] = lang('manager_article_add_success');
				// clear form validation data
				$this->form_validation->clear_fields();
				// display the form
				debug('loading "manager/article/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/article/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'article/edit' view, validate form data and modify article
	 */

	function edit($id)
	{
		debug('manager/article page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		$data[] = '';
		// check form data was submitted
		if ($this->input->post('article_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'title',
					'label' => lang('manager_article_form_validation_title'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'description',
					'label' => lang('manager_article_form_validation_description'),
					'rules' => 'required'
				),
				array(
					'field' => 'link_text',
					'label' => lang('manager_article_form_validation_link_text'),

				),
				array(
					'field' => 'link_url',
					'label' => lang('manager_article_form_validation_link_url'),

				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_article_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_article_form_validation_meta_description'),
					'rules' => 'max_length[255]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			debug('validate form data');
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['article'] = $this->Article_model->getArticleById($id);
				debug('loading "manager/article/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for updating the database
				$title = $this->input->post('title');
				$description = $this->input->post('description');
				$link_text = $this->input->post('link_text');
				$link_url = $this->input->post('link_url');
				$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
				$meta_description = str_replace('"', '', $this->input->post('meta_description'));
				// update the article
				$this->Article_model->updateArticle($id, $title, $description, $link_text, $link_url, $meta_keywords, $meta_description);
				$data['message'] = lang('manager_article_edit_success');
				debug('loading "manager/article/edited" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				// display the page again
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			// get the article data from the database
			$data['article'] = $this->Article_model->getArticleById($id);
			// if the article id exists...
			if ($data['article']) {
				// display the form
				debug('form not submitted - loading "manager/article/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// redirect to the list of articles
				debug('article not found - redirect to manager/articles');
				redirect('/manager/articles', 301);
			}
		}
	}

	/*
	 * delete function
	 *
	 * display 'article/delete' view, ask to confirm deletion
	 */

	function delete($id)
	{
		debug('manager/article page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// get the article data from the database
		$data['article'] = $this->Article_model->getArticleById($id);
		// if the article id exists...
		if ($data['article']) {
			debug('loading "article/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/article/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// article id does not exist, redirect to the list of articles
			debug('article not found - redirect to manager/articles');
			redirect('/manager/articles', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * deletes the articles and redirects to '/manager/articles'
	 */

	function deleted($id)
	{
		debug('manager/article page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// get the article data from the database
		$data['article'] = $this->Article_model->getArticleById($id);
		// if the article id exists...
		if ($data['article']) {
			debug('delete the article');
			// delete the article
			$this->Article_model->deleteArticle($id);
		}
		debug('redirect to manager/articles');
		redirect('/manager/articles', '301');
	}

}

/* End of file article.php */
/* Location: ./application/controllers/manager/article.php */