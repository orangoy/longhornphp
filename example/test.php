<?php
require_once "../vendor/autoload.php";
require_once "../src/LonghornClient/RestClient.php";
require_once "../src/LonghornClient/Manager.php";
require_once "../src/LonghornClient/Project.php";

use LonghornClient\Manager;
use LonghornClient\Project;

$mgr = new Manager;
$p = new Project($mgr, false);


$cur = dirname(__FILE__);
//$docx = $cur."/test.docx";
$bconf =$cur."/test.bconf";

//$p->inputBatchConfig($bconf);
print_r($p->inputBatchConfig($bconf)->getStatusCode());

// print_r($p->inputFile("test.docx", $docx));
// print_r($p->executeTasks());
// print_r($p->listOutputFiles());

// echo $p->getProjectId() . "\n";

//$mgr->deleteAllProjects();