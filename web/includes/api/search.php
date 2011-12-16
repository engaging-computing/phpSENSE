<?php

function searchExperiments($terms, $page = 1, $limit = 10, $sort = "relevancy") {
    global $session;
    
    $is_admin = ($session->type == 1);
    
	$tags = explode(" ", $terms);
	$results = array();
	
	// Build array of search results
	foreach($tags as $tag) {
		$search_results = getExperimentsByTag($tag);
		if($search_results !== false) {
			$results[$tag] = $search_results;
		}
	}
	
	$experiments = array();
	
	foreach($results as $resultk => $resultv) {
		foreach($resultv as $exp) {
			
			if(($exp['hidden'] == 1 && $is_admin == 1) || ($exp['hidden'] == 0)) {
			    $key = $exp['experiment_id'];
    			if(!array_key_exists($key, $experiments)) {
    				$sessioncount = countNumberOfSessions($key);
    				$contribcount = countNumberOfContributors($key);
    				$experiments[$key] = array('meta' => $exp, 'tags' => array($resultk), 'relevancy' => 1, 'session_count' => $sessioncount, 'contrib_count' => $contribcount);
    			}
    			else {
    				$experiments[$key]['tags'][] = $resultk;
    				$experiments[$key]['relevancy'] = count($experiments[$key]['tags']);
    			}    
			}
		}
	}
	
	if($sort == "relevancy") {
		uasort($experiments, "sort_relevancy");
	}
	else if($sort == "popularity") {
		uasort($experiments, "sort_exp_popularity");
	}
	else if($sort == "activity") {
		uasort($experiments, "sort_exp_activity");
	}
	
	if($page != -1) {
		if($limit != -1) {
		    $offset = ($page - 1) * $limit;
    		return array_splice($experiments, $offset, $limit);
		}
		else {
		    return $experiments;
		}
	}
	else {
		return count($experiments);
	}
}

function searchVisualizations($terms, $page = 1, $limit = 10, $sort = "relevancy") {
	$tags = explode(" ", $terms);
	$results = array();
	
	// Build array of search results
	foreach($tags as $tag) {
		$search_results = getVisByTag($tag);
		if($search_results !== false) {
			$results[$tag] = $search_results;
		}
	}
	
	$experiments = array();
	
	foreach($results as $resultk => $resultv) {
		foreach($resultv as $exp) {
			
			$key = $exp['vis_id'];
			if(!array_key_exists($key, $experiments)) {
				$experiments[$key] = array('meta' => $exp, 'tags' => array($resultk), 'relevancy' => 1);
			}
			else {
				$experiments[$key]['tags'][] = $resultk;
				$experiments[$key]['relevancy'] = count($experiments[$key]['tags']);
			}
		}
	}
	
	if($sort == "relevancy") {
		uasort($experiments, "sort_relevancy");
	}
	
	if($page != -1) {
		$offset = ($page - 1) * $limit;
		return array_splice($experiments, $offset, $limit);
	}
	else {
		return count($experiments);
	}
}

function searchPeople($terms, $page = 1, $limit = 10, $sort = "relevancy") {
	global $db;
	
	$sql = "SELECT users.* FROM users 
			WHERE CONCAT(users.firstname, ' ', users.lastname) LIKE '%{terms}%' 
			OR users.firstname = '{$terms}' OR users.lastname = '{$terms}' 
			OR CONCAT(users.firstname, ' ', users.lastname) = '{$terms}'";
			
	$results = $db->query($sql);
	
	if($db->numOfRows) {
		if($page != -1) {
			$offset = ($page - 1) * $limit;
			$results = array_splice($results, $offset, $limit);
			
			for($i = 0; $i < count($results); $i++) {
				$results[$i]['session_count'] = countNumberOfContributedSessions($results[$i]['user_id']);
				$results[$i]['experiment_count'] = countNumberOfContributedExperiments($results[$i]['user_id']);
			}
			
			return $results;
		}
		else {
			return count($results);
		}
	}
	
	return false;
}

function browsePeople($page = 1, $limit = 10, $sort = "") {
	global $db;
	
	$sql = "SELECT users.* FROM users ORDER BY users.lastname ASC, users.firstname ASC";
	$results = $db->query($sql);
	
	if($db->numOfRows) {
		
		if($page != -1) {
			$offset = ($page - 1) * $limit;
			$results = array_splice($results, $offset, $limit);

			for($i = 0; $i < count($results); $i++) {
				$results[$i]['session_count'] = countNumberOfContributedSessions($results[$i]['user_id']);
				$results[$i]['experiment_count'] = countNumberOfContributedExperiments($results[$i]['user_id']);
			}

			return $results;
		}
		else {
			return count($results);
		}
	}
	
	return false;
}

?>