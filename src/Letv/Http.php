<?php
namespace Anwelblue\Letv;
/**
 * 
 * @author Anwelblue
 *
 */
class Http
{
    /**
     * curl get
     * 
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function get($url,$data = []){
        $query = http_build_query($data);
        $url .= '?'.$query;
        $resource = curl_init();
        curl_setopt_array($resource, array(
            CURLOPT_HEADER => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));
        if (preg_match("/https:\/\//", $url)) {
            curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($resource, CURLOPT_POST, false);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }
    
    /**
     * curl post
     * 
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function post($url,$data = []){
        $resource = curl_init();
        curl_setopt_array($resource, array(
        CURLOPT_HEADER => false,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
        ));
        if (preg_match("/https:\/\//", $url)) {
            curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($resource, CURLOPT_POST, true);
        curl_setopt($resource, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }
    
}