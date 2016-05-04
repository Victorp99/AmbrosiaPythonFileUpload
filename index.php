<?
  /*
    print_r($_POST);
    print_r($_FILES);
    */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252"></meta>
   <title>File Upload</title>
   <link href="page1.css" type="text/css" rel="stylesheet">
   <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
   <script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <script src ="app.js"></script> <!-- load in javascript file -->
<script>
function myFunction() {
    document.getElementById("uploadForm").multiple = true;
 }

  function check()
  {
    var name= document.forms["form1"]["username"].value;
    var password= document.forms["form1"]["pass"].value;
     if (name==null || name=="") {
      alert("Please enter username");
      return false;
    }

     if (password==null || password=="") {
      alert("Please enter password");
      return false;
       }
    else {
      return true;
    }
  }

</script>
  </head>


<body>
<div class="container">
  <div class="page-header">
		<img  src="https://www.strose.edu/wp-content/themes/lima/images/logo-print.png" alt="Saint Rose Logo" class="img-rounded center-block" class="center-block" style="width:100px;height:100px;" >
	</div>
	<header>
		<h1 > Please select a python file to upload.</h1>
	</header>

 <form align="center" name="form1" onsubmit="return check();" method="post" id="uploadForm" enctype="multipart/form-data">

<div align="center">
	<font color="FFFF66" size="5px">
	<div class="form-group" >
		Username: <INPUT TYPE="TEXT" NAME="username"class="textox inline" > 
	</div>
	<div class="form-group">
		Password: <INPUT TYPE="PASSWORD" NAME="pass"class="textox">
	</div>	
	<div class="form-group">
		Run Time Limit (seconds): <input type="number" min="10" max= "86400" name="timelimit" class="textox" required/> 
	</div>	

	<div class="form-group">	
		Filename: <input type="file" class="textox" name="fileToUpload" id="fileToUpload" multiple>
	</div>
	<div class="form-group">
		  <input type="submit" class="btn btn-primary" id = "uploadPython" value="Submit" name="submit"/>
	</div>
    </font>	
</form>
</div>
<!--Place output in div below -->
<p style="color: #FFFF66;font-size:20px;">Output</p>
<div class= "well" style = "background-color: white; margin: 25px;text-align: center;">
  <div id = "pythonOutput" class= "pythonOutput">
  </div>
</div>


</body>
</html>
