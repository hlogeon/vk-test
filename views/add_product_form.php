<form action="/add" method="post">
    <div class="col-md-10">
        <div class="form-group">
            <label for="title">Название</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Гениальный продукт">
        </div>
        <div class="form-group">
            <label for="price">Цена</label>
            <input type="number" class="form-control" name="price" id="price" placeholder="127.50" step="any">
        </div>
        <textarea class="form-control" rows="3" placeholder="Описание" name="description"></textarea>
        <div class="form-group">
            <label for="image">URL изображения</label>
            <input type="url" class="form-control" name="image" id="image" placeholder="http://mysite.com/greate_image.jpg">
        </div>
    <button type="submit" class="btn btn-default">Submit</button>
    </div>
</form>