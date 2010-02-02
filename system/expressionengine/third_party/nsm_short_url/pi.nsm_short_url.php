<?php

$plugin_info = array(
	'pi_name' => 'NSM URL shortener',
	'pi_version' => '1.0',
	'pi_author' => 'Leevi Graham',
	'pi_author_url' => 'http://leevigraham.com/',
	'pi_description' => 'Shorten URLs for the post you are viewing, adding meta LINK in head, and A link in body, and a permanent redirect',
	'pi_usage' => "Refer to the README"
);

class Nsm_short_url {
	
	// entry_ attributes
	private $entry_id 				= FALSE;
	private $url_title 				= FALSE;
	private $comment_url 			= FALSE;

	// private attributes
	protected $segment_type			= FALSE;
	private $query_string			= FALSE;
	private $entry_id_trigger		= "-";

	private $long_url 				= FALSE;
	private $short_url 				= FALSE;

	public $return_data =			'';

	function Nsm_short_url()
	{
		$this->EE =& get_instance();

		$this->entry_id 			= $this->EE->TMPL->fetch_param('entry_id');
		$this->url_title 			= $this->EE->TMPL->fetch_param('url_title');
	}

	
	public function link()
	{
		if(!$this->_buildShortUrl())
			return FALSE;

		$link_content = $this->EE->TMPL->fetch_param('link_content') ? $this->EE->TMPL->fetch_param('link_content') : $this->entry_id;
		return "<a href='{$this->short_url}' rev='canonical' rel='alternate shorter'>{$link_content}</a>";
	}

	public function link_url()
	{
		return $this->_buildShortUrl();
	}

	public function meta()
	{
		if(!$this->_buildShortUrl())
			return FALSE;

		return "<link rev='canonical' rel='alternate shorter' href='{$this->short_url}' />";
	}

	/**
	 * Redirect tag. Redirect a current URL
	 **/
	public function redirect(){

		$segment_type = 'url_title';
		$key = $this->EE->TMPL->fetch_param('key') ? $this->EE->TMPL->fetch_param('key') : $this->EE->TMPL->segment_vars['segment_2'];
		if(strncmp($key, $this->entry_id_trigger, 1) == 0)
		{
			$this->entry_id = substr($key, 1);
			$segment_type = 'entry_id';
		}
		else
		{
			$this->entry_id = $this->EE->TMPL->segment_vars['segment_2'];
		}
		// if(!is_numeric($this->entry_id) || !$this->_getEntryAttributes())
		// 	exit("404");

		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $this->_buildLongURL($segment_type));
		exit("Redirecting...");
	}

	/**
	 * Build a short URL
	 **/
	private function _buildShortUrl()
	{
		if(!$this->entry_id && !$this->_getEntryAttributes())
			return FALSE;

		$host 						= $this->EE->TMPL->fetch_param('host');
		$redirect_with_entry_id 	= ($this->EE->TMPL->fetch_param('redirect_with_entry_id') == "yes") ? TRUE : FALSE;
		$template_group 			= $this->EE->TMPL->fetch_param('template_group') ? $this->EE->TMPL->fetch_param('template_group') : 's';

		$key = ($redirect_with_entry_id) ? $this->entry_id_trigger . $this->entry_id : $this->entry_id;
		return $this->short_url = ($host == FALSE)
					? $this->EE->functions->create_url($template_group . "/" . $key)
					: $host . "/" . $key;
	}

	/**
	 * Expand a short URL
	 **/
	private function _buildLongURL($segment_type)
	{
		$this->_getEntryAttributes();
		$pages = $this->EE->config->item('site_pages');

		$this->query_string = (array_key_exists($this->entry_id, $pages['uris']))
								? $pages['uris'][$this->entry_id]
								: $this->comment_url . $this->$segment_type;

		return $this->long_url = $this->EE->functions->create_url($this->query_string);
	}

	/**
	 * Get the entry attributes including channel comment url
	 **/
	private function _getEntryAttributes()
	{
		if(!$this->entry_id && !$this->url_title)
			return FALSE;

		$this->EE->db->select('channel_titles.channel_id, channels.comment_url, channel_titles.entry_id, channel_titles.url_title')
						->from('channels')
						->join('channel_titles', 'channels.channel_id = channel_titles.channel_id');

		if($this->entry_id)
			$this->EE->db->where('channel_titles.entry_id', $this->entry_id);
		
		if($this->url_title)
			$this->EE->db->where('channel_titles.url_title', $this->url_title);

		$query = $this->EE->db->get();

		if(!$query->num_rows())
			return FALSE;

		$row = $query->row();

		$this->entry_id = $row->entry_id;
		$this->url_title = $row->url_title;
		$this->comment_url = $row->comment_url;

		return $query->num_rows();
	}
}