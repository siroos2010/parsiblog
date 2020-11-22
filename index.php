<?php
require_once  'admin/includes/functions.php';
$posts = getPostsForIndex(4, 'id', 'DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فروشگاه</title>

    <link rel="stylesheet" href="./node_modules/bootstrap-v4-rtl/dist/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="./style.css" />
</head>
<body>
<?php
require_once 'front_layouts/header.php'
?>
    <main class="rtl mt-3">
        <div class="d-flex justify-content-center flex-wrap">
            <?php
            while ($post = mysqli_fetch_assoc($posts)) {
                require 'front_layouts/post_card.php';
            }
            ?>
        </div>
    </main>

<?php
require_once 'front_layouts/footer.php'
?>

    
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./popper.min.js"></script>
    <script src="./node_modules/bootstrap-v4-rtl/dist/js/bootstrap.min.js"></script>
</body>
</html>