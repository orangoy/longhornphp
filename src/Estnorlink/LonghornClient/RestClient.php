<?php
namespace Estnorlink\LonghornClient;
use Symfony\Component\HttpFoundation\Response;
/*
Provides helper classes for accessing Rest API
*/

class RestClient{

    protected $curl;
    protected $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    public function setUrl($url)
    {
        // Check that the given url is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new \Exception('Setting Longhorn URL: Not a valid URL');
        }

        $host = parse_url($url, PHP_URL_HOST);
        $port= parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);

        // Check that the server responds on the specified port
        if(!$socket =@ fsockopen($host, $port, $errno, $errstr, 30)) {
            throw new \Exception('Setting Longhorn URL: Could not contact server');
        } else {
            fclose($socket);
        }

        // Check that the actual url returns non-error response
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 200) {
            throw new \Exception('Setting Longhorn URL: Unexpected HTTP response code '.$httpCode.' from server');
        }
        $this->url = $url;
        return $this->url;
    }

    public function curlrequest($method, $path, $data = null, $datatype=null){
        // Common options
        foreach([
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_URL => rtrim($this->url, '/').$path,
                ] as $key => $value) $this->curlopt($key, $value);

        // Method definitions
        switch ($method) {

            case 'GET':
                break;

            case 'POST':
                $this->curlopt(CURLOPT_POST, true);
                $this->curlopt(CURLOPT_POSTFIELDS, $data);
                break;

            case 'DELETE':
                $this->curlopt(CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        // Execute, reset and return
        $ccontent = curl_exec($this->curl);
        $cinfo = curl_getinfo($this->curl);
        $response = new Response($ccontent,  $cinfo['http_code'], array( 'content-type' => $cinfo['content_type']));
        curl_reset($this->curl);
        return $response;
}

    protected function curlinit(){
            return curl_init();
     }

    public function curlfile($filename, $mime, $postname){
         if (function_exists('curl_file_create')) { // php 5.5+
             return curl_file_create($filename, $mime, $postname);
         } else { //
             return '@' . realpath($filename);
         }
     }

    protected function curlopt($key, $value){
        curl_setopt($this->curl, $key, $value);
    }
}
