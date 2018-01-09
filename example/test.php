<?php
require_once "../vendor/autoload.php";
require_once "../src/LonghornClient/RestClient.php";
require_once "../src/LonghornClient/Manager.php";
require_once "../src/LonghornClient/Project.php";
use LonghornClient\Manager;
use LonghornClient\Project;

/* These constants can be used for source and target language defaults */
define('LONGHORNSRCLANG','et-EE');
define('LONGHORNTRGLANG','nb-NO');

/* Instantiate manager */
$mgr = new Manager;
/* Instantiate Project -- if persistent flag is true, it will not be deleted when the script terminates */
$p = new Project($mgr, false);

/* List projects to demonstrate it exists */
$projects = $mgr->listProjectsArray();

/* The source and target methods used after project creation will override any defaults */
$p->setTrglang('sv-FI');

/* Upload the batch config file */
$bconf =dirname(__FILE__)."/test.bconf";
$p->inputBatchConfig($bconf);

/* Upload an input file */
$docx = dirname(__FILE__)."/test.docx";
//$p->inputFile($docx);
echo $p->inputZip(dirname(__FILE__)."/test.zip")->getStatusCode();


/* List all input files array */
$infiles = $p->listInputFilesArray();

/* Execute the project */
$p->executeTasks();

/* List output files as array */
$outfiles = $p->listOutputFilesArray();

/* Delete all projects */
// $mgr->deleteAllProjects();

echo "Projects:\n".implode(" ", $projects)."\n\n";
echo "Current project: ".$p->getProjectId()."\n\n";
echo "Input files:\n".implode(" ", $infiles)."\n\n";
echo "Output files:\n".implode(" ", $outfiles)."\n\n";

