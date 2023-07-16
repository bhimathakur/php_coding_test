<?php

use App\Config\Database;
use App\Product\Product;
include_once("src/Config/DBInterface.php");
include_once("src/Config/Database.php");
include_once("src/Product/Product.php");


$db = new Database();
$product = new Product($db);
//header("Content-length: $size");
if(isset($_GET['p_id']) && !empty($_GET['p_id'])) {

    $cataLog = $product->getCatalogFile($_GET['p_id']);
    if(count($cataLog) !== 0) {
            header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=catalog.pdf");
    echo $cataLog['catalog'];
    
    } else {
        echo "Oops someting went wrong...";

    }
    exit;
}


?>