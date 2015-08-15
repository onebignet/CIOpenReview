<?php
/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript base on the abandoned OpenReviewScript
 *
 * @filename    MY_Loader.php
 * @package        CIOpenReview
 * @author        JaymZZZ
 * @date    8/2/2015
 * @time    12:48 PM
 * @copyright           Copyright (c) 2014-2015 MindBrite.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
 * @license        This file is part of CIOpenReview with portions used from OpenReviewScript - free software licensed under the GNU General Public License version 2
 * @link        http://ciopenreview.com/ciopenreview
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
class MY_Loader extends CI_Loader
{

	var $CI;

	/**
	 * Constructor
	 *
	 * Sets the path to the view files and gets the initial output buffering level
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_ci_ob_level = ob_get_level();
		// CodeIgniter uses 'application/views/' directory for loading views
		// But we want to use 'themes/' directory
		$this->_ci_view_paths = array(THEMESPATH => TRUE);
		// (THEMESPATH is defined in index.php)


		log_message('debug', "Loader Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Loader
	 *
	 * This function is used to load views and files.
	 * Variables are prefixed with _ci_ to avoid symbol collision with
	 * variables made available to view files
	 *
	 * @access    private
	 * @param    array
	 * @return    void
	 */
	protected function _ci_load($_ci_data)
	{
		// Set the default data variables
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val) {
			$$_ci_val = isset($_ci_data[$_ci_val]) ? $_ci_data[$_ci_val] : FALSE;
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if (is_string($_ci_path) && $_ci_path !== '') {
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		} else {
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext === '') ? $_ci_view . VIEW_EXT : $_ci_view;

			foreach ($this->_ci_view_paths as $_ci_view_file => $cascade) {
				if (file_exists($_ci_view_file . $_ci_file)) {
					$_ci_path = $_ci_view_file . $_ci_file;
					$file_exists = TRUE;
					break;
				}

				if (!$cascade) {
					break;
				}
			}
		}

		if (!$file_exists && !file_exists($_ci_path)) {
			show_error('Unable to load the requested file: ' . $_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.
		$_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
			if (!isset($this->$_ci_key)) {
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load->vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if (is_array($_ci_vars)) {
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be post-processed by
		 *	the output class. Why do we need post processing? For one thing,
		 *	in order to show the elapsed page load time. Unless we can
		 *	intercept the content right before it's sent to the browser and
		 *	then stop the timer it won't be accurate.
		 */
		ob_start();

		// Change the short tags {{ {{= and }}
		// to standard PHP and echo statements.

		echo eval('?>' . str_replace('}}', '?>', str_replace('<?php=', '<?php echo', str_replace('{{', '<?php', file_get_contents($_ci_path)))));

		log_message('info', 'File loaded: ' . $_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE) {
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 */
		if (ob_get_level() > $this->_ci_ob_level + 1) {
			ob_end_flush();
		} else {
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}

		return $this;
	}

}