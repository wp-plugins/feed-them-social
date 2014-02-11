<?php

/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * The first PHP Library to support FTSAuth for Twitter's REST API.
 */

/* Load FTSAuth lib. You can find it at http://FTSAuth.net */
require_once('FTSAuth.php');

/**
 * Twitter FTSAuth class
 */
class TwitterFTSAuth {
  /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last API call. */
  public $url;
  /* Set up the API root URL. */
  public $host = "https://api.twitter.com/1.1/";
  /* Set timeout default. */
  public $timeout = 30;
  /* Set connect timeout. */
  public $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public $ssl_verifypeer = FALSE;
  /* Respons format. */
  public $format = 'json';
  /* Decode returned json data. */
  public $decode_json = TRUE;
  /* Contains the last HTTP headers returned. */
  public $http_info;
  /* Set the useragnet. */
  public $useragent = 'TwitterFTSAuth v0.2.0-beta2';
  /* Immediately retry the API call if the response was not successful. */
  //public $retry = TRUE;




  /**
   * Set API URLS
   */
  function accessTokenURL()  { return 'https://api.twitter.com/OAuth/access_token'; }
  function authenticateURL() { return 'https://api.twitter.com/OAuth/authenticate'; }
  function authorizeURL()    { return 'https://api.twitter.com/OAuth/authorize'; }
  function requestTokenURL() { return 'https://api.twitter.com/OAuth/request_token'; }

  /**
   * Debug helpers
   */
  function lastStatusCode() { return $this->http_status; }
  function lastAPICall() { return $this->last_api_call; }

  /**
   * construct TwitterFTSAuth object
   */
  function __construct($consumer_key, $consumer_secret, $FTSAuth_token = NULL, $FTSAuth_token_secret = NULL) {
    $this->sha1_method = new FTSAuthSignatureMethod_HMAC_SHA1();
    $this->consumer = new FTSAuthConsumer($consumer_key, $consumer_secret);
    if (!empty($FTSAuth_token) && !empty($FTSAuth_token_secret)) {
      $this->token = new FTSAuthConsumer($FTSAuth_token, $FTSAuth_token_secret);
    } else {
      $this->token = NULL;
    }
  }


  /**
   * Get a request_token from Twitter
   *
   * @returns a key/value array containing FTSAuth_token and FTSAuth_token_secret
   */
  function getRequestToken($FTSAuth_callback = NULL) {
    $parameters = array();
    if (!empty($FTSAuth_callback)) {
      $parameters['FTSAuth_callback'] = $FTSAuth_callback;
    } 
    $request = $this->FTSAuthRequest($this->requestTokenURL(), 'GET', $parameters);
    $token = FTSAuthUtil::parse_parameters($request);
    $this->token = new FTSAuthConsumer($token['FTSAuth_token'], $token['FTSAuth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @returns a string
   */
  function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
    if (is_array($token)) {
      $token = $token['FTSAuth_token'];
    }
    if (empty($sign_in_with_twitter)) {
      return $this->authorizeURL() . "?FTSAuth_token={$token}";
    } else {
       return $this->authenticateURL() . "?FTSAuth_token={$token}";
    }
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @returns array("FTSAuth_token" => "the-access-token",
   *                "FTSAuth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham")
   */
  function getAccessToken($FTSAuth_verifier = FALSE) {
    $parameters = array();
    if (!empty($FTSAuth_verifier)) {
      $parameters['FTSAuth_verifier'] = $FTSAuth_verifier;
    }
    $request = $this->FTSAuthRequest($this->accessTokenURL(), 'GET', $parameters);
    $token = FTSAuthUtil::parse_parameters($request);
    $this->token = new FTSAuthConsumer($token['FTSAuth_token'], $token['FTSAuth_token_secret']);
    return $token;
  }

  /**
   * One time exchange of username and password for access token and secret.
   *
   * @returns array("FTSAuth_token" => "the-access-token",
   *                "FTSAuth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham",
   *                "x_auth_expires" => "0")
   */  
  function getXAuthToken($username, $password) {
    $parameters = array();
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = $this->FTSAuthRequest($this->accessTokenURL(), 'POST', $parameters);
    $token = FTSAuthUtil::parse_parameters($request);
    $this->token = new FTSAuthConsumer($token['FTSAuth_token'], $token['FTSAuth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for FTSAuthRequest.
   */
  function get($url, $parameters = array()) {
    $response = $this->FTSAuthRequest($url, 'GET', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wrapper for FTSAuthRequest.
   */
  function post($url, $parameters = array()) {
    $response = $this->FTSAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for FTSAuthReqeust.
   */
  function delete($url, $parameters = array()) {
    $response = $this->FTSAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an FTSAuth / API request
   */
  function FTSAuthRequest($url, $method, $parameters) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}.{$this->format}";
    }
    $request = FTSAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    switch ($method) {
    case 'GET':
      return $this->http($request->to_url(), 'GET');
    default:
      return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  function http($url, $method, $postfields = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
    return $response;
  }

  /**
   * Get the header info to store.
   */
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
}