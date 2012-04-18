<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
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
 -->
<html>
  <head>
    <title> River Monitoring System Text Message Submit Form </title>

<!---
    <link rel="stylesheet" type="text/css" href="main.css"/>
--->

  </head>
  <body>
    <?php
	require_once '../includes/config.php';
//	if ($_REQUEST['From'] != "9783943795")
//	{
//		die("<br>Not from the right phone number!");
//	}

//	$myFile = "/home/arcadianfx/testFile2.txt";
//	$fh = fopen($myFile, 'a') or die("can't open file");

//	fwrite($fh, "" . date(DATE_RFC822) . "\n\n");

        echo "DEBUG info:\n<br>";

	while ($value = current($_REQUEST))
	{
        	echo "  key='" . key($_REQUEST) . "', value='" . $value . "', \n<br>";
		$stringData = "REQUEST Data: key='".key($_REQUEST)."', value='" . $value . "'\n";
//		fwrite($fh, $stringData);
		next($_REQUEST);
	}

        $pinPointData = $_REQUEST['Body'];
        $dataArray = explode(' ', $pinPointData);

        if (sizeof($dataArray) < 12)
        {
            die("<br>Data is less than 12 16-bit values!");
        }

//        fwrite($fh, "\nRAW DATA: " . $pinPointData . "\n");

        echo "\nRAW DATA: " . $pinPointData . "\n<br>";

        // Latitude
        $flat = (float) ("".hexdec($dataArray[0]).".".hexdec($dataArray[1]));
        $degs = (((int) $flat) / 100);
        $min = ($flat - ($degs * 100));
	$latitude = ($degs + $min / 60);

        $writeStr = "<br>Lat: ".$latitude;
        echo $writeStr;

        // Longitude
        $flong = (float) ("".hexdec($dataArray[2]).".".hexdec($dataArray[3]));
        $degs = (((int) $flong) / 100);
        $min = ($flong - ($degs * 100));
	$longitude = ($degs + $min / 60);

        $writeStr = "<br>Long: ".$longitude;
        echo $writeStr;

        // Altitude
        $alt = hexdec($dataArray[4]);
        $writeStr = "<br>Alt: ".$alt;
        echo $writeStr;

        // Internal Temperature
        $temperature = hexdec($dataArray[5]) / 10.0;
        $writeStr = "<br>Temp: ".$temperature." degrees C";
        echo $writeStr;

        // Internal Light reading
        $light = (pow(2, hexdec($dataArray[6])) * hexdec($dataArray[7]) * 0.025);
        $writeStr = "<br>Light: ".$light." lux";
        echo $writeStr;

	// Mini1 (external temperature -- special probe!!!)
	$exTemperature = ((423.65*hexdec($dataArray[8]))+0.005436)*3.3*(3.0/2.0)/1023;
        $writeStr = "<br>External Temperature: ".$exTemperature." degrees C";
        echo $writeStr;

	// Mini2 (unused currently)
	$mini2 = hexdec($dataArray[9]);
        $writeStr = "<br>Mini2: ".$mini2." (raw)";
        echo $writeStr;

	// BTA1 (pH)
	$pH = -0.0185*hexdec($dataArray[10])+13.769;
        $writeStr = "<br>pH: ".$pH." pH";
        echo $writeStr;

	// BTA2 (Flow rate)
	$flowRate = (hexdec($dataArray[11])*3.3*(3/2)/1023);
        $writeStr = "<br>Flow Rate: ".$flowRate." m/s";
        echo $writeStr;


        $data = array(array($_SERVER['REQUEST_TIME'],42.64037, -71.352156, $temperature, $pH, $flowRate,$exTemperature));

        $response = putData(410,3321, $data);
	
//	fclose($fh);
    ?>
  </body>
</html>


