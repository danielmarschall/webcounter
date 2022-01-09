<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2017 Daniel Marschall
 * Revision: 2017-05-05
 */

class VtsCounterThemeImpl extends VtsCounterTheme {

	protected function getImg($visitors, $hue=0) {
		$offset_x = 0;
		$offset_y = 0;

		$digit_middle_pad = 2;

		$digits = array();

		$img_size_w = $offset_x;
		$img_size_h = 0;

		for ($i=0; $i<strlen($visitors); $i++) {
			if ($visitors[$i] == ' ') $visitors[$i] = 'x'; // don't support "blank" digits
			$digit_im = ImageCreateFromPNG(__DIR__.'/digit_'.$visitors[$i].'.png');
			$digit_w = imagesx($digit_im);
			$digit_h = imagesy($digit_im);

			$digits[] = array($digit_im, $digit_w, $digit_h);

			$img_size_w += $digit_w + ($i == 0 ? 0 : $digit_middle_pad);
			$img_size_h = max($img_size_h, $digit_h+$offset_y);
		}

		$im = ImageCreate($img_size_w, $img_size_h);
		imagesavealpha($im, true);

		$pos_x = $offset_x;
		for ($i=0; $i<strlen($visitors); $i++) {
			$digit_im = $digits[$i][0];
			$digit_w = $digits[$i][1];
			$digit_h = $digits[$i][2];

			imagehue($digit_im, $hue);

			$pos_y = $offset_y + ($img_size_h - $digit_h); // auf Boden platzieren
			ImageCopy($im, $digit_im, $pos_x, $pos_y, 0, 0, $digit_w, $digit_h);
			$pos_x += $digit_w + $digit_middle_pad;

			ImageDestroy($digit_im);
		}

		return $im;
	}

}

$themeObj = new VtsCounterThemeImpl();
$themeObj->stellenMin = 1;
