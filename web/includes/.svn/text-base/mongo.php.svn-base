<?php


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
