<?php
/**
 * Copyright (c) STMicroelectronics 2012. All rights reserved
 *
 * This code is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this code. If not, see <http://www.gnu.org/licenses/>.
 */

header("Content-Type: text/html; charset=iso-8859-1");
require_once('User.class.php');

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>Sign up</title>
<link href="../www/include/css/sign.css" rel="stylesheet" type="text/css" />
</head>
';

// Select the username from the cookie
//$username = $_COOKIE['AVTool']['username'];
$cookie_name  = 'remember';
$messageStack = '';
if (!isset($_SESSION)) {
    session_start();
}
if(isset($_POST['email'])) {
    $email = $_POST['email'];
    // setcookie($cookie_name,$email, $time + 3600);
    $pass  = $_POST['pass'];
    if(isset($_POST['remember']) && !empty($_POST['email'])) {
        $check = $_POST['remember'];
    }
    $user  = new user();

    if(!$user->controlPassword($email, $pass)) {
        $messageStack = '
        <div class="messageStackError">
        <img src="../www/include/images/ic/error.png" alt="">&nbsp;
                Error: wrong email or password
        </div>
        ';
    } else {
        $_SESSION['sess_idUser'] = $user->id;
        if($check == 1) {
        //rememberMe
        //setcookie('rememberMe[mail]',$email,  time()  + 3600);
            setcookie('rememberMe', $user->id, time() + 3600*24*7);
        }
        header('Location: ../www/index.php?id='.$user->id);
    }
}

else if(isset($_COOKIE['rememberMe'])) {
    // $email = $_COOKIE['rememberMe[mail]'];
    // if(isset($_COOKIE['rememberMe[id]'])) {
    $_SESSION['sess_idUser'] = $_COOKIE['rememberMe'];
    header('Location: ../www/index.php');
    //}
}

echo '
<body>
<div id="body2">
<div id="separation"></div>
<div id="blocsign">
<div id="blocsign1-1">
<div id="sign_titreb1"><strong>SIGN UP</strong></div>
<div class="blocsign1-1">
<div class="sign_titreb1_1">CREATE YOUR ACCOUNT</div>
<div class="sign_titreb1_2">
    <img src="../www/include/images/sign/puce2.jpg" width="8" height="8" />
    <span class="joinText">Add Ruby/webdriver testcases</span></div>
<div class="sign_titreb1_3">
    <img src="../www/include/images/sign/puce2.jpg" width="8" height="8" /> 
    <span class="joinText">Set up configuration params</span></div>
<div class="sign_titreb1_3">
    <img src="../www/include/images/sign/puce2.jpg" width="8" height="8" />
    <span class="joinText">Create and run testsuites</span></div>
<div class="sign_titreb1_3">
    <img src="../www/include/images/sign/puce2.jpg" width="8" height="8" />
    <span class="joinText">Command line interface</span></div>
<div class="sign_titreb1_3">
    <img src="../www/include/images/sign/puce2.jpg" width="8" height="8" />
    <span class="joinText">Retrieve testsuites results</span></div>
<div class="sign_titreb1_4">
    <a href="step1.php">
    <img src="../www/include/images/sign/joinfree.jpg" width="226" height="41" border="0" /></a>
    </div><div class="sign_titreb1_5"><img src="../www/include/images/sign/under.jpg" /></div>
<div class="sign_titreb1_6"><span class="txtgris" style="font-size:10px;">

</div>
</div>
</div>
<div id="blocsign2-1">
<div id="blocsign2-2">
<div id="sign_titreb51"><strong>SIGN IN</strong></div>
<div class="blocsign1-1">
<div class="sign_titreb1_1">YOU ALREADY HAVE AN ACCOUNT</div>
<form action="sign.php" method="post" enctype="multipart/form-data" >

<div class="sign_titreb2_1">
<div class="sign_titreb2_2"><p class="joinText">Your Email Address</p>
</div><div id="champs1"><input name="email" type="text" class="champs"/></div>
</div>

<div class="sign_titreb2_11">
<div class="sign_titreb2_2"><p class="joinText">Your Password</p>
</div><div id="champs1"><input type="password" id="pass" name="pass" class="champs"/></div>
</div>

<div class="sign_titreb2_5">
  <div class="sign_titreb2_4">
  <div style="float:left"><input type="checkbox" name="remember" id="remember" value="1"/> </div> 
<div class="joinText" style="float:right; padding-right:45px;" >Remember my details</div>
  </div>
</div>

<div class="sign_titreb2_6">
  <div id="champs1">
    <div class="sign_titreb2_4">
      <p class="joinText">
      <input type="image"  value="Next" id="btnNext" src="../www/include/images/sign/login.jpg" width="85" height="18" border="0" alt="sustenn, the Sustainable Development Portal" >
    </p>
    </div>
  </div>
</div>
</form>';
echo $messageStack;
echo '
<div class="sign_titreb2_7">
  <div id="champs1">
    <div class="sign_titreb2_4">
      <p class="joinText2"><a href="#" class="forgetLink" style="font-size:9px">Forgot your password ?</a><br />
        <a href="#" class="forgetLink" style="font-size:9px">Forgot your email address ?</a></p>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>';
?>