<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2017 Daniel Marschall
 * Revision: 2017-05-05
 */

class VtsCounterThemeImpl extends VtsCounterTheme {

	protected function getImg($visitors, $hue=0) {
		$im = ImageCreateFromPNG(__DIR__.'/bg.png');
		imagesavealpha($im, true);

		$offset_x = 60;
		$offset_y = 16;

		for ($i=0; $i<strlen($visitors); $i++) {
			if ($visitors[$i] == ' ') $visitors[$i] = 'x'; // don't support "blank" digits
			$digit_im = ImageCreateFromPNG(__DIR__.'/digit_'.$visitors[$i].'.png');
			$digit_w = imagesx($digit_im);
			$digit_h = imagesy($digit_im);

			imagehue($digit_im, $hue);

			ImageCopy($im, $digit_im, $offset_x, $offset_y, 0, 0, $digit_w, $digit_h);
			ImageDestroy($digit_im);

			$offset_x += $digit_w + 4;
		}

		return $im;
	}

}

$themeObj = new VtsCounterThemeImpl();
$themeObj->stellenMin = 6;
