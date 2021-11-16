<?php

include_once("../../vendor/autoload.php");
include_once("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\DataManipulation\DataManipulation;
use App\Utility\Utility;


$mail = new PHPMailer(true);
$dbm = new DataManipulation();
$http_reffer = $_SERVER['HTTP_REFERER'];

if (!isset($_SESSION)) {
  session_start();
}
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: ../../index.php');
}

// otp check
// if (isset($_POST['check'])) {
//   $otp = $_POST['otp'];
//   $email = $_POST['email'];
//   $http_reffer = $_SERVER['HTTP_REFERER'];

//   $res = $dbm->checkToken($email, $otp);
//   if ($res) {
//     $curn = $dbm->tokenUpdate($email, $otp);
//     header("Location: ../../index.php");
//   } else {
//     $_SESSION['errorMessageRegister'] = "<div id=\"alertBox\" class=\"text-white px-4 py-3 border-0 rounded relative mb-4 bg-red-400\">
//         <span class=\"inline-block align-middle mr-8\">
//           <b class=\"capitalize\">Please </b> provide valid otp!
//         </span>
//         <button id=\"btnClose\" class=\"absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-0 mr-1 outline-none focus:outline-none\"\">
//           <span>×</span>
//         </button>
//       </div>";
//     header("Location: $http_reffer");
//   }
// }


// signup
if (isset($_POST['submit'])) {

  var_dump($_POST);
  $pass = $_POST['password'];
  $c_pass = $_POST['cpassword'];
  $http_reffer = $_SERVER['HTTP_REFERER'];

  if ($pass == $c_pass) {
    $receiver = $_POST['email'];
    $emailToken = rand(100000, 999999);
    // $name = $_POST['name'];
    $_POST['emailToken'] = $emailToken;

    $res = $dbm->emailIsExist($receiver);


    if (!$res) {
      try {
        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sysedu41@gmail.com';
        $mail->Password   = 'Sysedu123';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('sysedu41@gmail.com', 'Service System');
        $mail->addAddress("$receiver", 'User');
        $mail->addAddress('sysedu41@gmail.com');
        $mail->addReplyTo('sysedu41@gmail.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = "Verification Code";
        $mail->Body    = "<p>Here is your code <b> $emailToken </b></p>";
        $mail->AltBody = 'this is the body in plain text for non-HTML main clients';

        if ($mail->send()) {
          $dbm->setData($_POST);
          echo $_POST['emailToken'];
          $insert = $dbm->insertRegisterData();
          $_SESSION['m'] = $receiver;
          // header("HTTP/1.1 301 Moved Permanently");
          // Utility::redirect('./auth/user-otp.php');
          echo "<script type='text/javascript'>window.location.href='../auth/user-otp.php'</script>";
        }
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    } else {
      $_SESSION['errorMessageRegister'] = "<div class=\"alert alert-danger alert-dismissible rounded-0\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <h5><i class=\"icon fas fa-ban\"></i> Oops!</h5>
            Already exists this email address. Please try another email address
          </div>";
      header("Location: $http_reffer");
    }
  } else {

    $_SESSION['errorMessageRegister'] = "<div id=\"alertBox\" class=\"text-white px-4 py-3 border-0 rounded relative mb-4 bg-red-400\">
        <span class=\"inline-block align-middle mr-8\">
          <b class=\"capitalize\">password</b> not match!
        </span>
        <button id=\"btnClose\" class=\"absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-0 mr-1 outline-none focus:outline-none\"\">
          <span>×</span>
        </button>
      </div>";
    header("Location: $http_reffer");
  }
}


// login
if (isset($_POST['login'])) {

  $email = $_POST['email'];
  $pass = $_POST['password'];
  $userType = $_POST['u_type'];
  $pass = md5($pass);
  $http_reffer = $_SERVER['HTTP_REFERER'];

  // var_dump($email,$pass,$userType);
  $res = $dbm->checkUser($email, $pass);


  if ($res) {

    if ($userType == 'teacher') {
      $_SESSION['res'] =  $res;
      header("Location: ../admin/admin_home.php");
    }
    if ($userType == 'student') {
      $_SESSION['res'] =  $res;
      header("Location: ../user/user_home.php");
    }
  } else {
    $_SESSION['errorMessageRegister'] = "<div id=\"alertBox\" class=\"text-white px-4 py-3 border-0 rounded relative mb-4 bg-red-400\">
        <span class=\"inline-block align-middle mr-8\">
          <b class=\"capitalize\">Invalid </b> email or password!
        </span>
        <button id=\"btnClose\" class=\"absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-0 mr-1 outline-none focus:outline-none\"\">
          <span>×</span>
        </button>
      </div>";
    header("Location: $http_reffer");
  }
}

// update Profile
if (isset($_POST['updateProfile'])) {
  // echo "<script type='text/javascript'>alert('aok ajk')</script>";



  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  

  $size = $_FILES['image']['size'];
  if($size > 0){
    $files = rand(1000,10000).$_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $destinationFile = '../../contents/images/'.$files;
    move_uploaded_file($fileTmpName, $destinationFile);
    $image = $destinationFile;
    $res = $dbm->updateUserInfo($id, $name, $email, $image);
  }
  else{
    $res = $dbm->updateUserData($id, $name, $email);
  }

  // var_dump($_FILES['image']);
 
  if ($res) {
    $_SESSION['updateMsg'] ="<div class=\"alert alert-success alert-dismissible rounded-0\">
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <h5><i class=\"icon fas fa-ban\"></i> Updated!</h5>
    
  </div>";;
     Utility::redirect("$http_reffer");
  } else {

    $_SESSION['updateMsg'] = "<div class=\"alert alert-danger alert-dismissible rounded-0\">
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <h5><i class=\"icon fas fa-ban\"></i> Failed!</h5>
   
  </div>";
    Utility::redirect("$http_reffer");
  }
}


if (isset($_POST['forgotEmail'])) {

  // var_dump($_POST);
  // $pass = $_POST['password'];
  // $c_pass = $_POST['cpassword'];
  $email = $_POST['email'];

  $http_reffer = $_SERVER['HTTP_REFERER'];

  $receiver = $_POST['email'];
  $emailToken = rand(100000, 999999);
  // $name = $_POST['name'];
  $_POST['emailToken'] = $emailToken;

  $res = $dbm->emailIsExist($receiver);


  if ($res) {
    try {
      //Server settings
      $mail->SMTPDebug = 2;
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'sysedu41@gmail.com';
      $mail->Password   = 'Sysedu123';
      $mail->SMTPSecure = 'ssl';
      $mail->Port       = 465;

      //Recipients
      $mail->setFrom('sysedu41@gmail.com', 'Service System');
      $mail->addAddress("$receiver", 'User');
      $mail->addAddress('sysedu41@gmail.com');
      $mail->addReplyTo('sysedu41@gmail.com', 'Information');

      $mail->isHTML(true);
      $mail->Subject = "Verification Code";
      $mail->Body    = "<p>Here is your code <b> $emailToken </b></p>";
      $mail->AltBody = 'this is the body in plain text for non-HTML main clients';

      if ($mail->send()) {

        $_SESSION['m'] = $receiver;
        $_SESSION['fpass'] = 'set';
        $dbm->otpUpdate($email, $emailToken);
        echo "<script type='text/javascript'>window.location.href='../auth/user-otp.php'</script>";
      }
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    $_SESSION['errorMessageRegister'] = "<div class=\"alert alert-danger alert-dismissible rounded-0\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <h5><i class=\"icon fas fa-ban\"></i> Oops!</h5>
           Mail not found!!!
          </div>";
    header("Location: $http_reffer");
  }
}

if (isset($_POST['check'])) {
  $otp = $_POST['otp'];
  $email = $_POST['email'];
  $http_reffer = $_SERVER['HTTP_REFERER'];

  $res = $dbm->checkToken($email, $otp);
  if ($res and (!$_SESSION['fpass'])) {
    $curn = $dbm->tokenUpdate($email, $otp);
    header("Location: ../../index.php");
  } else if ($_SESSION['fpass']) {
    header("Location: ../auth/forgot-pass.php");
    unset($_SESSION['fpass']);
  } else {
    $_SESSION['errorMessageRegister'] = "<div id=\"alertBox\" class=\"text-white px-4 py-3 border-0 rounded relative mb-4 bg-red-400\">
        <span class=\"inline-block align-middle mr-8\">
          <b class=\"capitalize\">Please </b> provide valid otp!
        </span>
        <button id=\"btnClose\" class=\"absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-0 mr-1 outline-none focus:outline-none\"\">
          <span>×</span>
        </button>
      </div>";
    header("Location: $http_reffer");
  }
}

if (isset($_POST['forgotPass'])) {
  //var_dump($_POST);

  $pass = $_POST['password'];
  $c_pass = $_POST['cpassword'];
  $email =  $_SESSION['m'];

  if ($pass == $c_pass) {
    $pass = md5($pass);
    $res = $dbm->updatePassword($email, $pass);
    header("Location: ../../index.php");
  } else {

    $_SESSION['errorMessageRegister'] = "<div id=\"alertBox\" class=\"text-white px-4 py-3 border-0 rounded relative mb-4 bg-red-400\">
      <span class=\"inline-block align-middle mr-8\">
        <b class=\"capitalize\">password</b> not match!
      </span>
      <button id=\"btnClose\" class=\"absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-0 mr-1 outline-none focus:outline-none\"\">
        <span>×</span>
      </button>
    </div>";
    header("Location: $http_reffer");
  }
}
