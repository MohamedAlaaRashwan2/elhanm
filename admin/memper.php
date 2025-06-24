<?php
session_start();


$title = "memper";

// cheak if found session name
if (isset($_SESSION['Admin'])) {

    include "init.php";






    // Cheak if found the action in the title

    $action = isset($_GET['action']) ? $_GET['action'] : 'memper';

    if ($action == 'memper') {

        $Adminprofil = cheakName("Username", "admins", $_SESSION['Admin']);

        if ($Adminprofil == 1) {
            $stam = $con->prepare("UPDATE admins SET Groubid=2 WHERE ID = $_SESSION[ID]");
            $stam->execute();

        }


        $stam = $con->prepare("SELECT * FROM admins WHERE Groubid=1");
        $stam->execute();
        $rows = $stam->fetchAll();

        ?>
        <h1 class="text-center">Manage Member</h1>

        <div class="container">
            <div class="table">
                <table class="main-table text-center table table-bordered">
                    <thead>

                        <tr>
                            <td>ID</td>
                            <td>UserName</td>
                            <td>Email</td>
                            <td>FullName</td>
                            <td> Date Add</td>
                            <td>Control</td>
                        </tr>
                    </thead>
                    <?php
                    foreach ($rows as $row) {


                        echo "<tr>";

                        echo "<td> $row[ID] </td>";
                        echo "<td> $row[Username] </td>";
                        echo "<td> $row[Email] </td>";
                        echo "<td> $row[Fullname] </td>";
                        echo "<td> $row[date] </td>";
                        echo "<td>";
                        echo "<a href='memper.php?action=Edit Profil&ID=$row[ID]' class='btn btn-success'><i class='glyphicon glyphicon-edit'></i>Edit</a>";
                        echo "<a href='memper.php?action=delet Profil&ID=$row[ID]' class='btn btn-danger confirm'><i class='glyphicon glyphicon-remove'></i>Remove</a>";
                        echo "</td>";

                        echo "</tr>";

                    }
                    ?>

                </table>
                <a href="memper.php?action=add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a>
            </div>
        </div>


    <?php } elseif ($action == 'add') { ?>

        <h1 class="text-center">Add Member</h1>

        <div class="container">
            <form action="?action=insert" class="form-horizontal" method="POST">

                <div class="form-group form-group-lg">
                    <label for="#user" class="col-sm-2 control-label">UserName</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" id="user" class="form-control" autocomplete="off"
                            placeholder="Type Your Name To Control Panal" required />
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="#pass" class="col-sm-2 control-label">PassWord</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" id="pass" class="password form-control"
                            autocomplete="new-password" placeholder="PassWord Mast By Hard" required>
                        <i class="show-pass glyphicon glyphicon-eye-open"></i>
                    </div>
                </div>
                <div class=" form-group form-group-lg">
                    <label for="#email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" id="email" class="form-control" autocomplete="off"
                            placeholder="Type valided Email" required="required">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="#fullname" class="col-sm-2 control-label">FullName</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="fullname" id="fullname" class="form-control" autocomplete="off"
                            placeholder="Type Your Full Name" required="required">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-6">
                        <input type="submit" name="submit" value="Sign" class="btn btn-primary btn-lg">
                    </div>
                </div>

            </form>
        </div>



    <?php } elseif ($action == 'delet') {
        $IDuser = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;


        $stam = $con->prepare("SELECT * FROM admins WHERE ID=? LIMIT 1");

        $stam->execute(array($IDuser));

        $row = $stam->rowCount();

        if ($row > 0) {
            $stam = $con->prepare("DELETE FROM admins  WHERE ID = :did ");
            $stam->bindparam("did", $IDuser);
            $stam->execute();
            $mes = "<div class='alert alert-danger'> The Members Is Deleted</div>";
            redrect($mes, "Memper", 2, "memper.php");


        }




    } elseif ($action == "insert") {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_POST['name'];
            $pass = $_POST['password'];
            $secritpass = sha1($pass);
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            $formErrors = array();

            if (strlen($name) < 4) {
                $formErrors[] = "your name is short";
            }
            if (strlen($name) > 10) {
                $formErrors[] = "your name is tall ";
            }

            if (empty($name)) {
                $formErrors[] = "cant be name empty";
            }
            if (empty($pass)) {
                $formErrors[] = "cant be password empty";
            }
            if (is_numeric($pass)) {
                $formErrors[] = "PassWord Mast Have A Chrater";
            }
            if (empty($email)) {
                $formErrors[] = "cant be email empty";
            }
            if (empty($fullname)) {
                $formErrors[] = "cant be fullname empty";
            }

            if (strlen($fullname) > 20) {
                $formErrors[] = "your fullname is tall ";
            }



            // cheak if found any errors in array

            if (empty($formErrors)) {

                // cheak if no found uniqe name

                $cheak = cheakName("Username", "admins", $name);

                if ($cheak == 1) {


                    $mes = "<div class='alert alert-danger'> Soory This Name Is Found</div>";
                    redrect($mes, "Add Member", 2, "memper.php?action=add");



                } else {


                    $stam = $con->prepare("INSERT INTO admins (Username , Pass  , Email , Fullname ,Groubid) 
                    VALUES (:u,:p,:e,:f,:g)");
                    $stam->execute(
                        array(
                            ":u" => $name,
                            ":p" => $secritpass,
                            ":e" => $email,
                            ":f" => $fullname,
                            ":g" => 1

                        )
                    );
                    $count = $stam->rowCount();

                    if ($count > 0) {
                        $mes = "<div class='alert alert-success'> The Member Is Addes</div>";
                        redrect($mes, "Member", 2, "memper.php");


                    }
                }


            } else {
                foreach ($formErrors as $error) {
                    $mes = "<div class='alert alert-danger'>" . $error . "</div>";
                    redrect($mes, "Add Member", 2, "memper.php?action=add");
                }
            }



        } else {
            $mes = "<div class='alert alert-danger'> You Cant'n Acsses In this page</div>";
            redrect($mes, "Dashbord");
        }

    } elseif ($action == 'Edit Profil') {

        // if found id and id is nummber get this

        $IDuser = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

        // Cheak If The User Found In The Database

        $stam = $con->prepare("SELECT * FROM admins WHERE ID=? LIMIT 1");
        $stam->execute(array($IDuser));

        $rowmember = $stam->fetch();
        $count = $stam->rowCount();


        if ($count > 0) {


            ?>

            <h1 class="text-center">Edit Profile</h1>

            <div class="container">
                <form action="?action=update" class="form-horizontal" method="POST">

                    <input type="hidden" name="id" value="<?php echo $IDuser ?>">
                    <div class="form-group form-group-lg">
                        <label for="#user" class="col-sm-2 control-label">UserName</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" id="user" class="form-control"
                                value="<?php echo $rowmember['Username'] ?>" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="#pass" class="col-sm-2 control-label">PassWord</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpass" value="<?php echo $rowmember['Pass'] ?>">
                            <input type="password" name="newpass" id="pass" class="form-control" autocomplete="new-password"
                                placeholder="*********">
                        </div>
                    </div>
                    <div class=" form-group form-group-lg">
                        <label for="#email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" id="email" value="<?php echo $rowmember['Email'] ?> "
                                class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="#fullname" class="col-sm-2 control-label">FullName</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="fullname" id="fullname" class="form-control"
                                value="<?php echo $rowmember['Fullname'] ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10 col-md-6">
                            <input type="submit" name="submit" value="Updata" class="btn btn-primary btn-lg">
                        </div>
                    </div>

                </form>
            </div>


            <?php
            // if not fouud this id
        } else {
            $mes = "<div class='alert alert-success'> Can't Found This ID</div>";
            redrect($mes, "Dashbord");
        }
    } elseif ($action == 'update') { // if get user from update click



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $idname = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            $password = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);

            $formErrors = array();

            if (strlen($name) < 4) {
                $formErrors[] = "your name is short";
            }
            if (strlen($name) > 10) {
                $formErrors[] = "your name is tall ";
            }
            if (empty($name)) {
                $formErrors[] = "cant be name empty";
            }
            if (empty($email)) {
                $formErrors[] = "cant be email empty";
            }
            if (empty($fullname)) {
                $formErrors[] = "cant be fullname empty";
            }

            if (strlen($fullname) > 20) {
                $formErrors[] = "your fullname is tall ";
            }



            // cheak if found any errors in array

            if (empty($formErrors)) {

                $stam = $con->prepare("UPDATE admins SET Fullname = ? ,Username = ? , Email = ? , Pass= ? WHERE ID = ?");
                $stam->execute(array($fullname, $name, $email, $password, $idname));
                $count = $stam->rowCount();

                if ($count > 0) {
                    $mes = "<div class='alert alert-success'> The Data Updates</div>";
                    redrect($mes, "Add Member", 2, "memper.php?action=Edit Profil&ID=$idname");

                } else {
                    $mes = "<div class='alert alert-warning'>NoUpdate</div>";
                    redrect($mes, "Add Member", 2, "memper.php?action=Edit Profil&ID=$idname");
                }



            } else {

                foreach ($formErrors as $error) {
                    $mes = "<div class='alert alert-warning'>$error</div>";
                    redrect($mes, "Add Member", 2, "memper.php?action=Edit Profil&ID=$idname");
                }

            }


        } else {
            $mes = "<div class='alert alert-danger'>You Can't Acsees In This Page</div>";
            redrect($mes, "Dashbord");

        }

    }

} else {
    header('Location:index.php');
    exit();
}
include $ft . "footer.php";

