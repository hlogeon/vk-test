<?php include(__DIR__.'/layout/header.php');?>











    <div class="content-wrapper">
        <div class="item-container">
            <div class="container">
                <div class="col-md-12">
                    <div class="product col-md-3 service-image-left">

                        <center>
                            <img id="item-display" src="<?= $product['image'] ?>" alt="">
                        </center>
                    </div>

                    <div class="container service1-items col-sm-2 col-md-2 pull-left">
                        <center>
                            <a id="item-1" class="service1-item">
                                <img src="<?= $product['image'] ?>" alt="">
                            </a>
                        </center>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="product-title"><?= $product['title'] ?></div>
                    <div class="product-rating"><i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star-o"></i> </div>
                    <hr>
                    <div class="product-price">$ <?= $product['price'] ?></div>
                    <div class="product-stock">In Stock</div>
                    <hr>
                    <div class="btn-group cart">
                        <a href="/edit/<?= $product['id'] ?>">
                            <button type="button" class="btn btn-success">
                                Редактировать
                            </button>
                        </a>
                    </div>
                    <div class="btn-group wishlist">
                        <a href="/delete/<?= $product['id'] ?>">
                            <button type="button" class="btn btn-danger">
                                Удалить
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-md-12 product-info">
                <ul id="myTab" class="nav nav-tabs nav_tabs">
                    <li class="active"><a href="#service-one" data-toggle="tab">DESCRIPTION</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in active" id="service-one">

                        <section class="container product-info">
                            <?= $product['description'] ?>
                        </section>

                    </div>
                </div>
                </div>
                <hr>
            </div>
        </div>
    </div>


<?php include(__DIR__.'/layout/footer.php'); ?>