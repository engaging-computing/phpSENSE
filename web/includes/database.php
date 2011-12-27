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

class Database {
	var $connection;
	var $ready;
	var $error;
	var $last;
	
	var $numOfRows;
	
	function Database($user, $pass, $server, $name) {
		$this->ready = false;
		$this->last = "";
		$this->connection = mysql_connect($server, $user, $pass, true);
		
		if($this->connection) {
			if(mysql_select_db($name, $this->connection)) {
				$this->ready = true;
			}
		}
	}
	
	function lastInsertId() {
		if($this->ready) {
			return mysql_insert_id($this->connection);
		}
		else {
			return false;
		}
	}
		
	function query($query) {
		$output = array();
		
		if($this->ready) {
			$this->last = $query;
			$result = mysql_query($query, $this->connection);
			
			$error = mysql_error();
			if($error != "") {
				echo $error;
				echo $query;
				$this->error = $error;
				$this->numOfRows = -1;
			}
			else {
				if(strpos($query, "INSERT") === FALSE && strpos($query, "UPDATE") === FALSE && strpos($query, "DELETE") === FALSE) {
					$output = array();
					
					$this->numOfRows = mysql_num_rows($result);
					while($row = mysql_fetch_assoc($result)) {
						$output[] = $row;
					}

					mysql_free_result($result);
					
					return $output;
				}
				else {
					$this->numOfRows = mysql_affected_rows($this->connection);
					return true;
				}				
			}
		}
		
		return false;
	}
	
	function disconnect() {
		if($this->ready) {
			mysql_close($this->connection);
		}
		
		return false;
	}
}

$db = new Database(DB_USER, DB_PASS, DB_HOST, DB_NAME);

?>
