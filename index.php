<?php 
use App\Config\Database;
use App\Product\Product;

include_once("src/Config/DBInterface.php");
include_once("src/Config/Database.php");
include_once("src/Product/Product.php");
include_once("src/Category/Category.php");

$db = new Database();
$product = new Product($db); 
$products = $product->getAll();
$category = new Category($db);
$categoryList = $category->getAll();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link rel="stylesheet" href="style.css">
    <script src="js/validate.lib.js"></script>

    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <center> <button style="margin-top:20px" type="button" class="btn btn-primary" data-toggle="modal"
            data-target="#exampleModal">
            Add Product</button></center>

    <div class="container">
        <div class="productListing">

            <div style="background: gray;">
                <span class="itemListing heading">Category</span>
                <span class="itemListing heading">Sub category</span>
                <span class="itemListing heading">Product</span>
                <span class="itemListing heading">Catalog</span>
                <span class="itemListing heading">Image</span>

                <span class="itemListing heading">Action</span>
            </div>
            <?php
            $count = 1;
            foreach($products as $product) {?>
            <?php $class = ($count % 2 == 0) ? 'even' : 'odd'; ?>

            <div class="<?php echo $class; ?>" id="product_container_<?php echo $product['id']; ?>">
                <span class="itemListing"><?php echo $product['categoryTitle'];?></span>
                <span class="itemListing"><?php echo $product['subCatTitle'];?></span>
                <span class="itemListing"> <?php echo $product['product_name'];?> </span>
                <span class="itemListing"><a href="downloadPdf.php?p_id=<?php echo $product['id']; ?>">View </a></span>
                <span class="itemListing"><img width="100" height="100" src="<?php echo $product['image']; ?>" /></span>
                <span class="itemListing"><a href="#" class=""
                        onclick="window.open('EditProduct.php?id='+<?php echo $product['id'];?>, 'popup', 'location=0,width=750,height=650,left=500,top=55'); return false;"><img
                            src="img/edit.png" width="25" height="25" alt="Edit" /> </a>|
                    <a href="#" onclick=(deleteProduct(<?php echo $product['id'];?>))><img src="img/remove.png"
                            width="25" height="25" alt="Delete" /></a></span>
            </div>
            <?php $count++; } if(count($products) == 0) {?>
            <div style="text-align:center; margin: 10px 0 10px 0px; font-weight: bold; ">No product found</div>
            <?php } ?>

        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-container">
                        <form action="" method="post" id="addProduct" enctype="multipart/form-data" class="form">

                            <div class="form-group row">

                                <div class="col-lg-5">

                                    <label for="category" class=" ">Category</label>
                                </div>
                                <div class="col-lg-7">
                                    <select onchange="subCategoryList(this)" class="form-control" id="category"
                                        name="category">
                                        <option value="">Select category</option>
                                        <?php 
                                        foreach($categoryList as $category) {
                                         ?>
                                        <option value="<?php echo $category['id'] ?>"><?php echo $category['title'] ?>
                                        </option>
                                        <?php  } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-5">
                                    <label for="sub-category" class=" ">Sub
                                        Category</label>
                                </div>
                                <div class="col-lg-7" id='subCatList'>
                                    <select class="form-control" id="sub_category" name="sub_category">
                                        <option value="">Select sub category</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-5">
                                    <label for="product" class=" ">Product
                                        Name</label>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" id="product" name="product" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-5">

                                    <label for="catalog" class="">Product Catalog</label>
                                </div>
                                <div class="col-lg-7">
                                    <input type="file" class="form-control" id="catalog" name="catalog" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-5">
                                    <label for="catalog" class="">Image</label>
                                </div>
                                <div class="col-lg-4 imgIcon">
                                    <input type="file" class="form-control" id="image" name="image_addmore[]" value="">

                                </div>
                                <div class="col-lg-3 imgIcon">
                                    <div class="imgAddmore">
                                        <img width="30" id="addmore" height="30" src="img/add.png" alt="Add">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row addmoreImage" id="addmoreImage">
                            </div>
                            <div class="form-group row" style="text-align:center;">
                                <button type="submit" class="btn btn-primary" name="save" id="save"> Save</button>
                                <button type="submit" class="btn btn-dark" name="save-addnew" id="saveAddNew">Save & Add
                                    new
                                    item</button>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="popup-wrapper" id="popup">
        <div class="popup-container">
            <center>
                <iframe id="editProductIframe" allowfullscreen="true" height="500" src="EditProduct.php"
                    width="900"></iframe>
            </center>
            <a class="popup-close" href="#closed">X</a>
        </div>
    </div>
    <script>
    function editProduct(id) {
        var url = "EditProduct.php?id=" + id;
        document.getElementById('editProductIframe').src = url;

    }
    $('#myModal').on('shown.bs.modal', function() {
        $('#myInput').trigger('focus')
    });
    </script>


</body>

</html>