<?php

use DynamicsWebApi\Client;
use DynamicsWebApi\Helper;

require_once 'vendor/autoload.php';

$helper = new Helper();
$helper->publishAllChanges();
$name = $argv[1];
$client = Client::createInstance();
if (isset($argv[3]) && !empty($argv[3])) {
	$client->request('/CloneAsSolution', 'POST', [
		'ParentSolutionUniqueName' => $name,
		'DisplayName' => $name,
		'VersionNumber' => $argv[2],
	]);
}
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
$filePath = $argv[2] . DIRECTORY_SEPARATOR . 'exported-solution.zip';
file_put_contents($argv[2] . DIRECTORY_SEPARATOR . 'exported-solution.zip', $exportedSolutionFile);
echo 'echo "exported_file_path=' . $filePath . '" >> $GITHUB_OUTPUT';
echo 'echo "exported_file_base64=' . $exportedSolution['ExportSolutionFile'] . '" >> $GITHUB_OUTPUT';