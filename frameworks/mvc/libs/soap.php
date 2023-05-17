<?php
/**
 * soap类
 * 主要用于webservice调用
 */
class soap{
	private $webService_client_conf = array ('trace' => true, 'exceptions' => false, 'soap_version' => SOAP_1_2 );
	private $client;
	public $is_trace = FALSE;
	protected $result,$callfunction,$myParams;
    
	public function __construct($serviceURL) {
		$this->client=new SoapClient($serviceURL, $this->webService_client_conf);
	}
	
	/**
	 * 发送soap请求
	 * @param string $callback
	 * @param string $params
	 * @throws Exception
	 */
	public function request($callback, $params) {
		try {
			$res = $this->client->__soapCall($callback, $params);
			$this->myParams = $params;
			$this->result = $res;
			$this->callfunction = $callback;
			if($this->is_trace) {
				$this->trace();
			}
			if($this->is_success($res)){		
				 return $res;
			}else{
				 return false;
			}
		} catch ( SoapFault $execption ) {
			throw new Exception('調用soap異常  :'.$execption->message);
		}
	}
	
   /**
    * 检查是否出错
    * @param array $res
    */
   protected function is_success($res) {
   	if (is_soap_fault($res)) {
			$this->error=true;
			    print_r('SoapError:function callled:'.$this->callfunction."，調用soap錯誤: (faultcode: {$res->faultcode}, faultstring: {$res->faultstring},line：".__LINE__.")",'err_webservice_');
			    print_r('SoapError:function params:'.var_export($this->myParams ,true),'err_webservice_');
			    return false;
			}else{
				return true;
			}
 	 }
	
 	/**
 	 * 
 	 * 查看調用過程
 	 */
   public function trace(){
			print "<pre>\n";
			print "Request: \n".htmlspecialchars($this->client->__getLastRequest()) ."\n";
			print "Response: \n".htmlspecialchars($this->client->__getLastResponse())."\n";
			print "</pre>";
   }
}
?>
