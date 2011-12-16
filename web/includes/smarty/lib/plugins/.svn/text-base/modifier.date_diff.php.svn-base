<?php

function smarty_modifier_date_diff($string) {
	
	$day_1 = time();
	$day_2 = strtotime($string);
	
	$diff = $day_1 - $day_2;

    /*
	$sec   = $diff % 60;
	$diff  = intval($diff / 60);
	$min   = $diff % 60;
	$diff  = intval($diff / 60);
	$hours = $diff % 24;
	$days  = intval($diff / 24);
	*/
	
	if($days=intval((floor($diff/86400)))) $diff = $diff % 86400;
	
	if($hours=intval((floor($diff/3600)))) $diff = $diff % 3600;
	
	if($minutes=intval((floor($diff/60)))) $diff = $diff % 60;
	
	$diff    =    intval( $diff );
	
	$date_diff_string = "";
	if($days != 0) {
		$date_diff_string .= $days . " days ";
	}
	
	if($hours != 0) {
		$date_diff_string .= $hours . " hours ";
	}
	
	if($min != 0) {
		$date_diff_string .= $min . " minutes ";
	}
	
	$date_diff_string .= "ago";

	return $date_diff_string;
	
    return strtoupper($string);
}

?>