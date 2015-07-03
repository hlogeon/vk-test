<?php include(__DIR__.'/layout/pager.php'); ?>
<?php foreach($products as $product): ?>
    <div class="col-lg-4">
        <div class="thumbnail" >
            <h4 style="text-align: center"><?= $product['title']; ?></h4>
            <hr/>
            <p><?= substr($product['description'], 0, 250).'...' ?></p>
            <!--                <img src="--><?//= $product['image'] ?><!--?text=--><?//= $product['title'] ?><!--">-->
            <span class="btn btn-primary" style="cursor: default"><?= $product['price'] ?></span>
            <a href="/view/<?= $product['id'] ?>" style="float: right"><button class="btn btn-info">Просмотр</button></a>
            <a href="/delete/<?= $product['id'] ?>" style="float: right"><button class="btn btn-danger">Удалить</button></a>
            <div class="clearfix"></div>
        </div>
    </div>
<?php endforeach ?>