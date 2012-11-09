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

require_once('../include/common/User.class.php');

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>Fire Opal, Sign Up</title>
<link rel="stylesheet" type="text/css" href="css/style2.css">
<link href="include/css/index.css" rel="stylesheet" type="text/css" />
<link href="include/include/css/style.css" rel="stylesheet" type="text/css" />
<link href="include/css/step1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js" ></script>
</head>
';

$action = (isset($_POST['action']) ? $_POST['action'] : 'nothing');
$type = (isset($_POST['type']) ? $_POST['type'] : '');


$loginExist = 0;

$req1 = '<div style="width:20px; float:left;">
            <img src="include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req2 = '<div style="width:20px; float:left;">
            <img src="include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req3 = '<div style="width:20px; float:left;">
            <img src="include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req4 = '<div style="width:20px; float:left;">
            <img src="include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$reqSurname = '<div style="width:20px; float:left;">
         <img src="include/images/signup/puce_erreur_blc.jpg"/>
         </div>';


if (isset($_GET['return']) and isset($_SESSION['sess_idUser_temp'])) {
    $user = new user();
    $userTemp = $_SESSION['sess_idUser_temp'];
    $user->deleteUser($userTemp);
    session_unset();
    session_destroy();
}

$error = '';
if (isset($_POST['login'])) {
    $login  = (isset($_POST['login']) ? trim($_POST['login']) : '');
    $pass   = (isset($_POST['pass']) ? trim($_POST['pass']) : '');
    $rpass  = (isset($_POST['rpass']) ? trim($_POST['rpass']) : '');
    $cross  = true;

    if ($login == '' or $pass == '' or $rpass == '') {
        if ($login == '' )
            $req2 = '<div style="width:20px; float:left;">
                        <img src="include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($pass == '' )
            $req3 = '<div style="width:20px; float:left;">
                        <img src="include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($rpass == '' )
            $req4 = '<div style="width:20px; float:left;">
                         <img src="include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($login == '' )
            $error .= '&nbsp;Type your login,';
        if ($pass == '' )
            $error .= '&nbsp;type your password,';
        if ($rpass == '' )
            $error .= '&nbsp;retype your password,&nbsp;';
        $cross  = false;
}

    $surname       = (isset($_POST['surname']) ? trim($_POST['surname']) : '');


if ( strnatcmp($pass, $rpass) != 0 ) {
    $error .= '&nbsp;the password you typed is not identical on both lines,&nbsp;';
    $req4 = '<div style="width:20px; float:left;">
                <img src="include/images/signup/puce_erreur.jpg" />
            </div>';
    $cross = false;
}

// Input validated, continue
if ($cross == true) {
    $user        = new user();
    $user->login = $login;
    if ($user->userExist())
        $cross = false;
        if ($cross == true) {
            $user->password     = $pass;
            $user->surname      = $surname;
            $user->save();
            $user->initWorkSpace();
            $_SESSION['sess_idUser_temp']       = $user->getAtt('id');
            $_SESSION['sess_completeRecording'] = 1;
            //echo "User Successfully registred";
            header("location:index.php");
        } else {
            $req1 = '<div style="width:20px; float:left;">
            <img src="include/images/signup/puce_erreur.jpg" />
            </div>';
            //$req2 = '<font color="red" ><strong>!</strong></font>';
            $error .= '&nbsp;This login was already used to Sign up,&nbsp;';
            $loginExist = 1 ;
        }
    }
}

$errorMsg = '';
if ($error != '') {
    if($loginExist) {
        $error = str_replace("&nbsp;&nbsp;","&nbsp;",$error);
        if(strrchr($error,",") == ",&nbsp;") $error = substr($error,0,(strlen($error)-7));
        $error  = $error."." ;
        $error .= '<br><br><p class="a2" style="font-weight:normal">
                  Want to <a href="sign.php" class="lienvert2">Sign in ?</a><br />
                  <a href="#" class="lienvert2">Forgot your password ?</a></p>';
    } else {
        $error = str_replace("&nbsp;&nbsp;","&nbsp;",$error);
        if(strrchr($error,",") == ",&nbsp;") $error = substr($error,0,(strlen($error)-7));
        $error = $error."." ;
    }
    $errorMsg = 'ERROR: '.$error.'<br><br>';
}

echo '
<body>
<form action="step1.php" method="post" enctype="multipart/form-data" >
<div id="body-sign">
    <div id="blocprinc">
        <div id="sign-bloc1">
            <div id="sign-titre1"><strong>SIGN UP</strong></div>
                <div id="sign-titre2"> FireOpal <br/>
                    <span class="greenTitle">enable you to:</span>
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
                 <div id="sign-titre1"><strong>Who are you?</strong></div>
            </div>
            <div id="sign-titre5">
                ENTER YOUR LOGIN AND CHOOSE YOUR PASSWORD
            </div>';
echo '<div class="date" id="sign-titre6">
        <div id="sign-titre7">
        <div id="sign-titre7-15">First Name / Surname *</div>
        <div class="champs1">';
echo $reqSurname.'
        <input id="surname" name="surname" type="text" class="champs"/>
        </div>
    </div>';
echo ' <div class="date" id="sign-titre6">
                <div id="sign-titre771">
                    <div id="sign-titre7-15">
                        Enter your login*
                    </div> 
                <div class="champs2">';
echo $req2.'
                    <input id="login" name="login" type="text" class="champs"/>
                </div>
            </div>
            <div id="sign-titre772">
                <div id="sign-titre7-15">
                    Enter your password*
                </div> 
                <div class="champs3">';
echo $req3.'
                    <input name="pass" type="password" class="champs" value="" />
                </div>
            </div>
            <div id="sign-titre773">
                <div id="sign-titre7-15">
                    Retype your password*
                </div> 
                <div class="champs4">';
echo $req4.'
                    <input name="rpass" type="password" class="champs" value="" />
                </div>
            </div>
            <div class="sign03" style="display:block;" >';
echo $errorMsg.'
            </div>
            <div id="sign-titre8">
                <input type="image"  value="Next" id="btnNext" src="include/images/sign/validate.jpg" width="80" height="28" border="0" alt="FireOpal">
            </div>
            <div id="sign-titre774">
                <p class="a2">-Mandatory information fields *<br /><br />
                </p>
            </div>
        </div>
    </div>
</div>
</div>
</form>
</body>
</html>';
?>
