<?php
if(!isset($form_action))
    $form_action = '/add';
?>


<form action="<?= $form_action ?>" method="post">
    <div class="col-md-10">
        <?php if(isset($product)): ?>
            <input type="hidden" value="<?= $product['id'] ?>" name="id" id="id">
        <?php endif; ?>
        <div class="form-group">
            <label for="title">Название</label>
            <input value="<?= isset($product) ? $product['title'] : ''; ?>" type="text" class="form-control" name="title" id="title" placeholder="Гениальный продукт">
        </div>
        <div class="form-group">
            <label for="price">Цена</label>
            <input value="<?= isset($product) ? $product['price'] : ''; ?>" type="number" class="form-control" name="price" id="price" placeholder="127.50" step="any">
        </div>
        <textarea class="form-control" rows="3" placeholder="Описание" name="description"><?= isset($product) ? $product['description'] : ''; ?></textarea>
        <div class="form-group">
            <label for="image">URL изображения</label>
            <input value="<?= isset($product) ? $product['image'] : ''; ?>" type="url" class="form-control" name="image" id="image" placeholder="http://mysite.com/greate_image.jpg">
        </div>
    <button type="submit" class="btn btn-default">Submit</button>
    </div>
</form>