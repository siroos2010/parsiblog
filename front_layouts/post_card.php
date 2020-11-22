<div class="card m-2" style="width: 18rem;">
    <img src="<?= !empty($post['pic_url']) ? '/uploads/' . $post['pic_url'] : './images/p1.jpg' ?>" class="card-img-top" alt="store">
    <div class="card-body">
        <h5 class="card-title">
            <a href="single.html" class="nav-link p-0 text-dark"><?= $post['title'] ?></a>
        </h5>
        <p class="card-text text-muted o-font-sm"><?= $post['short_description'] ?></p>
    </div>
    <div class="card-footer">
        <p class="text-success text-center">25,000 تومان</p>
        <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-outline-secondary btn-block">ادامه مطلب</a>
    </div>
</div>