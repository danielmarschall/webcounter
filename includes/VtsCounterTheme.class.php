<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2022 Daniel Marschall
 * Revision: 2022-01-09
 */

abstract class VtsCounterTheme {
	public $stellenMin;

	abstract protected function getImg($visitors, $hue=0);

	function outputCounterImage($visitors, $format='png', $hue=0) {
		$visitors = sprintf("% ".$this->stellenMin."d", $visitors);

		$im = $this->getImg($visitors, $hue);

		$format = strtolower($format);
		if ($format == 'png') {
			header('Content-Type: image/png');
			imagepng($im);
		} else if (($format == 'jpg') || ($format == 'jpeg')) {
			header('Content-Type: image/jpeg');
			imagejpeg($im);
		} else if ($format == 'gif') {
			header('Content-Type: image/gif');
			imagegif($im);
		} else if ($format == 'wbmp') {
			header('Content-Type: image/vnd.wap.wbmp');
			imagewbmp($im);
		} else {
			assert(false);
		}

		@imagedestroy($im);
	}

}
