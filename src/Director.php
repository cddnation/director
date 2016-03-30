<?php

namespace Dao;

use Exception;

class Director{

	/**
	 * $_data
	 *
	 * The data containing the URL redirects
	 * @var null
	 */
	private $_data = null;

	/**
	 * $_match
	 *
	 * Contains the matched URL from the data.
	 * @var null
	 */
	private $_match = null;

	/**
	 * $testMode
	 *
	 * When set to true, various data is output for debugging purposes.
	 * @var boolean
	 */
	private $testMode = false;

	/**
	 * $defaultLocation
	 *
	 * the default location to redirect to if no value is found in the data.
	 * @var string
	 */
	private $defaultLocation = '';

	/**
	 * $permanent
	 *
	 * Whether the redirect is permanent (301 v 302).
	 * @var boolean
	 */
	private $permanent = false;

	/**
	 * $preserveQuery
	 *
	 * Whether to preserve the query string in the redirect.
	 * @var boolean
	 */
	private $preserveQuery = false;


	/**
	 * __construct
	 *
	 * Set the data, set the user specified properties and validate them.
	 * 
	 * @param array 	$data 	Array of link objects for the redirects.
	 * @param array  	$arr 	Array of user defined options.
	 */
	public function __construct($data = null, $arr = array()) {
		
		//set the data
		$this->data = $data;

		//set properties
		$this->_setProperties($arr);

		//validate properties
		$this->_validateProperties();
	}


	/**
	 * point
	 *
	 * Point to the new url that was found as a match in the data.
	 */
	public function pointToMatch() {

		//if we have a match, direct to it.
		if($this->_match){

			//build the url from options
			$url = $this->_buildDestinationUrl($this->_match);

			//if not in test mode
			if(!$this->testMode){

				//redirect
				header('Location: ' . $url, true, $this->permanent? 301 : 302);
			
			//else output what we would have done
			}else {
				echo "<br />Redirecting to <strong>" . $url . "</strong> as a " . (($this->permanent)? "301" : "302") . " redirect.";
			}
			
			//found a redirect, we're done
			exit();
		}
	}


	/**
	 * _buildDestinationUrl
	 *
	 * Build the URL. At the moment this just finds the to property of the url
	 * object and returns it.
	 *
	 * The plan is to apply options to this value, and give the user the opportunity
	 * to preserve queries, set redirect type etc...
	 * 
	 * @param  object 	$url 	The object containing URL information.
	 * @return string  			The URL to redirect to
	 */
	private function _buildDestinationUrl($url) {

		return $result = $url->to;
		
		//if options are set
		// if(isset($url->options)){
		// 	$options = $url->options;

		// 	if(isset($options->preserveQuery)){
				
		// 	}
		// }
	}


	/**
	 * findMatch
	 *
	 * Find a match from the data for the URL passed as param.
	 *
	 * @param  string 	$url 	The URL we're looking to match in the data.
	 * @return object 			The URL object from the data that was matched (null if not found).
	 */
	public function findMatch($url) {

		//check it exists and that it is an array
		if(!$this->data || !is_array($this->data)) throw new Exception('Directory data needs to be set to find a match. It must also be an array.');

		//show what we're looking for
		if($this->testMode) echo "Looking for <strong>" . $url . "</strong><br />";
		
		//loop the data
		foreach($this->data as $link){

			//find a match
			if($url == $link->from){
				$this->_match = $link;
			}
		}

		//output whether a match was found.
		if($this->testMode) echo "<br />" . ((isset($this->_match->to))? "Found a match." : "Match not found.");

		//return the match
		return $this->_match;
	}


	/**
	 * _validateProperties
	 *
	 * Validates the object properties - the ones set by the user.
	 */
	private function _validateProperties() {

		//validate defaultLocation property
		if(!is_string($this->defaultLocation)) throw new Exception('Directory property defaultLocation needs to be a string value.');
	}


	/**
	 * _setProperties
	 *
	 * Options to be set against the object as passed in via the construct.
	 *
	 * The properties must be defined in order to be set.
	 * 
	 * @param array 	$arr 	Array of values to set as properties.
	 */
	private function _setProperties($arr) {

		//make sure we're dealing with an array
		if(!is_array($arr)) throw new Exception('Directory properties need to be set with an array of options.');

		//loop through the options
		foreach($arr as $option => $value) {

			//check property exists
			if(!property_exists($this, $option)) throw new Exception('Directory property '. $option .' does not exist.');

			//set the option
			$this->{$option} = $value;
		}
	}
}