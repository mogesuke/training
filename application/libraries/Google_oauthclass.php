<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Google_oauthclass {

    /**
     * Oauthへアクセス用(codeを取得するページ)のURL
     * @return url
     */
    public function create_oauth_url() {
        return OAUTH_URL_BASE ."auth"
                        ."?client_id=" .OAUTH_CLIENT_ID
                        ."&scope=openid email profile"
                        ."&response_type=code"
                        ."&redirect_uri=" .base_url() ."top/callback"
                        ."&approval_prompt=force"
                        ."&access_type=offline";
    }

    /**
     * Token取得APIを実行し、Tokenを取得する。
     * @param $code
     * @return token(json)
     */
    public function get_token($code) {
        $url = OAUTH_URL_BASE ."token";

        $param = array(
            "client_id" => OAUTH_CLIENT_ID,
            "client_secret" => OAUTH_CLIENT_SECRET,
            "grant_type" => "authorization_code",
            "redirect_uri" => base_url() ."top/callback",
            "code" => $code
        );
        $CI =& get_instance();
        $CI->load->library('httpclass');
        return $CI->httpclass->execute_post($url, $param, NULL);
    }

    /**
     * Tokenを利用し、プロフィールを取得する。
     * @param $access_token
     * @return profile(json)
     */
    public function get_profile($access_token)
    {
        $url = "https://www.googleapis.com/plus/v1/people/me/openIdConnect";
        // header
        $header = array(
            "Authorization: OAuth " .$access_token
        );
        $CI =& get_instance();
        $CI->load->library('httpclass');
        return $CI->httpclass->execute_get($url, NULL, $header);
    }
}