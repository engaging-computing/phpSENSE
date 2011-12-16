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
