<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2017 Daniel Marschall
 * Revision: 2017-05-05
 */

class VtsCounterThemeImpl extends VtsCounterTheme {

	protected function getImg($visitors, $hue=0) {
		$im = ImageCreate(strlen($visitors)*15 + 2 - 1, 17 + 4);
		$black = imagecolorallocate($im,0,0,0);
		imagefill($im,0,0,$black);

		$offset_x = 2;
		$offset_y = 2;

		for ($i=0; $i<strlen($visitors); $i++) {
			if ($visitors[$i] == ' ') $visitors[$i] = 'x';
			$digit_im = ImageCreateFromPNG(__DIR__.'/digit_'.$visitors[$i].'.png');
			$digit_w = imagesx($digit_im);
			$digit_h = imagesy($digit_im);

			imagehue($digit_im, $hue);

			ImageCopy($im, $digit_im, $offset_x, $offset_y, 0, 0, $digit_w, $digit_h);
			ImageDestroy($digit_im);

			$offset_x += $digit_w + 3;
		}

		return $im;
	}

}

$themeObj = new VtsCounterThemeImpl();
$themeObj->stellenMin = 8;
