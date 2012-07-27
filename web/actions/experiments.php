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

require_once '../includes/config.php';


if(isset($_GET['action'])) {
    switch($_GET['action']) {
        case "addfeature":
            if(isAdmin()){  
                addFeaturedExperiment($_GET['id']);
            }
            break;
            
        case "removefeature":
            if(isAdmin()){  
                removeFeaturedExperiment($_GET['id']);
            }
            break;
            
        case "rate":
            if(isUser()){
                rateExperiment($_GET['id'], $_GET['value']);
            }
            break;
            
        case "hide":
            if(isAdmin() || isExperimentOwner($_GET['id'])) {
                hideExperiment($_GET['id']);
            }
            
            break;
            
        case "unhide":     
            if(isAdmin() || isExperimentOwner($_GET['id'])) {
                unhideExperiment($_GET['id']);
            }
            
            break;
            
        case "hideSes":
            if(isAdmin() || isSessionOwner($_GET['id'])) {
                hideSession($_GET['id']);
            }
            
            break;
            
        case "unhideSes":
            if(isAdmin() || isSessionOwner($_GET['id'])) {
                unhideSession($_GET['id']);
            }
            
            break;
            
        case "closeExp":        
            if(isAdmin() || isExperimentOwner($_GET['id'])) {
                closeExperiment($_GET['id']);
            }
                      
            break;
            
        case "uncloseExp":
            if(isAdmin() || isExperimentOwner($_GET['id'])) {
                uncloseExperiment($_GET['id']);
            }
            
            break;
            
            
        case "recommend":
            if(isAdmin()) {
                recommendExperiment($_GET['id']);
            }
            
            break;
            
            //Demote an experiment from iSENSE recommended status.
        case "unrecommend":
            if(isAdmin()) {
                unrecommendExperiment($_GET['id']);
            }
            
            break;
            
        case "changeimage":
            
            $user  = $session->getUser();
            $owner = getExperimentOwner($_GET['eid']);
            $url = $_GET['purl'];
            $eid = $_GET['eid'];
            if(isAdmin() || isExperimentOwner($eid)) {
                updateExperimentImage($url,$eid);
            }
            break;
    }
}

?>
