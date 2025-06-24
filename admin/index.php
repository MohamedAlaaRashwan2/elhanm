<?php
//session start
session_start();
if (isset($_SESSION['Admin'])) {

    header('Location:dashbord.php');
}
$notNav = "";
$title = "Login";
// include The Intales File To Derctor 
include "init.php";



// cheak The Server Send Method

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Named The Varibels
    $name = $_POST['user'];
    $pass = $_POST['pass'];
    $Passhide = sha1($pass);

    // Cheak If The User Found In The Database

    $stam = $con->prepare("SELECT ID,Username, Pass FROM admins WHERE Username = ? AND Pass=? AND Groubid=1 OR Groubid=2 LIMIT 1");
    $stam->execute(array($name, $Passhide));
    $rowmember = $stam->fetch(); //get the user table
    $count = $stam->rowCount();


    // If The Count Row IN Database > 0 
    if ($count > 0) {
        $_SESSION['Admin'] = $name;
        $_SESSION['ID'] = $rowmember['ID'];
        header('Location:dashbord.php');
        exit();
    }


}

?>


<form class="form-admin" action=<?php echo $_SERVER['PHP_SELF'] ?> method="POST">
    <h4 class="text-center">Admin Login</h4>

    <input type="text" class="form-control" name="user" placeholder="UserName" autocomplete="off" />
    <input type="password" class="form-control" name="pass" placeholder="PassWord" autocomplete="new-password" />
    <input type="submit" class="btn btn-primary btn-block" value="login" />

</form>
<?php
include $ft . "footer.php"

    ?>