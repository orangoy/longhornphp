<?php

namespace LonghornClient;
/*
Okapi Longhorn PHP Wrapper: project class
With the persistent flag on (default) the project is automatically deleted by the class destructor after use.
 */
class Project
{
    private $manager;
    private $project_id;
    private $persistent;
    private $srclang;
    private $trglangs;

    public function __construct(Manager $mgr, $persistent = false, $srclang = null, $trglangs = array())
    {
        $this->manager = $mgr;
        $this->project_id = $this->manager->newProject();
        $this->persistent = $persistent;
        $this->srclang = $srclang;
        $this->trglangs = $trglangs;
    }

    public function __destruct()
    {
        if (!$this->persistent) $this->manager->deleteProject($this->project_id);
    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles: Returns a list of the output files generated
    public function listOutputFiles()
    {
        return $this->manager->curlrequest('GET', "/projects/" . $this->project_id . "/outputFiles/");
    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles/help.out.html: Accesses the output file 'help.out.html' directly
    public function getOutPutFile($filename)
    {
        return $this->manager->curlrequest('GET', "/projects/" . $this->project_id . "/outputFiles/" . $filename);
    }

    // GET http://{host}/okapi-longhorn/projects/1/outputFiles.zip: Returns all output files in a zip archive
    public function getOutPutFilesZip()
    {
        return $this->manager->curlrequest('GET', "/projects/" . $this->project_id . "/outputFiles.zip");
    }

    // POST http://{host}/okapi-longhorn/projects/1/batchConfiguration: Uploads a Batch Configuration file
    public function inputBatchConfig($bconf)
    {
        $post = array('batchConfiguration'=> $this->manager->curlfile($bconf,'application/octet-stream',basename($bconf)));
        return $this->manager->curlrequest('POST', "/projects/" . $this->project_id . "/batchConfiguration", $post);
    }

    // POST http://{host}/okapi-longhorn/projects/1/inputFiles.zip: Adds input files as a zip archive (the zip will be extracted and the included files will be used as input files)
    public function inputZip($zipfile)
    {
        return $this->manager->curlrequest('POST', "/projects/" . $this->project_id . "/inputFiles.zip", $zipfile);
    }

    // PUT http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Uploads a file that will have the name 'help.html'
    public function inputFile($filename, $file)
    {
        return $this->manager->curlrequest('PUT', "/projects/" . $this->project_id . "/inputFiles/" . $filename, $file);
    }

    // GET http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Retrieve an input file that was previously added with PUT or POST
    public function getInputFile($filename)
    {
        return $this->manager->curlrequest('GET', "/projects/" . $this->project_id . "/inputFiles/" . $filename);
    }

    // POST http://{host}/okapi-longhorn/projects/1/tasks/execute: Executes the Batch Configuration on the uploaded input files
    // + POST ... /execute/en-US/de-DE: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and the target language set to 'de-DE'
    // + POST ... /execute/en-US?targets=de-DE&targets=fr-FR: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and multiple target languages, 'de-DE' and 'fr-FR'
    public function executeTasks()
    {
        if (!empty($this->srclang && count($this->trglangs) > 1)) {
            $trg = implode("&targets=", $this->trglangs);
            return $this->manager->curlrequest('POST', "/projects/" . $this->project_id . "/tasks/execute/" . $this->srclang . "?targets=" . $trg);
        } else if (!empty($this->srclang && count($this->trglangs) == 1)) {
            $trg = array_values($this->trglangs)[0];
            return $this->manager->curlrequest('POST', "/projects/" . $this->project_id . "/tasks/execute/" . $this->srclang . "/" . $trg);
        } else {
            return $this->manager->curlrequest('POST', "/projects/" . $this->project_id . "/tasks/execute");
        }
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
