<?php
/**
 * Soundcloud Widget Helper
 *
 * Get the code for a soundcloud widget from URL via oEmbed
 *
 * @author Marc Löhe
 * @license MIT (http://www.opensource.org/licenses/mit-license.php)
 */

class SoundcloudWidgetHelper extends AppHelper {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * @var string Soundcloud oEmbed base URL
 * @access private
 */
  private $_baseUrl = 'http://soundcloud.com/oembed';

/**
 * Array of iframe options
 *
 * @var array
 */
  private $_iframeOpts = array( 
    'width'       => '100%',
    'frameborder' => 0,
  );

/**
 * Player Variables
 *  
 * @var array
 * @see http://developers.soundcloud.com/docs/html5-widget#widget-params
 */ 
  private $_playerVars = array(
    'auto_play' => 'false',
    'buying' => 'true',
    'liking' => 'true',
    'download' => 'true',
    'sharing' => 'true',
    'show_artwork' => 'true',
    'show_comments' => 'true',
    'show_playcount' => 'true',
    'show_user' => 'true',
    'start_track' => 0,
  );

/**
 * @var array oEmbed Query parameters
 * @access private
 * @see http://developers.soundcloud.com/docs/oembed#parameters
 */
  private $_oembedParams = array(
    'format' => 'json',
    'iframe' => 'true',
  );

/**
 * @var Curl object
 * @access private
 */
  private $_ch;

/**
 * Close CURL on destruction
 *
 */
  public function __destruct(){
    if(!empty($this->curl)){
      curl_close($this->curl);
    }
  }

/**
 * Get widget code by URL
 *
 * @param string $url Soundcloud URL
 * @param array $iframeOpts Options for iframe
 * @param array $playerVars Options for player
 * @access public
 */
  public function widget($url = null, $iframeOpts = array(), $playerVars = array())
  {
    if ($url == null || !is_string($url))
    {
      return '';
    }
    $oembedParams = array_merge($this->_oembedParams, array(
      'url' => $url,
    ));
    if (isset ($playerVars['maxheight']))
    {
      $oembedParams['maxheight'] = $playerVars['maxheight'];
      unset($playerVars['maxheight']);
    }
    $query = http_build_query($oembedParams);
    $get = $this->_baseUrl . '?' . $query;
    try
    {
      $data = $this->_curlGetData($get);
    } catch (Exception $e) {
      return '';
    }
    if (!empty($data))
    {
      $data = json_decode($data, true);
      if (!isset($iframeOpts['height']) &&
        isset($data['height']))
      {
        $iframeOpts['height'] = $data['height'];
      }
      if (isset($data['html']) &&
        !empty($data['html']))
      {
        $src = $this->_getHtmlAttribute('src', $data['html']);
        return $this->_buildPlayer($src, $iframeOpts, $playerVars);
      }
    }
    return '';
  }

/**
 * Build Player
 *
 * @param string $url oEmbed Player Url
 * @param array $iframeOpts Options for iframe
 * @param array $playerVars Options for player
 */
  private function _buildPlayer($url = null, $iframeOpts = array(), $playerVars = array())
  {
    $url = parse_url($url);
    parse_str($url['query'], $params);
    $params = array_merge($params, array_merge($this->_playerVars, $playerVars));
    $src = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . http_build_query($params);
    $iframeOpts = array_merge($this->_iframeOpts, $iframeOpts);
    $iframeOpts['src'] = $src;
    return $this->Html->tag('iframe', '', $iframeOpts);
  }

/**
 * Retrieve external data with CURL
 *
 * @access private
 * @param string $uri URI to retrieve
 * @return mixed Retrieved data
 */
    private function _curlGetData($url = null)
    {
      if ($url === null) {
        throw new InvalidArgumentException("Invalid URL");
      }
      if (!empty($this->_ch))
      {
        curl_close($this->_ch);
      }
      $this->_ch = curl_init();
      $timeout = 15;
      curl_setopt($this->_ch, CURLOPT_URL, $url);
      curl_setopt($this->_ch, CURLOPT_FAILONERROR, 1);
      curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $data = curl_exec($this->_ch);
      curl_close($this->_ch);
      return $data;
    }

  /**
   * Get attribute of HTML tag
   *
   * @param string $attrib Attribute to look for
   * @param string $tag HTML tag to look in
   * @return mixed Attribute content or false if not found
   */
  private function _getHtmlAttribute($attrib, $tag){
		$reg = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
		if (preg_match($reg, $tag, $match)) {
			return $match[2];
		}
		return false;
	}

/**
 * Strip query string of a URL
 *
 * @param string $url URL
 * @return URL without query string
 * @access private
 */
  private function _stripUrlQuery($url = '') {
    return preg_replace('/\?.*/', '', $url);
  }

}
