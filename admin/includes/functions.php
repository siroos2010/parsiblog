<?php
require_once 'db.php';


function storePost($title, $short_description, $description, $category_id, $pic) {
    global $link;

    $extension = explode('.', $pic['name']);
    $extension = end($extension);

    if($pic) {
//        var_dump(is_dir('../../uploads')); die;
        $file_name = time() . '.' . $extension;
        $target = '../../uploads/' .  $file_name;

        if (move_uploaded_file($pic['tmp_name'], $target)) {
            $is_uploaded = true;
        } else {
            $is_uploaded = false;
        }
    }

    $pic_url = $is_uploaded ? $file_name : '';
    $query = "INSERT INTO `posts`  VALUES
     (NULL, '{$category_id}', '{$title}', '{$short_description}', '{$description}', '0', '{$pic_url}', '" . date('Y-m-d H:i:s') . "')";

    $result = mysqli_query($link, $query);
}

function updatePost($id, $title, $short_description, $description, $category_id) {
    global $link;
    $query = "UPDATE `posts` SET
        `category_id` = '{$category_id}', 
        `title` = '{$title}', 
        `short_description` = '{$short_description}',
        `description` = '{$description}'
         WHERE `id` = '{$id}'";

    $result = mysqli_query($link, $query);
}

function getPosts($post_id = null, $limit = null, $orderBy = null, $orderType = 'ASC') {
    global $link;

    $query = "SELECT * FROM `posts`";
    if(!is_null($post_id)) {
        $query .= " WHERE `id`='{$post_id}'";
    }
    if(!is_null($orderBy)) {
        $query .= " ORDER BY `" . $orderBy . "` " . $orderType;
    }
    if(!is_null($limit)){
        $query .= " LIMIT " . $limit;
    }

//    echo $query; exit;
    $result = mysqli_query($link, $query);
    return $result;
}

function deletePost($post_id) {
    global $link;

    $query = "DELETE FROM `posts` WHERE `id`='{$post_id}'";
    $result = mysqli_query($link, $query);
    return $result;
}

function storeCategory($title, $show_at_index) {
    global $link;
    $query = "INSERT INTO `categories` VALUES
     (NULL,  '{$title}', '{$show_at_index}', '" . date('Y-m-d H:i:s') . "')";

    $result = mysqli_query($link, $query);
}

function getCategories($category_id = null) {
    global $link;
    $query = "SELECT * FROM `categories`";
    if(! is_null($category_id)) {
        $query .= " WHERE `id`='{$category_id}'";
    }
    $result = mysqli_query($link, $query);
    return $result;
}


function deleteCategory($category_id) {
    global $link;

    $query = "DELETE FROM `categories` WHERE `id`='{$category_id}'";
    $result = mysqli_query($link, $query);
    return $result;
}


function updateCategory($id, $title, $show_at_index) {
    global $link;
    $query = "UPDATE `categories` SET 
        `title` = '{$title}', 
        `show_at_index` = '{$show_at_index}'
         WHERE `id` = '{$id}'";

    $result = mysqli_query($link, $query);
}

//function calculateCountViews($post_id) {
//
//    if(isset($_COOKIE['viewed_post_' . $post_id])) {
//        return;
//    }
//
//    global $link;
//    $query = "UPDATE `posts` SET
//        `count_views` = `count_views` + 1
//         WHERE `id` = '{$post_id}'";
//
//    $result = mysqli_query($link, $query);
//
//    setcookie('viewed_post_' . $post_id, 1, time() +  3 * 24 * 3600);
//}

function calculateCountViews($post_id) {
    $viewed_pages = [];
    if(isset($_COOKIE['viewed_pages'])) {
        $viewed_pages = json_decode($_COOKIE['viewed_pages'], true);
    }

    if(!in_array($post_id, $viewed_pages)) {

        global $link;
        $query = "UPDATE `posts` SET 
        `count_views` = `count_views` + 1
         WHERE `id` = '{$post_id}'";

        $result = mysqli_query($link, $query);
        $viewed_pages[] = $post_id;
        setcookie('viewed_pages', json_encode($viewed_pages), time() + 3 * 24 * 3600);
    }
}


function getPostsForIndex( $limit = null, $orderBy = null, $orderType = 'ASC') {
    global $link;

    $query = "SELECT P.* FROM `posts` P 
                JOIN `categories` C 
                ON P.category_id=C.id
                WHERE
                    C.show_at_index = '1'
";

    if(!is_null($orderBy)) {
        $query .= " ORDER BY `" . $orderBy . "` " . $orderType;
    }
    if(!is_null($limit)){
        $query .= " LIMIT " . $limit;
    }

//    echo $query; exit;
    $result = mysqli_query($link, $query);
    return $result;
}


function storeComment($_post) {
    global $link;
    $query = "INSERT INTO `comments` VALUES
     (NULL,  '{$_post['post_id']}','{$_post['parent_id']}','{$_post['name']}','{$_post['mobile']}','{$_post['email']}','{$_post['description']}', '0', '" . date('Y-m-d H:i:s') . "')";

    $result = mysqli_query($link, $query);
}

function getComments($post_id, $parent_id = NULL) {
    global $link;
    $query = "SELECT * FROM `comments` WHERE is_confirm = '1' AND `post_id` = '{$post_id}'";
    if(is_null($parent_id)) {
        $query .= " AND `parent_id` = '0'";
    } else {
        $query .= " AND `parent_id` = '{$parent_id}' ";
    }
    return mysqli_query($link, $query);
}
//
//function getComments($post_id, $is_parent = true) {
//    global $link;
//    if($is_parent) {
//        $query = "SELECT * FROM `comments` WHERE is_confirm = '1' AND `post_id` = '{$post_id}' AND `parent_id` = '0'";
//    } else {
//        $query = "SELECT * FROM `comments` WHERE is_confirm = '1' AND `post_id` = '{$post_id}'
//            AND `parent_id` IN (SELECT `id` FROM `comments` WHERE is_confirm = '1' AND `post_id` = '{$post_id}' AND `parent_id` = '0') ";
//    }
//    return mysqli_query($link, $query);
//}
