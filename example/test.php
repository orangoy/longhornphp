<?php
require_once "../vendor/autoload.php";
require_once "../src/LonghornClient/RestClient.php";
require_once "../src/LonghornClient/Manager.php";
require_once "../src/LonghornClient/Project.php";

use LonghornClient\Manager;
use LonghornClient\Project;

$mgr = new Manager;
$p = new Project($mgr, true);

$currentDir = dirname(__FILE__);
$bconf =$currentDir."/test.bconf";
$docx = $currentDir."/test.docx";

//$docxfile=file($docx);

// $p->inputBatchConfig($bconf);


 print_r($p->inputFile($docx)->getStatusCode());



print_r($mgr->listProjects());
print_r((array) simplexml_load_string($p->listInputFiles()->getContent()));
print_r((array) simplexml_load_string($p->listOutputFiles()->getContent()));





//echo "\n";
//print_r($p->inputFile(basename($docx), $docx)->getStatusCode());
//echo "\n";
// print_r($p->inputFile("test.docx", $docx));
// print_r($p->executeTasks());
// echo $p->getProjectId() . "\n";
// $mgr->deleteAllProjects();