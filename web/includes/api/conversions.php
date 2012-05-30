<?php

    function getPossibleConversions($unit_id){
        global $db;

	$sql = "SELECT conversions.name,
		  conversions.from_unit_id,
		  conversions.to_unit_id,
		  conversions.javascript_code,
		  units.abbreviation 
		FROM conversions,units 
		WHERE conversions.from_unit_id={$unit_id} 
		AND units.unit_id=conversions.to_unit_id";
        $query = $db->query($sql);

        return $query;
    }

?>
