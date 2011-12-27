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


class MDB {
	
	var $c;
	var $db;
	var $col;
	
	function MDB($database, $collection = "data", $hostname = "localhost", $port = 27017, $username = null, $password = null) {
		
		$auth = "";
		if($username != null && $password != null) {
			$auth = "{$username}:{$password}@";
		}
		
		$con_string = "mongodb://{$auth}{$hostname}:{$port}";
		
		try {
		$this->c = new Mongo($con_string);
		$this->db = $this->c->selectDB($database);
		$this->col = $this->db->selectCollection($collection);
    } catch (MongoConnectionException $e) {
        $err = "Mongo was not running. Please refresh the page";
	$exec = shell_exec("rm /opt/data/db/mongod.lock");
        $exec = shell_exec("nohup mongod --dbpath /opt/data/db --fork --logpath /var/log/mongo > /dev/null &");

    }
	}
	
    function dropUpdate( $data, $keys, $exp, $ses) {
        
        $this->selectCollection( 'e' . $exp );
                
        $this->col->remove( array( 'session' => floatval($ses) ));
                
        foreach($data as $dat) {
            foreach( $dat as $k => $d ){
                    
                    if( $k == 0 )
                        echo '_id \'en it';
                    else if( $k == 1 )
                        $doc[$keys[$k]] = new MongoInt64( $d );
                    else
                        $doc[$keys[$k]] = $d;
                }
            $this->col->save($doc);
            unset($doc);
        }
        

        
    }
	
	function selectCollection($col) {
		$this->col = $this->db->selectCollection($col);
	}
	
	function insert($collection, $data) {
		$this->selectCollection($collection);
		return $this->col->insert($data);
	}
	
	function find($collection, $conds = array(), $cols = array()) {
		$this->selectCollection($collection);
		
		$results = $this->col->find($conds, $cols);
		
		$output = array();
		foreach($results as $result) {
			$output[] = $result;
		}
		
		return $output;
	}
	
}

$mdb = new MDB(MDB_DATABASE);

ini_set('mongo.native_long', 1);
?>
