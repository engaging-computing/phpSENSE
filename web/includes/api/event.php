<?php

function createEvent($token, $title, $description, $location, $start, $end) {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
	
	$db->query("INSERT INTO events (`author_id`, `title`, `description`, `location`, `start`, `end`) VALUES({$uid}, '{$title}', '{$description}', '{$location}', '$start', '$end')");
	
	if($db->numOfRows)  {
		$id = $db->lastInsertId();
		
		$url = 'http://isense.cs.uml.edu/events.php?id=' . $id;
		publishToTwitter('Latest Event: "'.$title.'" - ' . $url . ' #isenseevents');
		
		$title = 'iSENSE Event - ' . $title;
		publishToDelicious($title, $url, $description, array('isenseevents', 'isense', 'education'));
		
		return $id;
	}
	
	return false;
}

function getEvent($id) {
	global $db;
	
	$output = $db->query("SELECT events.*, users.* FROM events, users WHERE events.event_id = {$id} AND users.user_id = events.author_id");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function deleteEvent($eid){
	global $db;
	
	$output = $db->query("DELETE FROM events WHERE events.event_id = {$eid}");
	
	if($db->numOfRows){
		return true;
	}
	
	return false;
}

/*
function getEvents($limit = 5) {
	global $db;
	
	$output = $db->query("SELECT events.*, users.* FROM events, users WHERE users.user_id = events.author_id ORDER BY events.start DESC LIMIT 0, {$limit}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}
*/

function getEvents($limit = 5) {
    $url = "http://".$_SERVER['SERVER_NAME']."/blog/feed?post_type=isense_event";
    $output = array();
    $count = 0;
    
    $contents = file_get_contents($url);
    if($contents != FALSE) {
        $xml = simplexml_load_string($contents);
        
        foreach($xml->channel->item as $i) {
            $pubDate = (string) $i->pubDate;
            $pubDate = strtotime($pubDate);

            if($count < $limit) {
                $output[] = array(
                  "title" => (string) $i->title,
                  "link" => (string) $i->link,
                  "date" => $pubDate
                );
            }
            else {
                break;
            }
        }
    }
    
    return $output;
}

?>