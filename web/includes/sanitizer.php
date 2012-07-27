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

 /**
  * Runs HTML-only sanitization on the given file(path).
  * The file is modified then saved back to disk.
  */
function sanitizeFile($filename) {

    $contents = file_get_contents($filename);

    //Files only need to be protected from js injection (not sql)
    $contents = htmlentities($contents, ENT_NOQUOTES);

    //Save sanitized data
    $file = fopen($filename, "w");
    fwrite($file, $contents);
    fclose($file);
}

/**
  * Preforms both HTML and SQL sanitization on the given string.
  * Also sanitizes escapes to avoid unescaping escaped SQL input.
  */
function sanitizeString($string) {

    $string = str_replace("\\", "", $string);
    $string = mysql_real_escape_string($string);
    $string = htmlentities($string, ENT_NOQUOTES);
    
    return $string;
}

/**
  * Runs sanitizeString on all contents of an array-like object recursively
  */
function sanitizeGeneric($obj) {

    if (is_array($obj)) {
        foreach ($obj as $key=>$val) {
            $obj[$key] = sanitizeGeneric($val);
        }
    }
    else {
        return sanitizeString($obj);
    }

    return $obj;
}

/**
  * Sanitize all standard input vectors.
  */
$_POST    = sanitizeGeneric($_POST);
$_GET     = sanitizeGeneric($_GET);
$_REQUEST = sanitizeGeneric($_REQUEST);
$_COOKIE  = sanitizeGeneric($_COOKIE);

?>