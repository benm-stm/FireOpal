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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>Automatic validation, Sign Up</title>
<link rel="stylesheet" type="text/css" href="css/style2.css">
<link href="../www/include/css/index.css" rel="stylesheet" type="text/css" />
<link href="../www/include/include/css/style.css" rel="stylesheet" type="text/css" />
<link href="../www/include/css/step1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js" ></script>
<script type="text/javascript" src="js/cookies-bandeau.js" ></script> 
<script src="include/js/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
';

$action = (isset($_POST['action']) ? $_POST['action'] : 'nothing');
$type = (isset($_POST['type']) ? $_POST['type'] : '');
$email = '';
$remail = '';

$mailexist = 0;

$req1 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req2 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req3 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur_blc.jpg" />
        </div>';
$req4 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur_blc.jpg" />
        </div>';

if (isset($_GET['return']) and isset($_SESSION['sess_idUser_temp'])) {
    $user = new user();
    $userTemp = $_SESSION['sess_idUser_temp'];
    $user->deleteUser($userTemp);
    session_unset();
    session_destroy();
}

/*if(isset($_SESSION['sess_step'])) 
{

 if((int)$_SESSION['sess_step'] != 1 and  (int)$_SESSION['sess_step'] != 4 )
 tep_redirect('step'.$_SESSION['sess_step'].'.php'); 
}
*/

// enregistrement 
if (isset($_SESSION['sess_idUser_temp'])) {
    $user = new clsUsers();
    $user->loadFromId($_SESSION['sess_idUser_temp']);
    $email  = $user->email;
    $remail = $user->email;
}

$error = '';
if (isset($_POST['email'])) {
    $email  = (isset($_POST['email']) ? trim($_POST['email']) : '');
    $remail = (isset($_POST['remail']) ? trim($_POST['remail']) : '');
    $pass   = (isset($_POST['pass']) ? trim($_POST['pass']) : '');
    $rpass  = (isset($_POST['rpass']) ? trim($_POST['rpass']) : '');
    $cross  = true;

    if ( $email == '' or $remail == '' or $pass == '' or $rpass == ''   ) {
        if ($email == '' )
            $req1 = '<div style="width:20px; float:left;">
                        <img src="../wwww/include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($remail == '' )
            $req2 = '<div style="width:20px; float:left;">
                        <img src="../www/include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($pass == '' )
            $req3 = '<div style="width:20px; float:left;">
                        <img src="../www/include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($rpass == '' )
            $req4 = '<div style="width:20px; float:left;">
                         <img src="../www/include/images/signup/puce_erreur.jpg" />
                    </div>';
        if ($email == '' )
            $error .= 'Type your email address,';
        if ($remail == '' )
            $error .='&nbsp;retype your email address,';
        if ($pass == '' )
            $error .= '&nbsp;type your password,';
        if ($rpass == '' )
            $error .= '&nbsp;retype your password,&nbsp;';
        $cross = false;
    /* $messageStack->add('Il y a des champs vides <br /> ', 'error'); */
}

if (strnatcmp($email, $remail) != 0) {
    $error .= '&nbsp;the email you typed is not identical on both lines,&nbsp;';   
    $req2 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur.jpg" />
            </div>';
    $cross = false;
/*   $messageStack->add('Vérifiez les emails <br />', 'error'); */  
}

$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#'; 
if (!preg_match($Syntaxe, $email)) {
    $req1 = '<div style="width:20px; float:left;">
            <img src="../www/include/images/signup/puce_erreur.jpg" />
            </div>';
    $error .= '&nbsp;check your email address,&nbsp;'; 
    $cross = false;
    /* $messageStack->add('Vérifiez votre syntaxe du champ email <br />', 'error'); */   
}
 
if ( strnatcmp($pass, $rpass) != 0 ) {
    $error .= '&nbsp;the password you typed is not identical on both lines,&nbsp;';
    $req4 = '<div style="width:20px; float:left;">
                <img src="../www/include/images/signup/puce_erreur.jpg" />
            </div>';
    $cross = false;
    /* $messageStack->add('Vérifiez les mots de passe <br />', 'error'); */
}
 

// Input validated, continue
if ($cross == true) {
    $user = new user();
    $user->email = $email;
    if ($user->userExist())
        $cross = false;
    if ($cross == true) {
        $user->password = $pass;
        $user->save();
        //$_SESSION['sess_idUser_temp'] = $user->getMaxUser();
        $_SESSION['sess_idUser_temp'] = 1;
        $_SESSION['sess_completeRecording'] = 0;
        $_SESSION['sess_step'] = 2;
        header('Location: step2.php');
        $messageStack->add('Enregistrement reussi <br />', 'success');
    } else {
        $req1 = '<div style="width:20px; float:left;">
                 <img src="../www/include/images/signup/puce_erreur.jpg" />
                 </div>';
        //$req2 = '<font color="red" ><strong>!</strong></font>';        
        $error .= '&nbsp;This email address was already used to Sign up,&nbsp;';
        $mailexist = 1 ;
        //$messageStack->add('check form : '.$error, 'error');
    }   
} else {}//$messageStack->add('check form : '.$error, 'error'); 
}

$errorMsg = '';
if ($error != '') {
    if($mailexist) {
        $error = str_replace("&nbsp;&nbsp;","&nbsp;",$error) ;
        if(strrchr($error,",") == ",&nbsp;") $error = substr($error,0,(strlen($error)-7)) ;
        $error = $error."." ;
        $error .= '<br><br><p class="a2" style="font-weight:normal">
                  Want to <a href="sign.php" class="lienvert2">Sign in ?</a><br />
                  <a href="#" class="lienvert2">Forgot your password ?</a></p>' ;
    } else {
        $error = str_replace("&nbsp;&nbsp;","&nbsp;",$error) ;
        if(strrchr($error,",") == ",&nbsp;") $error = substr($error,0,(strlen($error)-7)) ;
        $error = $error."." ;
    }
    $errorMsg = 'ERROR: '.$error.'<br><br>';
}

echo '
<body onload="check();check2();check3();check4();">
<form action="step1.php" method="post" enctype="multipart/form-data" >
<div id="body-sign">
    <div id="blocprinc">
        <div id="sign-bloc1">
            <div id="sign-titre1"><strong>SIGN UP</strong></div>
                <div id="sign-titre2">3 QUICK STEPS <br/>
                    <span class="greenTitle">enabling you to:</span>
                </div>
                <div class="date" id="sign-titre3">
                    <div>
                    <img src="../www/include/images/signup/puce_vert.jpg" />
                    <span class="date">Add test cases</span>
                    </div>
                    <div>
                    <img src="../www/include/images/signup/puce_vert.jpg"/>
                    <span class="date">Build testsuites</span>
                    </div>
                    <div>
                    <img src="../www/include/images/signup/puce_vert.jpg" />
                    <span class="date">Validate releases</span>
                    </div>
                </div>
            </div>
            <div id="sign-bloc2">
                <div id="sign-titre4">
                <div id="btn1">
                    <strong>STEP 1</strong>
                </div>
                <div id="btn2">
                    <a href="#" class="a3"><strong>STEP 2</strong></a>
                </div>
                <div id="btn3">
                    <a href="#" class="a3" ><strong>STEP 3</strong></a>
                </div>
            </div>
            <div id="sign-titre5">
                ENTER YOUR EMAIL AND CHOOSE YOUR PASSWORD
            </div>
            <div class="date" id="sign-titre6">
                <div id="sign-titre7">
                    <div id="sign-titre7-11">
                        Enter your email*
                    </div>
                    <div id="champs1">';
echo $req1.'
                        <input name="email" type="text" class="champs"/>
                    </div>
                </div>
                <div id="sign-titre771">
                    <div id="sign-titre7-12">
                        Retype your email*
                    </div> 
                <div id="champs2">';
echo $req2.'
                    <input name="remail" type="text" class="champs"/>
                </div>
            </div>
            <div id="sign-titre772">
                <div id="sign-titre7-13">
                    Enter your password*
                </div> 
                <div id="champs3">';
echo $req3.'
                    <input name="pass" type="password" class="champs" value="" />
                </div>
            </div>
            <div id="sign-titre773">
                <div id="sign-titre7-14">
                    Retype your password*
                </div> 
                <div id="champs4">';
echo $req4.'
                    <input name="rpass" type="password" class="champs" value="" />
                </div>
            </div>
            <div class="sign03" style="display:block;" >';
echo $errorMsg.'
            </div>
            <div id="sign-titre8">
                <input type="image"  value="Next" id="btnNext" src="../www/include/images/signup/next.jpg" width="66" height="18" border="0" alt="Automatic validation">
            </div>
            <div id="sign-titre774">
                <p class="a2">- Your email will not be visible on the Automatic validation engine<br />
                - Mandatory information fields *<br /><br />
                </p>
                <p class="a2">- Please read our <span class="greenLink">
                <a href="#" class="greenLink">Privacy Policy</a></span>
                to find out how we use your data. You must also read our
                <a href="#" class="greenLink">Terms of Service</a>.<br />
                &nbsp;&nbsp;By submitting this form, you are agreeing that you have read and understood.</p>
            </div>
        </div>
    </div>
</div>
</div>
</form>
</body>
</html>';
?>