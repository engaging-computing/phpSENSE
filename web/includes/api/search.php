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
function packageBrowseVisualizationsResults($results, $page = 1, $limit = 10) {

    if($page != -1) {
        $output = array();
        $offset = ($page - 1) * $limit;
        $results =  array_splice($results, $offset, $limit);

        foreach($results as $result) {
            $output[$result['vis_id']] = array("meta" => $result, "tags" => array(), "relevancy" => 0);
        }

        return $output;
    }
    else {
        return count($results);
    }
}

function browseVisualizationsByTimeCreated($page = 1, $limit = 10) {
    global $db;

    //$sql = "SELECT visualizations.*, users.firstname, users.lastname FROM visualizations, users WHERE visualizations.owner_id = users.user_id AND visualizations.is_activity = 0 ORDER BY visualizations.timecreated DESC";
    $sql = "SELECT savedVises.*, users.firstname FROM savedVises, users WHERE savedVises.owner_id = users.user_id ORDER BY savedVises.timecreated DESC";    
    $results = $db->query($sql);

    if($db->numOfRows) {
        return packageBrowseVisualizationsResults($results, $page, $limit);
    }

    return false;
}

//Search Visualizations
function getVisualizations($terms, $page = 1, $limit = 10, $sort = "relevancy") {
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

    $total = count($results);

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


    $offset = ($page - 1) * $limit;
    return array('count'=>$total,'data'=>array_splice($experiments, $offset, $limit));

}

//Search People
function getPeople($page, $limit, $query = null){
    global $db;

    //Build up the query.
    $sql = "Select users.firstname, users.user_id, users.picture FROM users";

    if($query !=null) {
        $sql .= " WHERE CONCAT(users.firstname, ' ', users.lastname) LIKE '%{terms}%'
        OR users.firstname = '{$query}' OR users.lastname = '{$query}'
        OR CONCAT(users.firstname, ' ', users.lastname) = '{$query}'";
    }

    $sql .= " ORDER BY users.lastname ASC, users.firstname ASC";

    $results = $db->query($sql);

    //The total number of results
    $total = count($results);

    //Paginate the results
    $offset = ($page - 1) * $limit;
    $results = array_splice($results, $offset, $limit);

    //only get extra meta data for current page of results
    for($i = 0; $i < count($results); $i++) {
        $results[$i]['session_count'] = countNumberOfContributedSessions($results[$i]['user_id']);
        $results[$i]['experiment_count'] = countNumberOfContributedExperiments($results[$i]['user_id']);
    }

    //Return the total number of results and the data from the specified page.
    return array('count'=>$total,'data'=>$results);

}


//Search Experiments
function getExperiments($page=1, $limit=10, $hidden=0,$featured="off",$recommended="off", $tags= null, $sort = "recent"){
    global $db;

    $result = array();

    $sql = "SELECT DISTINCT experiments.*,
            (experiments.rating/experiments.rating_votes) as rating_comp,
            users.firstname
            FROM experiments
            LEFT JOIN users on experiments.owner_id = users.user_id";


    if($tags){
        $sql .= ", tagExperimentMap, tagIndex";
    }

    $sql .= " WHERE experiments.hidden = {$hidden} ";

    if($featured == 'on'){
        $sql .= " AND experiments.featured=1 ";
    }

    if($recommended == 'on'){
        $sql .= " AND experiments.recommended=1";
    }

    if($tags){

        $sql .= " AND ( ";

        //If there are tags we want to search by each of them.
        $tags = explode(" ", $tags);

        for($i=0;$i<count($tags);$i++){

            $sql .= " (tagIndex.value like '%{$tags[$i]}%'
            AND tagIndex.tag_id = tagExperimentMap.tag_id
            AND experiments.experiment_id = tagExperimentMap.experiment_id
            AND tagIndex.weight=1)";

            if($i < count($tags) -1){
                $sql .= " OR ";
            }
        }

        $sql .= " )";
    }

    if($sort == "rating"){
        $sql .= " ORDER BY experiments.rating/experiments.rating_votes DESC";
    } else if ($sort == "recent"){
        $sql .= " ORDER BY experiments.timecreated DESC";
    }

    $result = $db->query($sql);

    $total = count($result);

    $keys = array();
    $tmp = array();

    for($i = 0; $i < count($result); $i++) {
        $tmpKey = $result[$i]['experiment_id'];
        if (in_array($tmpKey,$keys)){
        } else {
            $keys[count($keys)] = $tmpKey;
            $tmp[count($tmp)] = $result[$i];
        }
    }

    $packaged =  packageBrowseExperimentResults($tmp, $page,$limit,false);

    if($sort == "popularity"){
        usort($packaged,'exp_popularitySort');
    } else if ($sort == "activity"){
        usort($packaged,'exp_activitySort');
    }

    return array('count'=>$total,'data'=>pagifyBrowseExperimentResults($packaged, $page,$limit,false));
}

?>
