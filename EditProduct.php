<?php 
use App\Config\Database;
use App\Product\Product;
include_once("src/Config/DBInterface.php");
include_once("src/Config/Database.php");
include_once("src/Product/Product.php");
include_once("src/Category/Category.php");


$db = new Database();
$product = new Product($db);
$id = $_GET['id'];
$productData = $product->getById($id);

$category = new Category($db);
$categoryList = $category->getAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link rel="stylesheet" href="style.css">
    <script src="js/validate.lib.js"></script>

    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="modal-body">

        <div class="form-container">
            <span id="success-message"
                style="display:flex; color:green; font-weight:bold; padding-left:325px; padding-bottom: 20px;"></span>
            <form action="" method="post" id="addProduct" enctype="multipart/form-data" class="form">

                <div class="form-group edit-product">
                    <div class="edit-label">
                        <label for="category" class=" ">Category</label>
                    </div>
                    <div class="edit-field">
                        <select onchange="subCategoryList(this)" class="form-control" id="category" name="category">
                            <option value="">Select category</option>
                            <?php 
                               foreach($categoryList as $category) {
                            ?>
                            <option value="<?php echo $category['id'] ?>"
                                <?php if($category['id'] == $productData[0]['category_id']) echo "selected";?>>
                                <?php echo $category['title'] ?>
                            </option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group edit-product">
                    <div class="edit-label">
                        <label for="sub-category" class="">Sub Category</label>
                    </div>
                    <div class="edit-field" id='subCatList'>

                        <select class="form-control" id="sub_category" name="sub_category">
                            <option value="<?php echo $productData[0]['sub_cat_id'] ?>">
                                <?php echo $productData[0]['subCatTitle'] ?>
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group edit-product">

                    <div class="edit-label">
                        <label for="product" class=" ">Product
                            Name</label>
                    </div>
                    <div class="edit-field">

                        <input type="text" class="form-control" id="product" name="product"
                            value="<?php echo $productData[0]['product_name']?>">
                    </div>
                </div>

                <div class="form-group edit-product">
                    <div class="edit-label">


                        <label for="catalog" class="">Product Catalog</label>
                    </div>
                    <div class="edit-field">
                        <input type="file" class="form-control" id="catalog" name="catalog" value="">

                        <input type="hidden" class="form-control" id="id" name="id"
                            value="<?php echo $productData[0]['id']?>">

                    </div>
                </div>


                <?php 
                $counter = 1;
                foreach($productData as $product) {
                   
                ?>
                <div class="form-group edit-product" id="image_container_<?php echo $product['imageId'];?>">
                    <div class="edit-label">


                        <label for="catalog" class="">Image</label>
                    </div>
                    <div class="edit-field">
                        <input type="file" class="form-control" id="image_<?php echo $product['imageId'];?>"
                            name="image_addmore[]" value="">

                    </div>
                    <div class="col-lg-3 imgIcon">
                        <div class="imgAddmore">
                            <img width="50" id="addmore" height="50" src="<?php echo $product['image']?>" alt="Add">
                        </div>
                        <?php if($counter !== 1 ) {  ?>
                        <img width="30" onclick="deleteImage(<?php echo $product['imageId'];?>)" id="addmore"
                            height="30" src="img/remove.png" alt="remove">
                        <?php  }?>
                    </div>
                </div>
                <?php $counter++; } ?>

                <div class="form-group addmoreImage" id="addmoreImage">
                </div>
                <div class="form-group row" style="text-align:center;">
                    <button type="submit" class="btn btn-primary" name="save" id="save"> Save</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>