<?PHP


class touclick_seccode{
	private $apiurl = 'http://api.touclick.com';
	private $apiacturl='http://admin.touclick.com';
	private $version = 'v3-0';
    private $platform = 'discuz';
	private $config = array();
	public function __construct(){
		$this->config = array(
			'pubkey'=>'',
			'prikey'=>'',
			'chkkey'=>'',
			'chkurl'=>'',
			'ip'=>'0.0.0.0',
			'username'=>'',
			'uid'=>'',
			'charset'=>'gbk',
			'pf'=>'discuz',
			'op'=>'1002',
			'version'=>$this->version,
		);
	}
	public function set($arr){
		$this->config = array_merge($this->config,$arr);
	}
	/*
	callback execute()
	true: is checked
	001: is not checked
	002: pubkey or prikey is false
	003: chkkey or chkurl is false
	*/
	public function execute(&$resRep){
		$data= array(
			"b" => $this->config['pubkey'],
			"z" => $this->config['prikey'],
			"i" => $this->config['chkkey'],
			"p" => $this->config['ip'],
			"un" => $this->config['username'],
			'ud' => $this->config['uid'],
		);
		$url='';
		$path='';
		if(empty($this->config['pubkey']) || strlen($this->config['pubkey']) != 36 || empty($this->config['prikey']) || strlen($this->config['prikey']) != 36){
 			return '002';
		}
		if(empty($this->config['chkkey']) || !$this->filterUrl($url, $path, $this->config['chkurl'])){
 			return '003';
		}
		$resRep = $this->tc_request($url.$path.'?'.http_build_query($data));
		if($resRep){
			$pos = strpos($resRep, "<<[yes]>>");
			if($pos === false){
 				return '001';
			}
            return true;
		}
		return  false;	
	}
	public function activate($cmd='selectstate'){
		if(!in_array($cmd,array('selectstate','active')))
			return false;
		if($cmd == 'active'){
			$b = $this->activate();
			if($b=='active')
				return $b;
		}
		$data = array(
			'cmd'=>$cmd,
			'website_key'=>$this->config['pubkey'],
			'private_key'=>$this->config['prikey'],
			'plugin_type'=>$this->version,
            'platform' => $this->platform
		);
		$back = $this->tc_request($this->apiacturl."/active.touclick",$data);
		if(empty($back))
			return false;
		$back = substr($back, 2, -2);
		return $back;
	}
	private function filterUrl(&$url, &$path, $testUrl) {
		$arrUrl = explode ( ",", $testUrl );
		if (! is_array ( $arrUrl ) || count ( $arrUrl ) != 2) {
			return false;
		}
		$tempUrl = explode ( ".", $arrUrl [0] );
		if (! is_array ( $tempUrl ) || count ( $tempUrl ) != 3) {
			return false;
		}
		$arrPath = explode ( ".", $arrUrl [1] );
		if (! is_array ( $arrPath ) || count ( $arrPath ) != 2) {
			return false;
		}
		if (! $this->filterStr ( $tempUrl [0] ) || ! $this->filterStr ( $arrPath [0] )) {
			return false;
		}
		$url = "http://" . $tempUrl [0] . ".touclick.com";
		$path = "/" . $arrPath [0] .".touclick";
		return true;
	}
	
	private function filterStr($str) {
		if (preg_match("/^[a-z0-9]+$/", $str) ) {
			return true;
		} else {
			return false;
		}
	}
	
	private function tc_request($url, $postdata=null) {
		$data = http_build_query ($postdata);
		if(function_exists('curl_exec')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if(!$postdata){
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}else{
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}
			@ $data = curl_exec($ch);
			curl_close($ch);
		}else{
			if($postdata)
				$url = $url.'?'.$data;
			$data = file_get_contents($url);
		}
		return $data;
	}
    
   
}
?>