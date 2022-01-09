<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2019 Daniel Marschall
 * Revision: 2019-02-18
 */

function pdox_execute($statement, $args=array()) {
	if (!$statement->execute($args)) {
		echo "SQL Error <br />";
		echo $statement->queryString."<br />";
		echo $statement->errorInfo()[2];
		die();
	}
}

function fetchip() {
	return md5($_SERVER['REMOTE_ADDR']); // masked IP wegen DSGVO Kacke

	// Das ist alles Quatsch! Dann kann man die IP ja fälschen
	/*
	// Source: http://lists.phpbar.de/pipermail/php/Week-of-Mon-20040322/007749.html

	$client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : '';
	$x_forwarded_for = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
	$remote_addr = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';

	if (!empty($client_ip)) {
		$ip_expl = explode('.', $client_ip);
		$referer = explode('.', $remote_addr);
		if($referer[0] != $ip_expl[0]) {
			$ip = array_reverse($ip_expl);
			$return = implode('.', $ip);
		} else {
			$return = $client_ip;
		}
	} else if (!empty($x_forwarded_for)) {
		if (strstr($x_forwarded_for, ',')) {
			$ip_expl = explode(',', $x_forwarded_for);
			$return = end($ip_expl);
		}
	} else {
		$return = $remote_addr;
	}
	unset ($client_ip, $x_forwarded_for, $remote_addr, $ip_expl);
	return $return;
	*/
}


function rgb2hsl($r, $g, $b) {
   $var_R = ($r / 255);
   $var_G = ($g / 255);
   $var_B = ($b / 255);

   $var_Min = min($var_R, $var_G, $var_B);
   $var_Max = max($var_R, $var_G, $var_B);
   $del_Max = $var_Max - $var_Min;

   $v = $var_Max;

   if ($del_Max == 0) {
      $h = 0;
      $s = 0;
   } else {
      $s = $del_Max / $var_Max;

      $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
      $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
      $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

      if      ($var_R == $var_Max) $h = $del_B - $del_G;
      else if ($var_G == $var_Max) $h = ( 1 / 3 ) + $del_R - $del_B;
      else if ($var_B == $var_Max) $h = ( 2 / 3 ) + $del_G - $del_R;

      if ($h < 0) $h++;
      if ($h > 1) $h--;
   }

   return array($h, $s, $v);
}

function hsl2rgb($h, $s, $v) {
    if($s == 0) {
        $r = $g = $B = $v * 255;
    } else {
        $var_H = $h * 6;
        $var_i = floor( $var_H );
        $var_1 = $v * ( 1 - $s );
        $var_2 = $v * ( 1 - $s * ( $var_H - $var_i ) );
        $var_3 = $v * ( 1 - $s * (1 - ( $var_H - $var_i ) ) );

        if       ($var_i == 0) { $var_R = $v     ; $var_G = $var_3  ; $var_B = $var_1 ; }
        else if  ($var_i == 1) { $var_R = $var_2 ; $var_G = $v      ; $var_B = $var_1 ; }
        else if  ($var_i == 2) { $var_R = $var_1 ; $var_G = $v      ; $var_B = $var_3 ; }
        else if  ($var_i == 3) { $var_R = $var_1 ; $var_G = $var_2  ; $var_B = $v     ; }
        else if  ($var_i == 4) { $var_R = $var_3 ; $var_G = $var_1  ; $var_B = $v     ; }
        else                   { $var_R = $v     ; $var_G = $var_1  ; $var_B = $var_2 ; }

        $r = $var_R * 255;
        $g = $var_G * 255;
        $B = $var_B * 255;
    }    
    return array($r, $g, $B);
}

function imagehue(&$image, $angle) {
if (!is_numeric($angle)) return;
    if($angle % 360 == 0) return;
    $width = imagesx($image);
    $height = imagesy($image);

$imout = imagecreate($width, $height);

    for($x = 0; $x < $width; $x++) {
        for($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;            
            $alpha = ($rgb & 0x7F000000) >> 24;
            list($h, $s, $l) = rgb2hsl($r, $g, $b);
            $h += $angle / 360;
            if($h > 1) $h--;
            list($r, $g, $b) = hsl2rgb($h, $s, $l);
            imagesetpixel($imout, $x, $y, imagecolorallocatealpha($imout, $r, $g, $b, $alpha));
        }
    }

	imagedestroy($image);
	$image = $imout;
}

/**
 * TRUE if any tag matched
 * FALSE if none matched
 * NULL if header is not specified
 * http://stackoverflow.com/questions/2086712/http-if-none-match-and-if-modified-since-and-304-clarification-in-php
 */
function anyTagMatched($myTag) {
    $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
        stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
        false ;

    if( false !== $if_none_match ) {
        $tags = explode( ", ", $if_none_match ) ;
        foreach( $tags as $tag ) {
            if( $tag == $myTag ) return true ;
        }
        return false ;
    }
    return null ;
}

