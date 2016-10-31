<?php
/**
 * Created by PhpStorm.
 * User: snake
 * Date: 10/31/16
 * Time: 16:34
 */

namespace IgorGoroun\FTNWBundle\Entity;


class reCaptcha
{
    private $secret=null;
    private $response=null;
    private $url=null;
    private $remoteip=null;
    //public $success=false;


    function __construct($response=null,$secret=false,$url=false) {
        $this->secret = $secret;
        $this->response = $response;
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function verifyResponse() {
        if (!$this->response == null) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                http_build_query(array('secret' => $this->secret, 'response' => $this->response))
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close($ch);
            if ($recaptcha = json_decode($server_output)) {
                //dump($recaptcha);
                if ($recaptcha->success === true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } else {
            return false;
        }

    }
}