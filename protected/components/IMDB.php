<?php
/**
 * Created by JetBrains PhpStorm.
 * User: andy
 * Date: 10.12.14
 * Time: 16:11
 * Class to get data from IMDB.com via curl
 */

class IMDB extends CApplicationComponent
{
	private $baseurl = 'https://app.imdb.com/';
	private $params = array(
		'api' => 'v1',
		'appid' => 'iphone1_1',
		'apiPolicy' => 'app1_1',
		'apiKey' => '2wex6aeu6a8q9e49k7sfvufd6rhh0n',
		'locale' => 'en_US',
		'timestamp' => '0',
	);



	/**
	 * Build URL based on the given parameters
	 *
	 * @param        $method
	 * @param string $query
	 * @param string $parameter
	 *
	 * @return string
	 */
	private function build_url($method, $query = "", $parameter = "")
	{

		// Set timestamp parameter to current time
		$this->params['timestamp'] = $_SERVER['REQUEST_TIME'];

		// Build the URL and append query if we have one
		$unsignedUrl = $this->baseurl . $method . '?' . http_build_query($this->params);
		if (!empty($parameter) AND !empty($query)) $unsignedUrl .= '&' . $parameter . '=' . urlencode($query);

		// Generate a signature and append to unsignedUrl to sign it.
		$sig = hash_hmac('sha1', $unsignedUrl, $this->params['apiKey']);
		$signedUrl = $unsignedUrl . '&sig=app1-' . $sig;


		return $signedUrl;
	}

	/**
	 * Top 250 Chart
	 *
	 * @return mixed
	 */
	public function chart_top(){
		$requestURL = $this->build_url('chart/top');
		$json = $this->fetchJSON($requestURL);
		if(isset($json->error) && is_object($json->error)){
			$data = $this->errorResponse($json->error);
		}
		else{
			$data = $json->data->list->list;
		}

		return $data;
	}

	/**
	 * Perform CURL request on the API URL to fetch the JSON data
	 *
	 * @param $apiUrl
	 *
	 * @return mixed
	 */
	private function fetchJSON($apiUrl){
		$ch = curl_init($apiUrl);
		$headers[] = 'Connection: Keep-Alive';
		$headers[] = 'Content-type: text/plain;charset=UTF-8';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_ENCODING , 'deflate');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
		$json = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		// Errors?
		if ($curl_errno > 0){
			$data = new stdClass;
			$data->error->message = 'cURL Error '.$curl_errno.': '.$curl_error;
		}
		else{
			// Decode the JSON response
			$data = json_decode($json);
		}

		return $data;
	}

	/**
	 * Basic error handling
	 *
	 * @param      $obj
	 * @param bool $returnArray
	 *
	 * @return array
	 */
	public function errorResponse($obj, $returnArray=false){
		$s = new stdClass;
		$s->status = $obj->status;
		$s->code = $obj->code;
		$s->message = $obj->message;
		$s->response = 0;
		$s->response_msg = "Fail";

		if($returnArray) return (array)$s;
		else return $s;
	}
}