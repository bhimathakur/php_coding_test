<?php
use App\Config\Database;
use App\Product\Product;
include_once("src/Config/DBInterface.php");
include_once("src/Config/Database.php");
include_once("src/Product/Product.php");
include_once("src/Category/Category.php");


$db = new Database();
$product = new Product($db);

$category = new Category($db);
if(!empty($_POST['id'])) {
    
    $product->edit($_POST);
    echo json_encode("Product updated successfully");
} else {
    if(isset($_POST) && !empty($_POST)) {
        $product->add($_POST);
       echo json_encode("Product added successfully");

    }    
} 

if(isset($_GET['imageId']) && !empty($_GET['imageId']))
{
    $product->deleteImage($_GET['imageId']);
    echo json_encode("Image deleted successfully");
}

if(isset($_GET['productId']) && !empty($_GET['productId']))
{
    $product->delete($_GET['productId']);
    echo json_encode("Product deleted successfully");
}


if(isset($_GET['categoryId']) && !empty($_GET['categoryId']))
{
  $subCategoryList =  $category->prepareSubCategoryListing($_GET['categoryId']);
  echo json_encode($subCategoryList);
}

exit;
?>