<?php
   ob_start();
   session_start();
?>

<div id="login_form">
    <form name="f1" method="post" action="login.php">
        <table>
            <tr>
                <td class="f1_label">User Name :</td><td><input type="text" name="username" value="" />
                </td>
            </tr>
            <tr>
                <td class="f1_label">Password  :</td><td><input type="password" name="password" value=""  />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="login" value="Log In" style="font-size:18px; " />
                </td>
            </tr>
        </table>
    </form> 
</div>
<div>
<?php
            $msg = '';
             // test if username and password is empty 
            if (isset($_POST['login'])) {
		if(!empty($_POST['username'])&& !empty($_POST['password'])) {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();  // update creation time
                  $_SESSION['username'] = $_POST['username']; // session suername
				  $_SESSION['password'] = $_POST['password'];  // session password
				  header('Location: index.php'); //link to upload page 
		}
		else{
				$msg =  'Please enter your username and password.';
		}
            }
		
         ?>
</div>
<label name="message"><?php echo $msg;?></label>