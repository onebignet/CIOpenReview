<?php

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
 * @link        http://CIOpenReview.com
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
 * Page management controller class
 *
 * Allows manager to add, edit or delete a custom page
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Page extends CI_Controller
{

	/*
	 * Page controller class constructor
	 */

	function page()
	{
		parent::__construct();
		$this->load->model('Page_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'page/add' view, validate form data and add new page to the database
	 */

	function add()
	{
		debug('manager/page page | add function');
// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$name' variable for use in the view
		$page->name = '';
		$page->content = '';
		$page->meta_keywords = '';
		$page->meta_description = '';
		$data['page'] = $page;
		// check form data was submitted
		if ($this->input->post('page_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_page_form_validation_name'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'content',
					'label' => lang('manager_page_form_validation_content'),
					'rules' => 'trim|required|min_length[5]'
				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_page_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_page_form_validation_meta_description'),
					'rules' => 'max_length[255]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				debug('loading "page/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for adding to database
				$name = $this->input->post('name');
				$content = $this->input->post('content');
				$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
				$meta_description = str_replace('"', '', $this->input->post('meta_description'));
				// add the page
				debug('add the page');
				$new_page_id = $this->Page_model->add_page($name, $content, $meta_keywords, $meta_description);
				$data['message'] = lang('manager_page_add_success');
				// clear form validation data
				$this->form_validation->clear_fields();
				// reload the form
				debug('loading "manager/page/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/page/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'page/edit' view, validate form data and modify page
	 */

	function edit($id)
	{
		debug('manager/page page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		$data[] = '';
		// check form data was submitted
		if ($this->input->post('page_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_page_form_validation_name'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'content',
					'label' => lang('manager_page_form_validation_content'),
					'rules' => 'trim|required|min_length[5]'
				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_page_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_page_form_validation_meta_description'),
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
				$data['page'] = $this->Page_model->get_page_by_id($id);
				debug('loading "manager/page/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for updating the database
				$name = $this->input->post('name');
				$content = auto_typography($this->input->post('content'));
				$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
				$meta_description = str_replace('"', '', $this->input->post('meta_description'));
				// update the page
				$this->Page_model->update_page($id, $name, $content, $meta_keywords, $meta_description);
				// reload the form
				debug('loading "manager/page/edited" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so load the data and show the form
			$data['page'] = $this->Page_model->get_page_by_id($id);
			if ($data['page']) {
				debug('form not submitted - loading "manager/page/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// no page data so redirect to the pages list
				debug('page not found - redirect to manager/pages');
				redirect('/manager/pages', 301);
			}
		}
	}

	/*
	 * delete function
	 *
	 * display delete confirmation page
	 */

	function delete($id)
	{
		debug('manager/page page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the page from the database
		$data['page'] = $this->Page_model->get_page_by_id($id);
		if ($data['page']) {
			// page exists... show confirmation page
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/page/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// redirect back to pages list
			redirect('/manager/pages', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the page after confirmation
	 */

	function deleted($id)
	{
		debug('manager/page page | deleted function');
// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the page from the database
		$data['page'] = $this->Page_model->get_page_by_id($id);
		if ($data['page']) {
			debug('delete the page');
			// page exists... show confirmation page
			$this->Page_model->delete_page($id);
		}
		// redirect back to pages list
		debug('page not found - redirect to manager/pages');
		redirect('/manager/pages', '301');
	}

}

/* End of file page.php */
/* Location: ./application/controllers/manager/page.php */