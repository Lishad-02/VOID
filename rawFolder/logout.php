//welcome page
<?php
session_start();
    if(isset($_SESSION['name'])){
        $user = $_SESSION['name'];
    }else{
        header('location:login.php');
    }
?>


//logout
<?php
session_start();
session_unset();
header('location:userLoginF.php');
?>

//user login page
<?php
session_start();
    if(isset($_SESSION['name'])){
        header('location:welcome.php');
    }//else{
       // header('location:login.php');
    //}
?>


//admin login page
<?php
session_start();
    if(isset($_SESSION['name'])){
        header('location:welcome.php');
    }//else{
       // header('location:login.php');
    //}
?>
