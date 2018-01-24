<?php
namespace Estnorlink\LonghornClient;
/*
Okapi Longhorn PHP Wrapper: manager class. Provides helper methods for managing Longhorn projects and implements the following Longhorn REST HTTP methods.
*/
use GuzzleHttp\Client;

class Manager extends RestClient {

    private $restclient;


    public function __construct($url="http://127.0.0.1:8080/okapi-longhorn/")
    {
        if(defined('LONGHORNURL')){
            $url=LONGHORNURL;
        }

        try {
            $this->setUrl($url);
            // $this->curl = $this->curlinit();

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $url,
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ]);

        } catch (\Exception $e) {
            echo 'Exception: '.$e->getMessage();
            return false;
        }

        return false;
    }

    // POST http://{host}/okapi-longhorn/projects/new: Creates a new temporary project
    public function newProject(){
         return $this->tryCreate();
    }

    private function tryCreate(){
        /* Longhorn does not appear to return the URI of the created project.
        Ugly workaround is comparing list before and after creation.
        TODO: Find a better solution for determining new ID */
        do {
        $beforelist = $this->listProjectsArray();
        $this->curlrequest('POST', "/projects/new");
        $afterlist = $this->listProjectsArray();
        $new_ids = array_values(array_diff($afterlist, $beforelist));
        break;
        } while ( count($new_ids) != 1);
        return $new_ids[0];
        }

    public function isProject($project_id){
        try {
            $projects = $this->listProjectsArray();
        } catch (\Exception $e) {
            echo 'Exception: '.$e->getMessage();
        }
        if(in_array($project_id, $projects)) return true;
        else return false;
    }

    // DEL http://{host}/okapi-longhorn/projects/1: Deletes the project
    public function deleteProject($project_id){
        if ($this->isProject($project_id)){
            return $this->curlrequest('DELETE', "/projects/".$project_id);
        } else {
            return false;
        }
    }

    public function deleteAllProjects(){
        foreach ($this->listProjectsArray() as $project_id) {
            $this->deleteProject($project_id);
        }
    }

    // GET http://{host}/okapi-longhorn/projects: Returns a list of all projects on the server
    public function listProjects(){


        return $this->curlrequest('GET', "/projects");


    }

    public function listProjectsArray(){
        $projects_response = $this->listProjects();

        if (!$projects = simplexml_load_string($projects_response->getContent())) {
            return array();
        } else {
            return array_values((array) $projects->e);
        }
    }



}
