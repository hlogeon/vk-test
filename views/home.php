<?php
include(__DIR__.'/layout/header.php'); ?>

<div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="/">Home</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">Products</h3>
      </div>

      <div class="jumbotron">
        <h1>Simple products application</h1>
        <p class="lead">This application is written in procedural style(without using OOP). There are approximately 1191572 records in the database
        but it shows great performance.
        </p>
        <p><a class="btn btn-lg btn-success" href="/list/1" role="button">List products</a></p>
      </div>

      <div class="row marketing">
          <center><h3>Featured products</h3></center>
        <?php foreach($products as $product): ?>
            <div class="col-lg-6">
                <h4><?= $product['title']; ?></h4>
                <p><?= $product['description'] ?></p>
                <button class="btn btn-warning" disabled="disabled"><?= $product['price'] ?></button>
                <a href="/view/<?= $product['id'] ?>">Просмотр</a>
            </div>
        <?php endforeach ?>
      </div>

<?php include(__DIR__.'/layout/footer.php');