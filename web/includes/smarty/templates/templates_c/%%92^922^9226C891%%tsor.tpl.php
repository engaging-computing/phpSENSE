<?php /* Smarty version 2.6.22, created on 2011-11-29 17:15:17
         compiled from tsor.tpl */ ?>
<div id="main">
    <form action="tsor.php" method=post>
      <fieldset id="raac-info">
        <legend> Step 1. Enter Collected Data </legend>
        <p>Please enter the collected data as instructed. Fields may be left blank if they were not measured. </p>

        <label for="school">School Name:</label> 
        <input type="text" style="width:250px;" name="school"><br/>

        <label for="team">Team:</label>
        <select name="team">
          <option value="">Choose....</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
          <option value="F">F</option>
        </select></br>

        <label for="testType">Test Type:</label>
        <select name="testType">
          <option value="Manual">Manual</option>
          <option value="Chemical">Chemical</option>
          <option value="PINPoint">PINPoint</option>
        </select></br>
        <label for="sessionLoc">Location:</label>

        <select name="sessionLoc">
          <option value="upriver">Up River</option>
          <option value="downriver">Down River</option>
          <option value="dock">Dock</option>
          <option value="canals">Canals</option>
        </select></br>
        
        <label for="pH">pH:</label> 
        <input type="text" name="pH"><br/>

        <label for="temp">Water Temp(Celsius):</label> 
        <input type="text" name="temp"><br/>

        <label for="disox">Dissolved Oxygen(ppm):</label> 
        <input type="text" name="disox"><br/>

        <label for="vernierClarity">Vernier Clarity(ntu)</label>
        <input type="text" name="vernierClarity"><br/>

        <label for="secchiClarity">Secchi Clarity(meter)</label>
        <input type="text" name="secchiClarity"><br/>

        <label for="airTemp">Air Temp(Celsius):</label> 
        <input type="text" name="airTemp"><br/>

        <label for="copper">Copper(ppm):</label> 
        <input type="text" name="copper"><br/>

        <label for="phosphorus">Phosphorus(ppm)</label> 
        <input type="text" name="phosphorus"><br/>
      </fieldset>    
      <fieldset>
        <legend> Step 2: Review and Submit </legend>
        <p> Please review the above data and press submit data to commit it.</p>

        <input type="submit" name="submit" class="submitButton" value="Submit Data">
      </fieldset>
    </form>
</div>