<?php
/**
 * Created by PhpStorm.
 * User: rangoy
 * Date: 07.01.18
 * Time: 07:29
 */

namespace LonghornClient;
/*
Okapi Longhorn PHP Wrapper: project class
With the persistent flag on (default) the project is automatically deleted by the class destructor after use.

 */
class Project {

    private $manager;
    private $project_id;
    private $persistent;

    private $srclang;
    private $trglangs;

    public function __construct(Manager $mgr, $persistent = false, $srclang = null, $trglangs=array())
    {
         $this->manager = $mgr;
         $this->project_id = $this->manager->newProject();
         $this->persistent = $persistent;
         $this->srclang = $srclang;
         $this->trglangs = $trglangs;
    }

    public function __destruct()
    {
        if(!$this->persistent) $this->manager->deleteProject($this->project_id);
    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles/help.out.html: Accesses the output file 'help.out.html' directly
    public function getOutPutFile($filename){

    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles.zip: Returns all output files in a zip archive
    public function getOutPutFilesZip(){

    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles/help.out.html: Accesses the output file 'help.out.html' directly
    public function listOutputFiles(){

    }

    // POST http://{host}/okapi-longhorn/projects/1/batchConfiguration: Uploads a Batch Configuration file
    public function inputBatchConfig($configcontent){
        return $configcontent;
    }

    // POST http://{host}/okapi-longhorn/projects/1/inputFiles.zip: Adds input files as a zip archive (the zip will be extracted and the included files will be used as input files)
    public function inputZip($zipcontent){
        return $zipcontent;
    }

    // PUT http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Uploads a file that will have the name 'help.html'
    public function inputFile($filecontent){
        return $filecontent;
    }

    // GET http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Retrieve an input file that was previously added with PUT or POST
    public function putBatchConfig($config){
        return $config;
    }

    // POST http://{host}/okapi-longhorn/projects/1/tasks/execute: Executes the Batch Configuration on the uploaded input files
    // POST http://{host}/okapi-longhorn/projects/1/tasks/execute/en-US/de-DE: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and the target language set to 'de-DE'
    // POST http://{host}/okapi-longhorn/projects/1/tasks/execute/en-US?targets=de-DE&targets=fr-FR: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and multiple target languages, 'de-DE' and 'fr-FR'
    public function executeTasks(){
        return true;
    }


    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param mixed $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return null
     */
    public function getSrclang()
    {
        return $this->srclang;
    }

    /**
     * @param null $srclang
     * @return Project
     */
    public function setSrclang($srclang)
    {
        $this->srclang = $srclang;
        return $this;
    }

    /**
     * @return array
     */
    public function getTrglangs()
    {
        return $this->trglangs;
    }

    /**
     * @param array $trglangs
     * @return Project
     */
    public function setTrglangs($trglangs)
    {
        $this->trglangs = $trglangs;
        return $this;
    }



    /**
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param Manager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

}


