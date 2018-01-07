<?php
require_once "../vendor/autoload.php";
require_once "../src/LonghornClient/Manager.php";
require_once "../src/LonghornClient/Project.php";

$mgr = new LongHornClient\Manager;
$p = new LongHornClient\Project($mgr);

echo $p->getProjectId();