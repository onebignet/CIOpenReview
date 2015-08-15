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
 * Article model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Article_model extends CI_Model
{

	/*
	 * Article model class constructor
	 */

	function Article_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addArticle function
	 */

	function addArticle($title, $description, $link_text, $link_url, $meta_keywords, $meta_description)
	{
		// add the article
		// check the title is unique and append a number if it is not
		$title_add = 2;
		$title = trim($title);
		$seo_title = url_title($title, '-', TRUE);
		$safe_seo_title = $seo_title;
		while ($this->_checkSeoTitleExists($safe_seo_title)) {
			// keep trying numbers until the title is unique
			$safe_seo_title = $seo_title . '-' . $title_add;
			$title_add++;
		}
		$data = array(
			'title' => $title,
			'seo_title' => $safe_seo_title,
			'description' => $description,
			'link_text' => $link_text,
			'link_url' => $link_url,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description
		);
		// add the article
		$this->db->insert('article', $data);
		// get the article id and return it
		$article = $this->getArticleBySeoTitle($safe_seo_title);
		$id = $article->id;
		return $id;
	}

	/*
	 * updateArticle function
	 */

	function updateArticle($id, $title, $description, $link_text, $link_url, $meta_keywords, $meta_description)
	{
		// update the article
		// check the title is unique and append a number if it is not
		$title_add = 2;
		$title = trim($title);
		$seo_title = url_title($title, '-', TRUE);
		$safe_seo_title = $seo_title;
		while ($this->_checkSeoTitleExists($safe_seo_title, $id)) {
			// keep trying numbers until the title is unique
			$safe_seo_title = $seo_title . '-' . $title_add;
			$title_add++;
		}
		$data = array(
			'title' => $title,
			'seo_title' => $safe_seo_title,
			'description' => $description,
			'link_text' => $link_text,
			'link_url' => $link_url,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description
		);
		$this->db->where('id', $id);
		// update the article
		$this->db->update('article', $data);
	}

	/*
	 * deleteArticle function
	 */

	function deleteArticle($id)
	{
		// delete the article
		$this->db->where('id', $id);
		$this->db->delete('article');
	}

	/*
	 * _checkSeoTitleExists function
	 */

	function _checkSeoTitleExists($seo_title, $id = -1)
	{
		// check the title is not already being used
		$this->db->select('seo_title');
		$this->db->where('seo_title', $seo_title);
		// optionally ignore a particular article
		// this is used when editing so the current title of an article can be ignored
		if ($id != -1) {
			$this->db->where('id !=', $id);
		}
		// return TRUE if the title exists, FALSE if not
		$query = $this->db->get('article');
		if ($query->num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * countArticles function
	 */

	function countArticles()
	{
		// return the total number of articles
		return $this->db->count_all_results('article');
	}

	/*
	 * getArticlesByKeyword function
	 */

	function getArticlesByKeyword($keyword, $limit, $offset = 0)
	{
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		// find articles that match the keyword(s)
		// note that some keywords will be ignored as stop words
		$this->db->distinct();
		$this->db->where('MATCH (article.title, article.description) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
		$this->db->group_by("article.id");
		$query = $this->db->get('article');
		// return the articles
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result;
		}
		// no results
		return FALSE;
	}

	/*
	 * countKeywordArticles function
	 */

	function countKeywordArticles($keyword)
	{
		// find articles that match the keyword(s)
		$this->db->distinct();
		$this->db->where('MATCH (article.title, article.description) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
		$this->db->group_by("article.id");
		$query = $this->db->get('article');
		// return the number of results
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $query->num_rows();
		}
		// no results
		return FALSE;
	}

	/*
	 * getArticleBySeoTitle function
	 */

	function getArticleBySeoTitle($param)
	{
		// find an article based on its title and return the article
		$this->db->where('seo_title', $param);
		$this->db->limit(1);
		$query = $this->db->get('article');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// not found
		return FALSE;
	}

	/*
	 * getArticleById function
	 */

	function getArticleById($id)
	{
		// find an article based on its id and return the article
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('article');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// not found
		return FALSE;
	}

	/*
	 * getAllArticles function
	 */

	function getAllArticles($limit, $offset = 0)
	{
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('article');
		// return the articles
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

}

/* End of file article_model.php */
/* Location: ./application/models/article_model.php */