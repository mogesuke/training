<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Httpclass {

    public function __construct()
    {
    }

	/**
	 * POST通信を行う。
     * @param $url URL文字列
     * @param $param パラメータの配列
     * @param $extra_header 追加するHttpHeaderの配列
     * @return 結果
	 *
	 */
	public function execute_post($url, $param, $extra_header) 
	{
		return $this->execute($url, TRUE, $param, $extra_header);
	}

	/**
	 * GET通信を行う。
     * @param $url URL文字列
     * @param $param パラメータの配列
     * @param $extra_header 追加するHttpHeaderの配列
     * @return 結果
	 *
	 */
	public function execute_get($url, $param, $extra_header) 
	{
		return $this->execute($url, FALSE, $param, $extra_header);
	}

    /**
     * http通信を行う。
     * @param $url URL文字列
     * @param $isPost true:post false:get
     * @param $param パラメータの配列
     * @param $extra_header 追加するHttpHeaderの配列
     * @return 結果
     */
    private function execute($url, $isPost, $param, $extra_header)
    {
		$data = "";
		$cp = curl_init();
		
		// サーバ証明書の検証は行わない。
		curl_setopt($cp, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cp, CURLOPT_SSL_VERIFYHOST, false);

		// curl_execの実行結果を文字列として取得できるように設定
		curl_setopt($cp, CURLOPT_RETURNTRANSFER, 1);

		// header
		$header = array(
            "Content-Type: application/x-www-form-urlencoded"
        );
		// extra header
		if (!is_null($extra_header))
		{
			$header = array_merge($header, $extra_header);
		}

        // param		
        if (!is_null($param)) 
        {
        	$param = http_build_query($param);
        	array_push($header, "Content-Length: ".strlen($param));
        	if ($isPost)
			{
        		curl_setopt($cp, CURLOPT_POST, true);
        		curl_setopt($cp, CURLOPT_POSTFIELDS, $param);
        	}
        	else
        	{
        		if (strpos($url, "?") === FALSE) 
        		{
        			$url = $url ."?" .$param;
        		}
        		else
        		{
        			$url = $url ."&" .$param;
        		}
        	}
        }
		// url
		curl_setopt($cp, CURLOPT_URL, $url);
        // header
		curl_setopt($cp, CURLOPT_HTTPHEADER, $header);

		$data = curl_exec($cp);
		curl_close($cp);
		return $data;
    }
}