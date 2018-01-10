<?php
/*
 * NB! Example use of methods in the wrapper.
 * Not meant for any other useful use (dummy docx, zip and bconf files included for the purpose of testing)
 *
 */


require_once "../vendor/autoload.php";

/* These constants can be used for source and target language defaults */
define('LONGHORNSRCLANG','et-EE');
define('LONGHORNTRGLANG','nb-NO');

/* Instantiate manager */
$mgr = new \Estnorlink\LonghornClient\Manager();
/* Instantiate Project -- if persistent flag is true, it will not be deleted when the script terminates */
$p = new \Estnorlink\LonghornClient\Project($mgr, false);

/* List projects to demonstrate it exists */
$projects = $mgr->listProjectsArray();

/* The source and target methods used after project creation will override any defaults */
$p->setTrglang('sv-FI');

/* Upload the batch config file */
$bconf =dirname(__FILE__)."/example.bconf";
$p->inputBatchConfig($bconf);

/* Upload an input file */
$docx = dirname(__FILE__)."/example-inputfile.docx";
//$p->inputFile($docx);

if ($p->inputZip(dirname(__FILE__)."/example-inputzip.zip")->getStatusCode() == 200) {
    echo "Added zip!\n";
} else {
    echo "Error adding zip!\n";
}

/* List all input files array */
if ($p->listInputFiles()->getStatusCode() == 200)
{
    $infiles = $p->listInputFilesArray();
}  else $infiles = false;

$infiles = $p->listInputFilesArray();

/* Execute the project */
$p->executeTasks();

/* List output files as array */
if ($p->listOutputFiles()->getStatusCode() == 200)
{
    $outfiles = $p->listOutputFilesArray();
}  else $outfiles = false;

/* Delete all projects */
// $mgr->deleteAllProjects();

echo "Projects:\n".implode(" ", $projects)."\n\n";
echo "Current project: ".$p->getProjectId()."\n\n";
if ($infiles) echo "Input files:\n".implode(" ", $infiles)."\n\n";
if ($outfiles) echo "Output files:\n".implode(" ", $outfiles)."\n\n";
