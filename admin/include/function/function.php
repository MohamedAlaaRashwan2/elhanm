<?php
//fanction v.01
// use function to title auto for page
function getTitle()
{
    global $title;

    if (isset($title)) {

        echo $title;

    } else {
        echo "admin";
    }

}

// use function to redrect the home page if found error 
//fanction v.01

function redrect($mes, $namePage, $sec = 2, $url = "index.php")
{
    echo "<div class='container'>";

    echo $mes;
    echo "<div class='alert alert-info'>You While Be Redrected To $namePage After " . $sec . " Secounds </div>";
    echo "</div>";


    header("refresh:$sec ;url=$url");
    exit();

}

//fanction v.01
// cheak the database if found this excite
function cheakName($excite, $table, $name)
{

    global $con;

    $stamt = $con->prepare("SELECT $excite FROM $table WHERE $excite=?");

    $stamt->execute(array($name));

    $rowCont = $stamt->rowCount();

    return $rowCont;


}

// function to count the any thing in database


function countcol($item, $table)
{

    global $con;

    $stam = $con->prepare("SELECT COUNT($item) FROM $table");

    $stam->execute();

    return $stam->fetchColumn();
}



// function to get all date for last member 

function lastmember($selctor, $table, $status = null, $order, $num = 5)
{

    global $con;

    $stam = $con->prepare("SELECT $selctor FROM $table $status ORDER BY $order DESC LIMIT $num");

    $stam->execute();

    $date = $stam->fetchAll();

    return $date;
}
