<?php
/**
 * Project:    Simple mvc for apps
 * File:       http.php
 *
 * 通过php获取网页内容,提交表单等
 * 
 * Example:
 *   http::post($url,$data);
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Libaray
 * @version 1.0.0
 */
class http{
	/**
	 * 
	 * post方式发送请求
	 * @param string $url
	 * @param array $data
	 */
	public static  function post($url,$data){
		$str='';
		foreach ($data as $key => $val){
			$str.="&$key=$val";
		}
		$http = curl_init();
		curl_setopt($http, CURLOPT_URL, $url);
		curl_setopt($http, CURLOPT_HEADER, 0);
		curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($http, CURLOPT_FOLLOWLOCATION , 1);
		curl_setopt($http, CURLOPT_POST, 1);
		curl_setopt($http, CURLOPT_POSTFIELDS, $str);
		$output = curl_exec($http);
		curl_close($http);
		return $output;
	}

	/**
	 * 
	 * get方式发送请求
	 * @param string $url
	 * @param array $data
	 */
	public static function get($url,$data){
		if($data){
			if(!strstr($url, '?')){
				$url.='?';
			}
			foreach ($data as $key => $val){
				$url.="&$key=$val";
			}
		}
		$http = curl_init();
		curl_setopt($http, CURLOPT_URL, $url);
		curl_setopt($http, CURLOPT_HEADER, 0);
		curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($http, CURLOPT_FOLLOWLOCATION , 1);
		$output = curl_exec($http);
		curl_close($http);
		return $output;
	}
	
}
