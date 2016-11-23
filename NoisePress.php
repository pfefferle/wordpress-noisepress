<?php
/*
Plugin Name: NoisePress
Plugin URI: http://notizblog.org/2010/11/05/noisepress-nie-wieder-irrelevante-inhalte/
Description: An APML-Filter for WordPress-Feeds
Version: 0.1
Author: Matthias Pfefferle
Author URI: http://notizblog.org/
*/

include_once('lib/apml_parser.php');

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
    define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
    define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

add_action('parse_request', array('NoisePress', 'parseRequest'));
add_action('atom_head', array('NoisePress', 'feedHeader'));
add_action('rss_head', array('NoisePress', 'feedHeader'));
add_action('rdf_header', array('NoisePress', 'feedHeader'));
//add_action('rss2_head', array('NoisePress', 'feedHeader'));
add_filter('query_vars', array('NoisePress', 'queryVars'));
add_filter('pre_get_posts', array('NoisePress', 'filterFeed'));
add_filter('wp_title_rss', array('NoisePress', 'feedHeader'),      8 );

/**
 * NoisePress class
 * 
 * @author Matthias Pfefferle
 */
class NoisePress {
  /**
   * 
   */
  function filterFeed($query) {
    if ($query->is_feed && $query->query_vars['apml_feed']) {
      $apml_array = NoisePress::parseApml(urldecode($query->query_vars['apml_feed']));
      
      if ($apml_array) {
        $limit = (int)$query->query_vars['apml_feed_limit'];
        if (!$limit) {
          $limit = 10;
        } else if ($limit > count($apml_array)) {
          $limit = count($apml_array);
        }
        
        $tags = array();
        
        for ($i = 0; $i < $limit; $i++) {
          $tags[] = $apml_array[$i]['concept_key'];
        }
        
        $query->set('tag', implode(',',$tags));
      }
    }

    return $query;
  }

  /**
   * add 'ubiquity' as a valid query variables.
   *
   * @param array $vars
   * @return array
   */
  function queryVars($vars) {
    $vars[] = 'apml_feed';
    $vars[] = 'apml_feed_limit';
    $vars[] = 'noisepress';

    return $vars;
  }
  
  function parseApml($url) {
    $parser = new APML_Parser();
    // get concepts
    $apml_array = $parser->getAPMLConcepts($url);
    
    if (is_array($apml_array)) {
      usort($apml_array, array('NoisePress', 'sortApmlArray'));
    
      return $apml_array;
    } else {
      return false;
    }
  }
  
  function sortApmlArray($first, $second) {
    if ($first['value'] < $second['value']) {
      return true;
    } else {
      return false;
    }
  }
  
  function parseRequest() {
    global $wp;

    if( isset($wp->query_vars['noisepress']) && $wp->query_vars['noisepress'] == 'feedfilter' ) {
      NoisePress::printFeedFilterPage();
    }
  }
  
  function printFeedFilterPage() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ( function_exists( 'language_attributes' ) ) language_attributes(); ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo 'NoisePress APML-Filter' ?></title>
<?php
  wp_admin_css('install', true);
  do_action('admin_head');
?>
  <style type="text/css">
    label { width: 100px; display: block; float: left; }
    #apml_feed input {
      background: url(<?php echo WP_PLUGIN_URL ?>/noisepress/img/apml-icon-12x12.png) no-repeat 3px center;
      padding-left: 20px;
    }
  </style>
</head>
<body id="apml-page">
  <h1>NoisePress Feed/<abbr title="Attention Profiling Markup Language">APML</abbr>-Filter</h1>
  
  <img src="<?php echo WP_PLUGIN_URL ?>/noisepress/img/logo.png" alt="NoisePress Logo" style="float: right; padding-top: 20px;" />
  <p>Filter the notizBlog-Feed</p>
  
  <form class="apml-filter" id="apml-filter" method="get" action="<?php bloginfo('wpurl') ?>">
    <p>
      <label for="feed">Blog-Feed:</label>
      <select name="feed">
        <option value="atom">Atom 1.0</option>
        <option value="rss2">RSS 2.0</option>
        <option value="rdf">RSS 1.0 (RDF)</option>
        <option value="rss">RSS 0.92</option>
      </select>
    </p>
    
    <p id="apml_feed">
      <label for="apml_feed"><abbr title="Attention Profiling Markup Language">APML</abbr>-Feed:</label>
      <input type="input" name="apml_feed" />
    </p>
    
    <p>
      <label for="apml_feed_limit">Limit:</label>
      <input type="input" name="apml_feed_limit" value="10" />
    </p>
    
    <input type="submit" />
  </form>
  
  <h2>Further reading</h2>
  <ul>
    <li><a href="http://apml.org/" rel="bookmark">APML Workgroup</a></li>
    <li><a href="http://wordpress.org/extend/plugins/apml/">APML WordPress Plugin</a> - This plugin creates an APML Feed using the the native WordPress-Tags, -Categories, -Links and -Feeds.</li>
    <li><a href="http://notizblog.org/2008/05/09/apml-als-filter-ein-use-case/" rel="bookmark">APML als Filter (German)</a></li>
  </ul>
</body>
</html>
<?php
    die();
  }
  
  /**
   *
   * 
   */
  function feedHeader($var) {
    global $wp_query;
    if ($wp_query->query_vars['apml_feed'] != "") {
    	return " &#187; filtered by ".print_r($wp_query->query_vars['tag'], true) . " (powered by noisepress)";
    } else {
    	return $var;
    }
  }
}
?>
