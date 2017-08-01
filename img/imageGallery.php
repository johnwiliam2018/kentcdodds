<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$page_title = "Image";
$page_slug = "image";
require ('library/admin_application_top.php');

//$action = (isset($_GET['']))
$sql = "SELECT * FROM " . TABLE_IMAGES . " WHERE `isupload` = 1";
$imagenum = $wpdb->get_results($sql);

require ('views/header.php');
?>

<style>
    .item {
        width: 300px;
        float: left;
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 5px;
        opacity: 0.8;
        margin-bottom: 10px;
    }

    .item:hover {
        opacity: 1;
    }

    .item img {
        display: block;
        width: 100%;
    }

    .item .item-body {
        position: relative;
    }

    .item .actions {
        position: absolute;
        Top: 0px;
        width: 100%;
        padding: 0 8px;
        background: rgba(255, 255, 255, 0.8);
        color: black;
        display: none;
    }

    .item .actions a {
        color: #333;
        font-size: 14px;
    }

    .item:hover .actions {
        display: block;
    }
</style>

<script src="//masonry.desandro.com/masonry.pkgd.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3> Uploaded Images </h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">   
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Image Gallery</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <p>Here are the pinterest style masonry grid of thumbnails of images uploaded.</p>
                            <div id="imgGallery">
                                <?php foreach ($imagenum as $image) : ?> 
                                    <div class="item" data-image-id="<?php echo $image->id; ?>">
                                        <div class="item-body">
                                            <img src="uploads/images/watch/<?php echo $image->image ?>"  alt="image" />

                                            <div class='text-center actions'>
                                                <!--a href="imagee_edit.php?id=<?php echo $image->id; ?>" class="btn btn-sm"><i class="fa fa-edit"></i></a-->

                                                <a href="#" class="btn btn-sm remove-btn"><i class="fa fa-remove"></i></a>

                                                <a class="fancybox" rel="ligthbox" href="uploads/images/pc/<?php echo $image->image ?>"><i class="fa fa-search"></i></a>
                                                <a class="fancybox" rel="ligthbox" href="uploads/images/pc/<?php echo $image->image ?>"><i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div> 
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script>
    $(function () {
        var $container = $('#imgGallery').masonry({
            itemSelector: '.item',
            columnWidth: 310
        });// reveal initial images
        $container.masonryImagesReveal($('#images').find('.item'));

        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });

        $('#imgGallery .item .actions a.remove-btn').click(function () {
            var $item = $(this).parent().parent().parent();

            var id = $item.attr("data-image-id");
            if (!confirm("Are you sure delete selected image?")){
                return false;
            }
            $.ajax({                
                type: "POST",
                url: "image_update.php",
                data: { 'id': id },                   
                success: function()
                {
                      
                    $item.remove();                         
                    $('#imgGallery').masonry('layout');
                }
            });
            // if ajax deleting success, delete itme. 
            
        });
    });

    $.fn.masonryImagesReveal = function ($items) {
        var msnry = this.data('masonry');
        var itemSelector = msnry.options.itemSelector;
        // hide by default
        $items.hide();
        // append to container
        this.append($items);
        $items.imagesLoaded().progress(function (imgLoad, image) {
            // get item
            // image is imagesLoaded class, not <img>, <img> is image.img
            var $item = $(image.img).parents(itemSelector);
            // un-hide item
            $item.show();
            // masonry does its thing
            msnry.appended($item);
        });

        return this;
    };
</script>


<?php
require ('views/footer.php');
