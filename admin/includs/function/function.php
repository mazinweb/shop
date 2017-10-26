<?php
/*
 * the function of title page that echo title page if
 * the variable $pageTitle is in the page
 */
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Defualt';
    }
}

/*
 * home redirect function v2.0
 * home redirect function [ this function accept parameters  ]
 * $error message = echo the error message
 * $seconds = hold the seconds before redirect
 * */
function redirect($msg, $url = null, $seconds = 5)
{
    if ($url == null) {
        $url = 'index.php';
        $link = 'Home Page';

    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Home Page';
        }
    }
    echo $msg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

/*
 * check function v1.0
 * function that check if the data is in the database or not
 * if it in database  send error message to know that
 * else do what you want to do it
 *
 * */
function checker($select, $from, $value)
{
    global $conn;
    $statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}


/*
 * function count v1.0
 * this function count the number of the any items in database
 * and return the numbers to be use
 * $item = the colum from the database that you want to count it
 * $table =  the name of the table that you want to count it
 * */
function countitem($item,$table)
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT($item) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}