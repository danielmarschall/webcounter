<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2017 Daniel Marschall
 * Revision: 2017-05-05
 */

if (!file_exists(__DIR__ . '/config/config.inc.php')) {
	die('Please edit config/config_dist.inc.php and then rename it to config/config.inc.php !');
}

require_once __DIR__ . '/config/config.inc.php';

$pdo = new PDO(PDO_HOST, PDO_USER, PDO_PASS);
$statement = $pdo->prepare("SELECT COUNT(*) AS cnt, SUM(counter) AS total FROM counter_visitors");
$statement->execute();
$stats = $statement->fetch();

?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

	<meta name="author" content="Daniel Marschall">
	<meta name="keywords" content="Freeware, webcounter, visitors, counter, homepage, tools, HTML, PHP, Website, Internet">
	<meta name="description" content="Free webcounter for your homepage!">

	<title>Web counter by Daniel Marschall</title>

	<style type="text/css">
	body {
		font-family: Verdana;
		background-color: #D6EAF8;
	}

	.tooltip {
		border-bottom: 1px dotted #000;
		text-decoration: none;
		cursor: help;
	}
	</style>
</head>

<body>

<h1>Web counter by Daniel Marschall</h1>

<h2>Features</h2>

<ul>
	<li>No registration needed! Just use any value for "id" that is free. A counter will be automatically created.</li>
	<li>Reload lock for <?php echo RELOADSPERRE_MINS; ?> minutes (Refresh button will not cause the counter to increase). The IP of the visitor is hashed to ensure privacy.</li>
	<li>Supports SSL, so there won't be any warnings when you include this counter to your HTTPS website.</li>
	<li>You can use several themes and modes simultaneously.</li>
	<li>Private and secure: No forced back-links, no cookies, no JavaScript!</li>
	<li>Note: Counters which are not visited for more than 1 year automatically get removed from the database.</li>
</ul>

<p>Currently hosted web counters: <b><?php echo $stats['cnt']; ?></b><br>
Visitors total: <b><?php echo $stats['total']; ?></b></p>

<h2>Available counters and modes</h2>

<?php

$dirs = array_filter(glob('themes/*'), 'is_dir');
$themes = array();
foreach ($dirs as $d) $themes[] = explode('/', $d, 2)[1];
foreach ($themes as $theme) {
	echo '<h3>Theme "'.$theme.'"</h3>';
	echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>graphic</b>&amp;theme=<b>'.$theme.'</b><br><br>';
	echo 'HTML example:<br><textarea cols="110" rows="4">&lt;a href="'.OWN_URL.'"&gt;&lt;img src="'.OWN_URL.'counter.php?id=demo&amp;amp;format=graphic&amp;amp;theme='.$theme.'" alt="Visitors" title="Visitors"&gt;&lt;/a&gt;</textarea><br>';
	echo '<br><a href="'.OWN_URL.'"><img src="'.OWN_URL.'counter.php?id=demo&amp;format=graphic&amp;theme='.$theme.'" alt="Visitors" title="Visitors"></a>';
}

echo '<h3>Spacer (1x1 invisible image)</h3>';
echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>spacer</b><br><br>';
echo 'HTML example:<br><textarea cols="110" rows="2">&lt;img src="'.OWN_URL.'counter.php?id=demo&amp;amp;format=spacer" alt=""&gt;</textarea><br>';

echo '<h3>Silent (no output)</h3>';
echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>silent</b><br>';
echo '<a href="'.OWN_URL.'counter.php?id=demo&amp;format=silent">Demo</a>';

echo '<h3>Text</h3>';
echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>plaintext</b><br>';
echo '<a href="'.OWN_URL.'counter.php?id=demo&amp;format=plaintext">Demo</a>';

echo '<h3>JSON</h3>';
echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>json</b><br>';
echo '<a href="'.OWN_URL.'counter.php?id=demo&amp;format=json">Demo</a>';

?>

<h2>Different hues</h2>

<p>You can change the hue of every counter by adding the argument "&amp;hue=" followed by a number between <b>1</b> and <b>359</b> . Just try out a few numbers until you see the color you desire.</p>

<p><u>Example:</u></p>

<?php
$theme = 'digital';
$hue = 145;
echo 'URL: '.OWN_URL.'counter.php?id=<b><span class="tooltip" title="Choose any ID you like. No registration needed!">demo</span></b>&amp;format=<b>graphic</b>&amp;theme=<b>'.$theme.'</b>&amp;hue=<b>'.$hue.'</b><br><br>';
echo 'HTML example:<br><textarea cols="110" rows="4">&lt;a href="'.OWN_URL.'"&gt;&lt;img src="'.OWN_URL.'counter.php?id=demo&amp;amp;format=graphic&amp;amp;theme='.$theme.'&amp;amp;hue='.$hue.'" alt="Visitors" title="Visitors"&gt;&lt;/a&gt;</textarea><br>';
echo '<br><a href="'.OWN_URL.'"><img src="'.OWN_URL.'counter.php?id=demo&amp;format=graphic&amp;theme='.$theme.'&amp;hue='.$hue.'" alt="Visitors" title="Visitors"></a>';
?>

<hr>

<p>
	<a href="https://validator.w3.org/check?uri=referer"><img src="https://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88"></a>
	<a href="https://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="https://jigsaw.w3.org/css-validator/images/vcss" alt="CSS ist valide!"></a>
</p>

</body>

</html>
