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
<div id="main">
    <form action="tsor.php" id="upload_form" method=post>
      <fieldset id="raac-info">
        <legend> Step 1. Enter Collected Data </legend>
        <p>Please enter the collected data as instructed. Fields may be left blank if they were not measured. </p>

        <label for="school">School Name:</label> 
        <input type="text" style="width:250px;" name="school" id="school" class="required urlSafe" /><img height="10px" width="10px" id="school_validated" src="/html/img/validated.png" class="validated" style="position:relative;left:-15px;" /><img height="10px" width="10px" id="school_failed" src="/html/img/failed.png" class="failed" style="position:relative;left:-15px;" /><br/>

        <label for="team">Team:</label>
        <select name="team" id="team">
          <option value="">Choose....</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
          <option value="F">F</option>
        </select></br>

        <label for="testType">Test Type:</label>
        <select name="testType" id="testType">
          <option>Choose...</option>
          <option value="Manual">Manual</option>
          <option value="Chemical">Chemical</option>
          <option value="PINPoint">PINPoint</option>
        </select></br>
        
        <label for="sessionLoc">Location:</label>
        <select name="sessionLoc" id="sessionLoc">
          <option>Choose...</option>
          <option value="upriver">Up River</option>
          <option value="downriver">Down River</option>
          <option value="dock">Dock</option>
          <option value="canals">Canals</option>
        </select></br>
        
        <label for="pH">pH:</label> 
        <input type="text" name="pH" id="pH"><br/>

        <label for="temp">Water Temp(Celsius):</label> 
        <input type="text" name="temp" id="temp"><br/>

        <label for="disox">Dissolved Oxygen(ppm):</label> 
        <input type="text" name="disox" id="disox"><br/>

        <label for="vernierClarity">Vernier Clarity(ntu):</label>
        <input type="text" name="vernierClarity" id="vernierClarity"><br/>

        <label for="secchiClarity">Secchi Clarity(meter):</label>
        <input type="text" name="secchiClarity" id="secchiClarity"><br/>

        <label for="airTemp">Air Temp(Celsius):</label> 
        <input type="text" name="airTemp" id="airTemp"><br/>

        <label for="copper">Copper(ppm):</label> 
        <input type="text" name="copper" id="copper"><br/>

        <label for="phosphorus">Phosphorus(ppm):</label> 
        <input type="text" name="phosphorus" id="phosphorus"><br/>
      </fieldset>    
      <fieldset>
        <legend> Step 2: Review and Submit </legend>
        <p> Please review the above data and press submit data to commit it.</p>

        <input type="submit" name="submit" class="submitButton" value="Submit Data">
      </fieldset>
    </form>
</div>
