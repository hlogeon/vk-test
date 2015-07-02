<?php
include(__DIR__.'/layout/header.php'); ?>

    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="/">Home</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Products</h3>
    </div>
    <div class="row marketing">
        <center><h3>All products</h3></center>
        <?php include(__DIR__.'/layout/pager.php'); ?>
        <?php foreach($products as $product): ?>
            <div class="col-lg-4">
                <div class="thumbnail" >
                    <h4 style="text-align: center"><?= $product['title']; ?></h4>
                    <hr/>
                    <p><?= substr($product['description'], 0, 250).'...' ?></p>
    <!--                <img src="--><?//= $product['image'] ?><!--?text=--><?//= $product['title'] ?><!--">-->
                    <span class="btn btn-danger" style="cursor: default"><?= $product['price'] ?></span>
                    <a href="/view/<?= $product['id'] ?>" style="float: right"><button class="btn btn-info">Просмотр</button></a>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php include(__DIR__.'/layout/pager.php'); ?>

<?php include(__DIR__.'/layout/footer.php');