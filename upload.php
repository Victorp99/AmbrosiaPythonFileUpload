<?php
  set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
  include('Net/SSH2.php');
  
  // Report all errors
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  #check to see if anything was uploaded
  #php uses the temporary name of the file uploaded and not the actual name of the file. We need to use the temporary file
  if(!file_exists($_FILES['fileToUpload']['tmp_name']) || !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
	exit('No upload');
  }
#check to see if file's mime type is python's mime type.
  /*if($_FILES['fileToUpload']['type'] != "text/plain"){
	exit("You did not upload a python file.");
  }*/

  $path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
  $extension = $path_parts['extension'];
  #check to see if the file has an .py extension
  if($extension != "py"){
    exit("Please upload a file that has a .py extension.");
  }

  #check to see if ambrosia is being uploaded by checking for: from ambrosia import *
  $fileContents = file_get_contents($_FILES['fileToUpload']['tmp_name']);
  function multiexplode ($delimiters,$string) {
	$ready = str_replace($delimiters, $delimiters[0], $string);
	$launch = explode($delimiters[0], $ready);
	return  $launch;
  }
  $words = multiexplode(array(" ","\n","\r","\t"),$fileContents); #stores each word into an array
  $x = 0;
  $noAmbrosia = false;
  #check to see if file is trying to make system calls
  $y = 0;
  $subProcessCheck = false;
  $osSystemCheck = false;
  $sProcessCheck = false; #catch all instances of subprocess
  $shCheck = false;
  while($y < count($words) -1 && $noAmbrosia == false){
	#ambrosia import statement was found
	if($words[$y] == "from" && $words[$y + 1] == "ambrosia" && $words[$y + 2] == "import" && $words[$y + 3] == "*"){
	  $noAmbrosia = true;
	}
	#from subprocess import call was found. Used to make shell commands
	if($words[$y] == "from" && $words[$x + 1] == "subprocess" && $words[$x + 2] == "import" && $words[$x + 3] == "call"){
	  $subProcessCheck = true;
	}
	#used for quick scripts
	if($words[$y] == "os.system" || $words[$y] == "os"){
	  $osSystemCheck = true;
	}
	if($words[$y] == "subprocess"){
	  $sProcessCheck = true;
	}
	if($words[$y] == "from" && $words[$y] == "sh"){
	  $shCheck = true;
	}
	$y++;
  }
  #ambrosia import statement was not in the file uploaded
  if($noAmbrosia == false){
	exit("This file does not contain an Ambrosia import statement.");
  }
  if($subProcessCheck == true){
	exit("Error. Dangerous statement was found. Found: subprocess");
  }
  if($osSystemCheck == true){
	exit("Error. Dangerous statement was found. Found: os.system");
  }
  if($sProcessCheck == true){
	exit("Error. Dangerous statement was found. Found: subprocess");
  }
  if($shCheck == true){
	exit("Error. Dangerous statement was found. Found: from sh");
  }

  //if validation of file was successful, run the python file and return the output
  echo 'You have requested to run the file ' . $_FILES['fileToUpload']['name'] . "<br>";
  
  #change permission of the uploaded file so that it can be moved after ssh
  system('chmod 777 ' . $_FILES['fileToUpload']['tmp_name']);
  
  #log in via ssh with ascg username and password
  $ssh = new Net_SSH2('ascg.strose.edu');
  if (!$ssh->login($_POST["username"], $_POST["pass"])) {
	   exit('Login Failed');
  }

  $ssh->setTimeout($_POST["timelimit"]);
  #move the uploaded python to the user's home area
  $fmove = $ssh->exec('cp ' . $_FILES['fileToUpload']['tmp_name'] . ' ./' . $_FILES['fileToUpload']['name']);

  #get the shell
  $checksh = $ssh->exec('echo $0');

  #if bash shell, set python path and run file; otherwise just run file.
  if (trim($checksh) == 'bash'){
	$output = $ssh->exec('export PYTHONPATH=${PYTHONPATH}:/home/ascg/ambrosia/python/; python3 ' . $_FILES['fileToUpload']['name']);
  }
  else{
	  $output = $ssh->exec('python3 ' . $_FILES['fileToUpload']['name']);
  }
  
  #if the job exceeds the run time, alert to partial output
  if($ssh->isTimeout()){
	  echo "!!!!!!WARNING!!!!!!<br>
	  You have exceeded the run time limit.<br>
	  Any output displayed below may only be PARTIAL.<br>
	  Try increasing the run time limit and re-submitting.<br>
	  If that doesn't work, there may be a problem with your input (e.g. infinite loop).<br>
	  <br>
	  !!!!!!PARTIAL OUTPUT BELOW!!!!!!<br>";
	  $tokill = $ssh->exec(" ps ux -o pid,command | grep [p]ython3");
	  $topid = preg_split('/\s+/', $tokill);
	  $pid = $topid[1];
	  $kill = true;
  }
  else{
	  $kill = false;
  }
  echo 'Output: <br> ' . $output . '<br>';

  #process the output
  $imagefiles = explode("]", $output);
  unset($imagefiles[count($imagefiles)-1]);
  foreach ($imagefiles as &$image){
	$image = substr($image,strpos($image,"[")+1,strpos($image,":")-strpos($image,"[")-1);
  }
  
  #move the image files so that they are accessible
  foreach ($imagefiles as $im){
	  echo $ssh->exec('mv /tmp/' . $im . ' /usr/home/www/ambrosia/images/');
  }
  
  #remove the python file that was run
  echo $ssh->exec('rm ' . $_FILES['fileToUpload']['name']);
  echo '<br>';
  
  #display the image files
  foreach ($imagefiles as $im){
	  echo '<img src=./images/' . $im . '> <br>';
  }
  
  #if the run time limit was exceeded, kill the job
  if($kill){
	  $ssh->exec('kill ' . trim($pid));
  }
  $ssh->disconnect();
 ?>
