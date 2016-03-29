<?php
  // Report all errors
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  #check to see if anything was uploaded
  #php uses the temporary name of the file uploaded and not the actual name of the file. We need to use the temporary file
  if(!file_exists($_FILES['fileToUpload']['tmp_name']) || !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
    echo 'No upload';
  }
  #check to see if file uploaded is a python file
  if($_FILES['fileToUpload']['type'] != "text/plain"){
    echo "You did not upload a python file.";
  }
  #check to see if ambrosia is being uploaded by checking for: from ambrosia import *
  #echo file_get_contents($_FILES['fileToUpload']['tmp_name']);
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
    echo "This file does not contain an Ambrosia import statement.";
  }
  if($subProcessCheck == true){
    echo "Error. Dangerous statement was found. Found: subprocess";
  }
  if($osSystemCheck == true){
    echo "Error. Dangerous statement was found. Found: os.system";
  }
  if($sProcessCheck == true){
    echo "Error. Dangerous statement was found. Found: subprocess";
  }
  if($shCheck == true){
    echo "Error. Dangerous statement was found. Found: from sh";
  }

 ?>
