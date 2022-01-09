<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2017 Daniel Marschall
 * Revision: 2017-05-14
 */

error_reporting(E_ALL | E_NOTICE);
assert_options(ASSERT_ACTIVE, true);
assert_options(ASSERT_BAIL, true);

if (!file_exists(__DIR__ . '/config/config.inc.php')) {
	die('Please edit config/config_dist.inc.php and then rename it to config/config.inc.php !');
}

require_once __DIR__ . '/config/config.inc.php';
require_once __DIR__ . '/includes/functions.inc.php';
require_once __DIR__ . '/includes/VtsCounterTheme.class.php';
require_once __DIR__ . '/includes/VtsCounter.class.php';
require_once __DIR__ . '/includes/VtsCounterInfo.class.php';

if ((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) {
	die('Argument "id" is missing');
}

if ($_REQUEST['id'] == 'demo') {
	$visitors = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : 123456;
	$created = '2017-05-04 00:00:00';
} else {
	$pdo = new PDO(PDO_HOST, PDO_USER, PDO_PASS);
	$c = new VtsCounter($pdo);
	$c->clearReloadSperre(RELOADSPERRE_MINS);
	$counter_id = $c->getIDfromIDStr($_REQUEST['id']);
	$c->visitCount($counter_id, fetchip());
	$info = $c->getCounterInfo($counter_id);
	$visitors = $info->visitors;
	$created = $info->created;
}
$querytime = gmdate('Y-m-d\TH:i:s\Z'); // ISO 8601
$created = date('Y-m-d\TH:i:s\Z', strtotime($created));

/*
// No caching
header("HTTP/1.1 200 OK");
header("Expires: Thu, 24 Dec 1987 08:30:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

$etag = md5($visitors . $_SERVER['QUERY_STRING']);
header("ETag: $etag");
if (anyTagMatched($etag)) {
	header("HTTP/1.1 304 Not Modified");
	die();
} else {
	header("HTTP/1.1 200 OK");
}


$format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'graphic';

if ($format == 'graphic') {
	$theme = isset($_REQUEST['theme']) ? preg_replace('/[^a-z0-9_\-]/', '', $_REQUEST['theme']) : null;

	if (is_null($theme)) die('Parameter "theme" is missing');

	$themeFile = __DIR__ . "/themes/$theme/theme.inc.php";

	if (!file_exists($themeFile)) die("Theme '$theme' does not exist.");

	$themeObj = null;
	include $themeFile;
	assert(!is_null($themeObj));

	$hue = isset($_REQUEST['hue']) ? $_REQUEST['hue'] : 0;
	$themeObj->outputCounterImage($visitors, 'png', $hue);
} else if ($format == 'silent') {
	// nothing
} else if ($format == 'spacer') {
	header('Content-Type: image/png');
	readfile(__DIR__ . '/spacer.png');
} else if ($format == 'plaintext') {
	header('Content-Type: text/plain');
	echo $visitors;
} else if ($format == 'json') {
	header('Content-Type: application/json');
	$out = array();
	$out['created'] = $created;
	$out['querytime'] = $querytime;
	$out['visitors'] = $visitors;
	echo json_encode($out);
} else {
	die('Argument "format" must be either graphic, silent, spacer, plaintext or json');
}
