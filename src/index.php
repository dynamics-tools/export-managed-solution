<?php

use DynamicsWebApi\Client;
use DynamicsWebApi\Helper;

require_once 'vendor/autoload.php';

$helper = new Helper();
$helper->publishAllChanges();
$name = $argv[1];
$client = Client::createInstance();
$client->request('/CloneAsSolution', 'POST', [
	'ParentSolutionUniqueName' => $name,
	'DisplayName' => $name,
	'VersionNumber' => $argv[2],
]);
$response = $client->request('/ExportSolution', 'POST', [
	'SolutionName' => $name,
	'ExportAutoNumberingSettings' => false,
	'ExportCalendarSettings' => false,
	'ExportCustomizationSettings' => false,
	'ExportEmailTrackingSettings' => false,
	'ExportGeneralSettings' => false,
	'ExportIsvConfig' => false,
	'ExportMarketingSettings' => false,
	'ExportOutlookSynchronizationSettings' => false,
	'ExportRelationshipRoles' => false,
	'ExportSales' => false,
	'Managed' => true,
]);
$exportedSolution = json_decode($response->getBody(), true);
$exportedSolutionFile = base64_decode($exportedSolution['ExportSolutionFile']);
$filePath = $argv[3] . '/exported-solution.zip';
file_put_contents($argv[3] . '/exported-solution.zip', $exportedSolutionFile);
echo "::set-output name=exported_file::$filePath";