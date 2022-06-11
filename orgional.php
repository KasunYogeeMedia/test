<?php
session_start();
error_reporting(0);

require_once 'dashboard/conn.php';
require_once 'dashboard/config.php';
require_once 'dashboard/dbconfig4.php';

if (isset($_SESSION["reid"])) {

    $image_qury = mysqli_query($conn, "SELECT * FROM lmsregister WHERE reid='" . $_SESSION["reid"] . "'");
    $image_resalt = mysqli_fetch_array($image_qury);

    $fullname = $image_resalt['fullname'];

    if ($image_resalt['image'] == "") {
        $dis_image_path = "profile/images/hd_dp.jpg";
    } else {
        $dis_image_path = "profile/uploadImg/" . $image_resalt['image'];
    }
}

$success_msg = 0;

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($con, strip_tags($_POST['fullname']));
    $address = mysqli_real_escape_string($con, strip_tags($_POST['address']));
    $contactnumber = (int)mysqli_real_escape_string($conn, $_POST['contactnumber']);
    $to = "0" . (int)mysqli_real_escape_string($conn, $_POST['contactnumber']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $password = md5(mysqli_real_escape_string($con, $_POST['password']));
    $re_password = md5(mysqli_real_escape_string($con, $_POST['re_password']));

    if ($password == $re_password) {

        $amilack_mobile_qury = mysqli_query($con, "SELECT * FROM lmsregister WHERE contactnumber='$contactnumber'");
        if (mysqli_num_rows($amilack_mobile_qury) > 0) {
            //user allready
            $success_msg = 1;
        } else {
            //pass
            if (mysqli_query($con, "INSERT INTO lmsregister (fullname,contactnumber, address, level,password, image, add_date, status, ip_address, relogin, reloging_ip, payment, verifycode) VALUES ('$fullname','$contactnumber','$address','$level','$password','', CURRENT_TIMESTAMP, '1', '', '0', '0', '0', '')")) {

                if (!empty($_POST['subjects'])) {
                    foreach ($_POST['subjects'] as $subject_id) {
                        mysqli_query($conn, "INSERT INTO lmsreq_subject(sub_req_reg_no, sub_req_sub_id) VALUES ('$contactnumber','$subject_id')");
                    }
                }

                $to = "+94" . (int)mysqli_real_escape_string($conn, $_POST['contactnumber']);
                $message_text = "Your Registration Completed Successfully.\nSign In Using Your Username: $contactnumber.\nPassword: $_POST[password]";
                $message = urlencode($message_text);
                $url = "https://cloud.websms.lk/smsAPI?sendsms&apikey=$sms_api_key&apitoken=$sms_api_token&type=sms&from=$sms_sender_id&to=$to&text=$message";
                file_get_contents($url);

                echo "<img src=''>";

                echo "<script>window.location='register.php?success';</script>";
            } else {
                //error
                $success_msg = 3;
            }
        }
        
    } else {
        //password error
        $success_msg = 2;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="Online Learning Platforms ">
    <meta name="author" content="Online Learning Platforms ">
    <title>Register | Online Learning Platforms </title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="images/fav.png">

    <!-- Stylesheets -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,500' rel='stylesheet'>
    <link href='profile/vendor/unicons-2.0.1/css/unicons.css' rel='stylesheet'>
    <link href="profile/css/vertical-responsive-menu.min.css" rel="stylesheet">
    <link href="profile/css/style.css" rel="stylesheet">
    <link href="profile/css/responsive.css" rel="stylesheet">
    <link href="profile/css/night-mode.css" rel="stylesheet">

    <!-- Vendor Stylesheets -->
    <link href="profile/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="profile/vendor/OwlCarousel/assets/owl.carousel.css" rel="stylesheet">
    <link href="profile/vendor/OwlCarousel/assets/owl.theme.default.min.css" rel="stylesheet">
    <link href="profile/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="profile/vendor/semantic/semantic.min.css">


    <style>
        input {
            border-radius: 0;
            box-yesadow: none;
            height: 40px;
            font-size: 13px;
            line-height: 20px;
            padding: 9px 12px;
            width: 100%;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        input:focus {

            border-radius: 0;

            box-yesadow: none;
            height: 40px;
            font-size: 13px;
            line-height: 20px;
            padding: 9px 12px;
            width: 100%;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        input.valid {
            border: 1px solid green;
            border-radius: 0;
            box-yesadow: none;
            height: 40px;
            font-size: 13px;
            line-height: 20px;
            padding: 9px 12px;
            width: 100%;
        }

        input.invalid {
            border: 1px solid red;
            border-radius: 0;
            box-yesadow: none;
            height: 40px;
            font-size: 13px;
            line-height: 20px;
            padding: 9px 12px;
            width: 100%;
        }

        input.invalid+.error-message {
            display: initial;
            color: red;
        }

        .error-message {
            display: none;
        }

        .error_Msg,
        .error_Msg2,
        .error_Msg3,
        .error_Msg4,
        .error_Msg5,
        .error_Msg6,
        .error_Msg7,
        .error_Msg8,
        .error_Msg9,
        .error_Msg10,
        .error_Msg11,
        .error_Msg12,
        .error_Msg13 {
            color: #fa4b2a;
            padding-left: 10px;
            font-family: Verdana;
            width: 100%;
        }
    </style>


</head>

<body>
    <!-- Signup Start -->
    <div class="sign_in_up_bg">
        <div class="container">
            <div class="row justify-content-lg-center justify-content-md-center">
                <div class="col-lg-12">
                    <div class="main_logo25" id="logo">
                        <a href="index.php"><img src="assets/images/logo/logonw.png" alt="" style="text-align:center;"></a>
                        <a href="index.php"><img class="logo-inverse" src="profile/images/ct_logo.png" alt=""></a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8">
                    <div class="sign_form">
                        <h2>Welcome Nasa </h2>
                        <p>Register and Start Learning!</p>
                        <form method="POST">
                            <?php if ($success_msg == 1) { ?><div class="alert alert-primary" style="font-weight:bold;background-color:#007bff;color:#ffffff;">Sorry! You are already registered.</div><?php } ?>
                            <?php if ($success_msg == 2) { ?><div class="alert alert-danger" style="font-weight:bold;background-color:#dc3545;color:#ffffff;">Error! The Re-Enter Password you entered does not match.</div><?php } ?>
                            <?php if ($success_msg == 3) { ?><div class="alert alert-danger" style="font-weight:bold;background-color:#dc3545;color:#ffffff;">Error! Your entered details something is wrong. Please try again.</div><?php } ?>
                            <?php if (isset($_GET['success'])) { ?><div class="alert alert-success" style="font-weight:bold;background-color:#28a745;color:#ffffff;">Thank You! Your Account Registration Is Successful. Log In To Your Account By <a href="login.php">Clicking Here.</a></div><?php } ?>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Full Name</label>
                                        <input name="fullname" required type="text" class="form-control fullname" placeholder="Enter Full Name" value="<?php if (isset($_POST['fullname'])) {
                                                                                                                                                            echo $_POST['fullname'];
                                                                                                                                                        } ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Phone Number</label>
                                        <input name="contactnumber" type="text" required placeholder="Enter Phone Number" class="form-control phone_val" value="<?php if (isset($_POST['contactnumber'])) {
                                                                                                                                                                    echo $_POST['contactnumber'];
                                                                                                                                                                } ?>" maxlength="10" minlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Address</label>
                                        <input name="address" type="text" required placeholder="Enter Address" class="form-control phone_val" value="<?php if (isset($_POST['address'])) {
                                                                                                                                                            echo $_POST['address'];
                                                                                                                                                        } ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Grade</label><br>
                                        <span id="class_load">
                                            <select name="level" required id="class_val" onChange="JavaScript:select_subject(this.value);" class="form-control simple" style="width:100%;">
                                                <option value="" hidden="yes">Select Grade</option>
                                                <?php
                                                $stmt = $DB_con->prepare('SELECT * FROM lmsclass ORDER BY cid');
                                                $stmt->execute();
                                                if ($stmt->rowCount() > 0) {
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        extract($row);
                                                ?>
                                                        <option value="<?php echo $row['cid']; ?>"><?php echo $row['name']; ?></option>
                                                <?php }
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function select_subject(sub_val) {
                                    var xhttp = new XMLHttpRequest();
                                    xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            document.getElementById("sub_load").innerHTML = this.responseText;
                                        }
                                    };
                                    xhttp.open("GET", "sub_load.php?cid=" + sub_val, true);
                                    xhttp.send();
                                }
                            </script>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Subject</label>
                                        <br>
                                        <div id="sub_load">
                                            <hr>
                                            Subject Not Found
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Password</label>
                                        <input name="password" type="password" class="form-control password" placeholder="Enter more than 8 characters" minlength="8">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="single-form">
                                        <label style="font-weight:bold;text-align:left;">Confirm Password</label>
                                        <input name="re_password" type="password" class="form-control passwordcon" placeholder="Enter your password again" minlength="8">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="single-form">
                                        <input type="submit" name="register" value="Register" class="btn btn-primary btn-block" style="background:#28a745;color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <p class="mb-0 mt-30">Already have an account? <a href="login.php">Log In</a></p>
                    </div>
                    <div class="sign_footer">© 2021 nasa.lk | All Rights Reserved | Developed By YogeeMedia</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Signup End -->

    <script src="profile/js/jquery-3.3.1.min.js"></script>
    <script src="profile/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="profile/vendor/OwlCarousel/owl.carousel.js"></script>
    <script src="profile/vendor/semantic/semantic.min.js"></script>
    <script src="profile/js/custom.js"></script>
    <script src="profile/js/night-mode.js"></script>

</body>

</html>