<?php

include('config.php');

// version
define('NSM_SHORT_URL_VERSION', '1.0.0');

mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
mysql_select_db(DB_NAME);

/**
 * Redirect tag. Redirect a current URL
 **/
function redirect(){

	if(!isset($_GET['key']))
		return FALSE;

	$key = $_GET['key'];

	$segment_type = 'url_title';

	if(strncmp($key, ENTRY_ID_TRIGGER, strlen(ENTRY_ID_TRIGGER)) == 0)
	{
		$key = substr($key, strlen(ENTRY_ID_TRIGGER));
		$segment_type = 'entry_id';
	}

	$entry_id = $key;

	if(is_numeric($entry_id) && ($entry_attributes = _getEntryAttributes($entry_id)))
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . _buildLongURL($entry_attributes, $segment_type));
		die('Redirecting');
	}

	return FALSE;

}

function _getEntryAttributes($entry_id)
{
	$prefix = DB_PREFIX;
	$sql = "SELECT `{$prefix}channel_titles`.`channel_id` as channel_id,
					`{$prefix}channel_titles`.`entry_id` as entry_id,
					`{$prefix}channel_titles`.`url_title` as url_title,
					`{$prefix}channels`.`comment_url` as comment_url
			FROM `{$prefix}channels`
			JOIN `{$prefix}channel_titles` 
			ON `{$prefix}channels`.`channel_id` = `{$prefix}channel_titles`.`channel_id`
			WHERE `{$prefix}channel_titles`.`entry_id` = {$entry_id}";

	if ($result = mysql_query($sql))
	{
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			return $row;
		}
	}
	return FALSE;
}

function _buildLongURL($entry_attributes, $segment_type)
{
	$pages = _getSitePages();
	$query_string = (array_key_exists($entry_attributes["entry_id"], $pages['uris']))
							? $pages['uris'][$entry_attributes["entry_id"]]
							: $entry_attributes["comment_url"] . $segment_type;
	
	return SITE_URL . $query_string;
	
}

function _getSitePages()
{
	$prefix = DB_PREFIX;
	$sql = "SELECT `{$prefix}sites`.`site_pages` as site_pages FROM `{$prefix}sites`";
	if ($result = mysql_query($sql))
	{
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			return unserialize(base64_decode($row['site_pages']));
		}
	}
	return FALSE;
}

function _getSiteTemplatePrefs()
{
	$prefix = DB_PREFIX;
	$sql = "SELECT `site_template_preferences` FROM `{$prefix}sites`";
	if ($result = mysql_query($sql))
	{
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			return unserialize(base64_decode($row['site_template_preferences']));
		}
	}
	return FALSE;
}

if(!redirect())
	header('HTTP/1.0 404 Not Found');

?>

<h1>404 not found</h1>
<p>You've attempted to use a shortended URL that no longer exists.</p>
<p>The original site you were looking for is: <a href="<?php print SITE_URL ?>"><?php print SITE_URL ?></a>.</p>