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
        <a id="id-desc" href="/ajax-list/1/?sort=id&order=desc"><button type="button" class="btn btn-lg btn-success">ID desc</button></a>
        <a id="id-asc" href="/ajax-list/1/?sort=id&order=asc"><button type="button" class="btn btn-lg btn-info">ID asc</button></a>
        <a id="price-desc" href="/ajax-list/1/?sort=price&order=desc"><button type="button" class="btn btn-lg btn-danger">Цена desc</button></a>
        <a id="price-asc" href="/ajax-list/1/?sort=price&order=asc"><button type="button" class="btn btn-lg btn-warning">Цена asc</button></a>
        <button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">+</button>
        <div style="clear: both;"></div>
        <div id="items">
            <?php include(__DIR__.'/items.php'); ?>
        </div>
    </div>


<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                <?php include(__DIR__.'/add_product_form.php') ?>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>


<?php include(__DIR__.'/layout/footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#id-desc').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('href'));
        });
        $('#id-asc').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('href'));
        });
        $('#price-desc').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('href'));
        });
        $('#price-asc').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('href'));
        });
        $('#prev-page').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('link'));
        });
        $('#next-page').click(function(e){
            e.preventDefault();
//            loader();
            sortRequest($(this).attr('link'));
        });

        function sortRequest(link)
        {
            $.ajax({
                url: link,
                type: 'get'
            }).success(function(response){
                $('#items').html(response);
                $('#prev-page').click(function(e){
                    e.preventDefault();
                    console.log($(this).attr('link'));
                    sortRequest($(this).attr('link'));
                });
                $('#next-page').click(function(e){
                    e.preventDefault();
//                    loader();
                    sortRequest($(this).attr('link'));
                });
            });
        }

        function loader()
        {
            $('#items').html('<div id="preloader_2"><span></span><span></span><span></span><span></span></div>');
        }
    });
</script>