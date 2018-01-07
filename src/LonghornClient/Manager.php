<?php
namespace LonghornClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/*
Okapi Longhorn PHP Wrapper: manager class:
Provides helper classes for managing Longhorn projects and implements the following Longhorn REST HTTP methods:
POST http://{host}/okapi-longhorn/projects/new: Creates a new temporary project and returns its URI (e.g. http://localhost/okapi-longhorn/projects/1)
GET http://{host}/okapi-longhorn/projects: Returns a list of all projects on the server
DEL http://{host}/okapi-longhorn/projects/1: Deletes the project
*/

class Manager {
    private $url;
    private $curl;

    /**
     * @param mixed $fi
     */
    public function __construct($url="http://localhost:8080/okapi-longhorn/")
    {
        try {
            $this->setUrl($url);
            $this->curl = $this->curlinit();
        } catch (\Exception $e) {
            echo 'Exception: '.$e->getMessage();
            return false;
        }
    }

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

    public function newProject(){
         return $this->tryCreate();
    }

    private function tryCreate(){
        /* Longhorn does not appear to return the URI of the created project.
        Ugly workaround is comparing list before and after creation.
        TODO: Find a better solution for determining new ID */
        do {
        $beforelist = $this->listProjects();
        $this->curlrequest('POST', "/projects/new");
        $afterlist = $this->listProjects();
        $new_ids = array_values(array_diff($afterlist, $beforelist));
        break;
        } while ( count($new_ids) != 1);
        return $new_ids[0];
        }

    public function isProject($project_id){
        try {
            $projects = $this->listProjects();
        } catch (\Exception $e) {
            echo 'Exception: '.$e->getMessage();
        }
        if(in_array($project_id, $projects)) return true;
        else return false;
    }

    public function deleteProject($project_id){
        if ($this->isProject($project_id)){
            return $this->curlrequest('DELETE', "/projects/".$project_id);
        } else {
            return false;
        }
    }

    public function deleteAllProjects(){
        foreach ($this->listProjects() as $project_id) {
            $this->deleteProject($project_id);
        }
    }

    public function listProjects(){
        $projects_response = $this->curlrequest('GET', "/projects");

        if (!$projects = simplexml_load_string($projects_response->getContent())) {
            return array();
        } else {
            return (array) $projects->e;
        }
    }

    private function curlrequest($method, $path, $data = null){
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
            case 'PUT':
                $this->curlopt(CURLOPT_PUT, true);
                $this->curlopt(CURLOPT_INFILE, $data);
                $this->curlopt(CURLOPT_INFILESIZE, strlen($data));
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

    private function curlinit(){
            return curl_init();
     }

    private function curlopt($key, $value){
        curl_setopt($this->curl, $key, $value);
    }
}
