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
 * Review features listing controller class
 *
 * Displays a list of review features for a review
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_features extends CI_Controller
{

	function Review_features()
	{
		parent::__construct();
		$this->load->model('Feature_model');
		$this->load->model('Review_model');
		$this->load->model('Review_feature_model');
		$this->load->helper('form');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * show function
	 *
	 * display list of review features for a review
	 */

	function show($review_id)
	{
		debug('manager/review_features page | show function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		if ($review_id) {
			// load a page of review features for this review into an array for displaying in the view
			$review = $this->Review_model->getReviewById($review_id);
			if ($review) {
				debug('loaded review');
				$data['allreviewfeatures'] = $this->Review_feature_model->getReviewFeaturesForReviewById($review_id);
				if ($data['allreviewfeatures']) {
					debug('loaded review features');
					$data['review'] = $review;
					$data['features'] = $this->Feature_model->getFeaturesDropDown(1);
					// show the review features page
					debug('loading "manager/review_features" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_features/review_features', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// no data... show the 'no review features' page
					$data['review'] = $review;
					debug('no review features found - loading "manager/no_review_features" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_features/no_review_features', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// no review data... redirect to manager home page
				redirect('/manager/home', '301');
			}
		} else {
			// no review id... redirect to manager home page
			redirect('/manager/home', '301');
		}
	}

	/*
	 * edit function
	 *
	 * modify review features for the review id, then display features for this review again
	 */

	function edit($id)
	{
		debug('manager/review_features page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		$data['review'] = $this->Review_model->getReviewById($id);
		// check form data was submitted
		if ($this->input->post('review_features_submit')) {
			debug('form submitted');
			$count = $this->input->post('feature_count');
			for ($index = 0; $index < $count; $index++) {
				$review_feature_id = $this->input->post('review_feature_id' . $index);
				$feature_id = $this->input->post('feature_id' . $index);
				$value = $this->input->post('value' . $index);
				$updateReview_feature = $this->Review_feature_model->updateReviewfeature($review_feature_id, $feature_id, $value);
			}
			debug('redirecting to "manager/review_features/show/"' . $id);
			redirect('/manager/review_features/show/' . $id, '301');
		} else {
			// form not submitted so redirect back to review_features/show
			debug('form not submitted');
			debug('redirecting to "manager/review_features/show/"' . $id);
			redirect('/manager/review_features/show/' . $id, '301');
		}
	}


}

/* End of file review_features.php */
/* Location: ./application/controllers/manager/review_features.php */