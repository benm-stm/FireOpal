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
require_once('../include/common/User.class.php');

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>Automatic validation, Sign Up</title>
<link href="include/css/index.css" rel="stylesheet" type="text/css" />
<link href="include/css/step1-2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js" ></script>
';

$action = (isset($_POST['action']) ? $_POST['action'] : '');
$type   = (isset($_POST['type']) ? $_POST['type'] : '');

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['sess_idUser_temp'])) {
    header('Location: step1.php');
}

$req2 = '<div style="width:20px; float:left;">
         <img src="include/images/signup/puce_erreur_blc.jpg"/>
         </div>';
$req3 = '<div style="width:20px; float:left;">
         <img src="include/images/signup/puce_erreur_blc.jpg"/>
         </div>';
$req4 = '<div style="width:20px; float:left;">
         <img src="include/images/signup/puce_erreur_blc.jpg"/>
         </div>';
    $surname      = (isset($_POST['surname']) ?  $_POST['surname'] : '');
    $family       = (isset($_POST['family']) ?  $_POST['family'] : '');
    $organisation = (isset($_POST['organisation']) ?  $_POST['organisation'] : '');


if (isset($_SESSION['sess_step'])) {
    if((int)$_SESSION['sess_step'] != 2)
    header("location:step".$_SESSION['sess_step'].".php");
}/* else {
    $_SESSION['sess_step'] = 1;
    header("location:step1.php");
}*/
if (!empty($surname) && !empty($family)) {
    if (isset($_SESSION['sess_idUser_temp'])) {
        $user = new user();
        $user->loadFromId($_SESSION['sess_idUser_temp']);
        $user->surname      = $surname;
        $user->familyName   = $family;
        $user->organisation = $organisation;
        var_dump($user);
        $user->save($_SESSION['sess_idUser_temp']);
    }
    $user->sendMail(0, 0);
    //echo "User Successfully registred, check your email";
    header("location:index.php");
}

$error = '';
$cross = true;
$search_word = '';
echo '
</head>
<body >
<form name="sign_up_form" action="step2.php" method="post" enctype="multipart/form-data" >
    <div id="body-sign">
        <div id="blocprinc">
            <div id="sign-bloc1">
            <div id="sign-titre1"><strong>SIGN UP</strong></div>
                <div id="sign-titre2">3 QUICK STEPS <br/>
                    <span class="greenTitle">enabling you to:</span>
                </div>
                <div class="date" id="sign-titre3">
                    <div>
                    <img src="include/images/signup/puce_vert.jpg" />
                    <span class="date">Add test cases</span>
                    </div>
                    <div>
                    <img src="include/images/signup/puce_vert.jpg"/>
                    <span class="date">Build testsuites</span>
                    </div>
                    <div>
                    <img src="include/images/signup/puce_vert.jpg" />
                    <span class="date">Validate releases</span>
                    </div>
                </div>
            </div>
        <div id="sign-bloc2">
        <div id="sign-titre4">
        <div id="btn1"><strong>STEP 1</strong></div>
        <div id="btn2"><strong>STEP 2</strong></div>
        </div>
        <div id="sign-titre5">ENTER YOUR INFORMATION VISIBLE ON AUTOMATIC VALIDATION ENGINE</div>
        <div class="date" id="sign-titre6">
        <div id="sign-titre770">
        <div class="sign-titre7-11">This data could appear on the test cases you add or test suites you run.<br />
        </div> 
        </div>
        <div class="sign01">
    <div class="sign1-titre7">
        <div class="sign2-titre7-11">First Name / Surname *</div>
        <div class="champs01">';
echo $req2.'
        <input id="surname" name="surname" type="text"  />
        </div>
    </div>
    <div class="sign1-titre7">
        <div class="sign2-titre7-11">Last Name / Family Name *</div>
        <div class="champs01">';
echo $req3.'
            <input id="family" name="family" type="text" />
      </div>
    </div>
    <div class="sign1-titre8">
      <div class="sign2-titre7-11">Organisation *</div>
      <div class="champs01">';
echo $req4.'
          <input id="organisation" name="organisation" type="text"  />
      </div>
    </div>
    <div class="sign02">
';

echo '
    <div class="sign02-1"> 
    <input id="action" name="action" type="hidden"  />
    </div>
    </div>
    <div class="sign04">
<div class="sign04_01">
<a href="step1.php?return=1" >
    <img alt="Automatic validation engine"
    src="include/images/sign/previous.jpg" width="66" height="18" border="0" />
</a>
</div>
<div class="sign04_02">
    <input type="image"  value="submit" id="btnNext" 
    src="include/images/sign/validate.jpg" width="66" height="18" border="0"
    alt="Automatic validation engine">
</div>
</div>
<div class="sign03" style="display:block">';
echo $error.'
    </div>
    <div id="sign-titre7740">
    <p class="a2">- Your email will not be visible on the Automatic validation engine<br />
    - Mandatory information fields *<br />
    <br />
    </p>
    <p class="a2">- Please read our <span class="greenLink">
    <a href="#" class="greenLink">Privacy Policy</a></span>
    to find out how we use your data. You must also read our
    <a href="#" class="greenLink">Terms of Service</a>.<br/>
    &nbsp;&nbsp;By submitting this form, you are agreeing that you have read and understood.<br />
    <br /> - (1) By uploading a file you certify that you have the right to distribute this picture and that it does not violate the
    <a href="#" class="greenLink">Terms of Service</a>.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
';

echo '
    </body>
</html>
';
?>