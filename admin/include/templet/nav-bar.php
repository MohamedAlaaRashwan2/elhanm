<?php
?>

<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
                aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashbord.php">
                Home
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav">

                <li><a href="items.php">
                        Items
                    </a></li>
                <li><a href="memper.php">
                        Members
                    </a></li>
                <li><a href="cpon.php">
                        Coupon
                    </a></li>



            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        Profil <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="memper.php?action=Edit Profil&ID=<?php echo $_SESSION['ID'] ?>">
                                Edit
                            </a></li>
                        <li><a href="memper.php?action=Sttings">
                                Sttings
                            </a></li>
                        <li><a href="logout.php">
                                <?php echo lang("تسجيل خروج") ?>
                            </a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>