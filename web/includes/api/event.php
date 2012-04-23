<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

function createEvent($token, $title, $description, $location, $start, $end) {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];

	$db->query("INSERT INTO events 
                (`author_id`, `title`, `description`, `location`, `start`, `end`) 
                VALUES({$uid}, '{$title}', '{$description}', '{$location}', '$start', '$end')");
	
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
