<?php
ob_start();

session_start();

// Cheak If This User Found In Session
if (isset($_SESSION['Admin'])) {
    $title = "Dashbord";

    include "init.php"; ?>

    <?php

    $last_member_count = 6;
    $dates = lastmember("*", "users", "", "ID", $last_member_count);

    $last_item_course = lastmember("*", "item", "", "ID")

        ?>

    <!-- satrt Dashbord page -->

    <div class="container home-stats text-center">
        <h1>Dashbord</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="stat stat-member">
                    <i class="fa-solid fa-user-tie"></i>
                    <div class="info-card">
                        Total Admin Members
                        <a href="memper.php"><span>
                                <?php echo countcol("ID", "admins") ?>
                            </span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat stat-Studint">
                    <i class="fa-solid fa-users"></i>
                    <div class="info-card">
                        Total users

                        <a href="memper.php?action=student">
                            <span>
                                <?php echo countcol("ID", "users") ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat stat-section">
                    <i class="fa-solid fa-carrot"></i>
                    <div class="info-card">
                        Total Items
                        <a href="items.php">
                            <span>
                                <?php echo countcol("ID", "item") ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3">
                <div class="stat stat-Courses">
                    <i class="fa-solid fa-store"></i>
                    <div class="info-card">
                        Total Purchases
                        <a href="purchases.php">
                            <span>
                                <?php echo countcol("ID", "buy_brodect") ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa-solid fa-users"></i>Latest
                        <span class="toggel-info pull-right">
                            <i class="fa fa-minus fa-lg"></i>
                        </span>
                        <?php echo $last_member_count ?> users Sign
                    </div>
                    <div class="panel-body">

                        <ul class="list-unstyled lastest-users">
                            <?php
                            foreach ($dates as $data) {

                                echo "<li>" . $data['first_name'] . " " . $data['last_name'] .
                                    "<a href='memper.php?action=student'>
                                    <span class='btn btn-success pull-right'>
                                    <i class='fa fa-edit'></i>Edit
                                    </a>
                                    </span>"
                                    . "</li>";

                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa-solid fa-graduation-cap"></i>Latest Items
                        <span class="toggel-info pull-right">
                            <i class="fa fa-minus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled lastest-users">
                            <?php
                            foreach ($last_item_course as $item) {

                                echo "<li>" . $item['name_item'] .
                                    "<a href='items.php?action=Edit&ID=$item[ID]'>
                                    <span class='btn btn-success pull-right'>
                                    <i class='fa fa-edit'></i>Edit
                                    </a>
                                    </span>"
                                    . "</li>";

                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- end Dashbord page -->

<?php } else {
    header('Location:index.php');
    exit();
}
include $ft . "footer.php";

ob_end_flush()
    ?>