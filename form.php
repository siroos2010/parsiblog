<?php 
require_once 'admin/includes/db.php';

if(isset($_POST['search'])){
    $searchQuery = $_POST ['search'];
    $getPosts = mysqli_query($link , "SELECT * FROM `posts` WHERE description LIKE '%$searchQuery%'");

    while ($row = mysqli_fetch_array($getPosts)){
        echo 'Name post : ';
        echo $row ['title'] . "<br>" ;
        echo 'Tozihat Post  : ';
        echo $row['description'] . "<br>" . "<br>" ;
    }

}


?>

