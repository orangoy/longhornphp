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

Implementing the following Longhorn REST HTTP methods:
POST http://{host}/okapi-longhorn/projects/1/batchConfiguration: Uploads a Batch Configuration file
POST http://{host}/okapi-longhorn/projects/1/inputFiles.zip: Adds input files as a zip archive (the zip will be extracted and the included files will be used as input files)
PUT http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Uploads a file that will have the name 'help.html'
GET http://{host}/okapi-longhorn/projects/1/inputFiles/help.html: Retrieve an input file that was previously added with PUT or POST
POST http://{host}/okapi-longhorn/projects/1/tasks/execute: Executes the Batch Configuration on the uploaded input files
POST http://{host}/okapi-longhorn/projects/1/tasks/execute/en-US/de-DE: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and the target language set to 'de-DE'
POST http://{host}/okapi-longhorn/projects/1/tasks/execute/en-US?targets=de-DE&targets=fr-FR: Executes the Batch Configuration on the uploaded input files with the source language set to 'en-US' and multiple target languages, 'de-DE' and 'fr-FR'
GET http://{host}/okapi-longhorn/projects/1/outputFiles: Returns a list of the output files generated
GET http://{host}/okapi-longhorn/projects/1/outputFiles/help.out.html: Accesses the output file 'help.out.html' directly
GET http://{host}/okapi-longhorn/projects/1/outputFiles.zip: Returns all output files in a zip archive

The project is automatically deleted by the destructor after use.

 */
class Project {

    private $manager;
    private $project_id;

    public function __construct(Manager $mgr)
    {
         $this->manager = $mgr;
         $this->project_id = $this->manager->newProject();
    }

    public function __destruct()
    {
        $this->manager->deleteProject($this->project_id);
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


