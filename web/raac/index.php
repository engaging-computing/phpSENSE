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
    <title> River as a Classroom Easy Submission Form </title>
    <link rel="stylesheet" type="text/css" href="main.css"/>
  </head>
  <body>
  <?php require_once '../includes/config.php' ?>
    <div id="wrapper">
      <div>
        <img src="../html/img/logo.png"/>
      </div>

      <form action="riverclassconfirm.php" method=post>
        <span class="formLabel">School Name:</span> 
        <input type="text" name="school"><br/>

        <span class="formLabel">Team:</span>
        <select name="team">
          <option value="">Choose....</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
          <option value="F">F</option>
        </select></br>

        <span class="formLabel">Test Type:</span>
        <select name="testType">
          <option value="Manual">Manual</option>
          <option value="Chemical">Chemical</option>
          <option value="PINPoint">PINPoint</option>
        </select></br>
        <span class="formLabel">Location:</span>

        <select name="sessionLoc">
          <option value="upriver">Up River</option>
          <option value="downriver">Down River</option>
          <option value="dock">Dock</option>
          <option value="canals">Canals</option>
        </select></br>

        <span class="formLabel">pH:</span> 
        <input type="text" name="pH"><br/>

        <span class="formLabel">Water Temperature(Celsius):</span> 
        <input type="text" name="temp"><br/>

        <span class="formLabel">Dissolved Oxygen(ppm):</span> 
        <input type="text" name="disox"><br/>

        <span class="formLabel">Vernier Clarity(ntu)</span>
        <input type="text" name="vernierClarity"><br/>

        <span class="formLabel">Secchi Clarity(meter)</span>
        <input type="text" name="secchiClarity"><br/>

        <span class="formLabel">Air Temperature(Celsius):</span> 
        <input type="text" name="airTemp"><br/>

        <span class="formLabel">Copper(ppm):</span> 
        <input type="text" name="copper"><br/>

        <span class="formLabel">Phosphorus(ppm)</span> 
        <input type="text" name="phosphorus"><br/>
        
        <input type="submit" name="submit" class="submitButton" value="Submit Data">
      </form>
    </div>
  </body>
</html>
