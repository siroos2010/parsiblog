<?php
require_once  'admin/includes/functions.php';

if(count($_GET) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $result = getPosts($_GET['id']);
    $post = mysqli_fetch_assoc($result);
    if(is_null($post)) {
        header('Location: index.php');
        exit;
    }

    calculateCountViews($post['id']);
} else {
    header('Location: index.php');
    exit;
}

if(count($_POST)) {
    storeComment($_POST);
}

$comments = getComments($_GET['id']);
//$childrenComment = getComments($_GET['id'], false);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>مقاله انرژی های تجدید پذیر</title>

    <link rel="stylesheet" href="./node_modules/bootstrap-v4-rtl/dist/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="./style.css" />
</head>
<body>
<?php
require_once 'front_layouts/header.php'
?>
    <main class="rtl mt-3">
        <div class="container">
            <div class="row">
              <div class="col-12 col-md-8 col-lg-5 d-flex d-md-block justify-content-center">
                <div class="d-flex justify-content-center single-img mb-4">
                  <img src="./images/p1.jpg" alt="file">
                </div>
              </div>
              <div class="col-12 col-md-4 col-lg-7">
                <h1 class="o-font-md font-weight-bold"><?= $post['title'] ?></h1>
                کد مقاله:<span class="text-muted d-block mb-2"> SF-564</span>
                <strong>قیمت محصول: </strong><span class="d-block text-success">25,000 تومان</span>
              </div>
            </div>
            <hr>
            <article class="o-font-sm text-dark text-justify">
              <p><?= nl2br($post['description']) ?></p>
              
              <hr>
              <h5 class="mb-3">نظرات</h5>
              <form method="post"  action="">
                  <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                  <input type="hidden" name="parent_id" value="0">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="inputName4">نام</label>
                    <input type="text"  name="name" class="form-control" id="inputName4">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="inputPhone4">شماره همراه</label>
                    <input type="text" name="mobile" class="form-control" id="inputPhone4">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputEmail4">ایمیل</label>
                    <input type="email" name="email" class="form-control" id="inputEmail4">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="inputPhone4">توضیحات</label>
                      <textarea name="description"  class="form-control" ></textarea>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">ارسال نظر</button>
              </form>

                <?php
                while ($comment = mysqli_fetch_assoc($comments)) {
                    ?>
                    <div class="alert alert-info" role="alert">
                        <p>نام: <?= $comment['name'] ?></p>
                        <p>موبایل: <?= $comment['mobile'] ?></p>
                        <p>ایمیل: <?= $comment['email'] ?></p>
                        <p>متن پیام: <?= nl2br($comment['description']) ?></p>
                        <button data-comment_id="<?= $comment['id'] ?>" class="btn btn-primary btn_reply_cm" id="btn_reply_cm_<?= $comment['id'] ?>">پاسخ</button>
                        <form method="post"  action="" class="reply_form" id="reply_form_<?= $comment['id'] ?>">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputName4">نام</label>
                                    <input type="text"  name="name" class="form-control" id="inputName4">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputPhone4">شماره همراه</label>
                                    <input type="text" name="mobile" class="form-control" id="inputPhone4">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">ایمیل</label>
                                    <input type="email" name="email" class="form-control" id="inputEmail4">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputPhone4">توضیحات</label>
                                    <textarea name="description"  class="form-control" ></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">ارسال نظر</button>
                        </form>

                        <?php
                        $childrenComment = getComments($post['id'], $comment['id']);
                        while($childComment = mysqli_fetch_assoc($childrenComment)) {
                            ?>
                            <div class="alert alert-warning" role="alert">
                                <p>نام: <?= $childComment['name'] ?></p>
                                <p>موبایل: <?= $childComment['mobile'] ?></p>
                                <p>ایمیل: <?= $childComment['email'] ?></p>
                                <p>متن پیام: <?= nl2br($childComment['description']) ?></p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php
                }

                ?>
            </article>
        </div>
    </main>


<?php
require_once 'front_layouts/footer.php'
?>

    
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./popper.min.js"></script>
    <script src="./node_modules/bootstrap-v4-rtl/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('form.reply_form').hide();

        $('button.btn_reply_cm').on('click', function () {
            var comment_id = $(this).attr('data-comment_id');
            // console.log(comment_id);
            $('form#reply_form_' + comment_id).toggle();
        });

    });
</script>
</body>
</html>