<?php

namespace App\Product;

use DateTime;
use Date;
use PDO;
use ErrorException;
use PDOException;
use App\Config\Database;
use Error;
use Exception;

/**
 * This class insert, update delete product in database
 */
class Product
{

   private $dbConn;


   public function __construct($database)
   {
      $this->dbConn = $database;
   }


   /**
    * This funciton insert product into db 
    *
    * @param array $productData
    * @return void
    */
   public function add(array $productData): void
   {
      $this->insert($this->prepareData($productData));
   }

   /**
    * This function prepare product data array and return array
    *
    * @param array $productData
    * @return array
    */
   private function prepareData(array $productData): array
   {
      $catalogPdfContent = [];
      if (!empty($_FILES['catalog']['name'])) {
         $tmpName = $_FILES['catalog']['tmp_name'];
         $fp      = fopen($tmpName, 'r');
         $content = fread($fp, filesize($tmpName));
         $content = addslashes($content);
         fclose($fp);


         //$content = addslashes(file_get_contents($_FILES['catalog']['tmp_name']));
         $catalogPdfContent = ['catalog' => $content];
      }

      $data = [
         'category' => $productData['category'],
         'subcategory' => $productData['sub_category'],
         'product' => $productData['product'],
         'date' => date("Y-m-d h:i:s")
      ];
      return array_merge($data, $catalogPdfContent);
   }

   /**
    * This function insert product data and product images into db
    *
    * @param array $data
    * @return void
    */
   private function insert(array $data): void
   {
      try {
         $stmt = $this->dbConn->getDb()->prepare('
         insert into product
          (category_id, sub_cat_id, product_name, catalog, date)
       values (:category, :subcategory, :product, :date, :catalog)
         ');
         $stmt->execute($data);
         $lastInsertedId = $this->dbConn->lastInsertId();
         $this->addImage($lastInsertedId);
         
      } catch (Exception $e) {
         throw new Exception($e->getMessage());
         
      }
   }


   /**
    * This function update the product details
    *
    * @param array $productData
    * @return void
    */
   public function edit(array $productData): void
   {
      $this->update($this->prepareData($productData), $productData['id']);
   }


   /**
    * This function delete the product and related product images
    *
    * @param integer $productId
    * @return void
    */
   public function delete(int $productId): void
   {
      $sql = "DELETE p, pi FROM product as p inner join product_image as pi on p.id=pi.product_id WHERE p.id=?";
      $stmt = $this->dbConn->getDb()->prepare($sql);
      $stmt->execute([$productId]);
   }

   /**
    * This funciton delete the product image
    *
    * @param integer $id
    * @return void
    */
   public function deleteImage(int $id): void
   {
      $sql = "DELETE FROM product_image WHERE id=?";
      $stmt = $this->dbConn->getDb()->prepare($sql);
      $stmt->execute([$id]);
   }



   /**
    * This function update the product detials into db.
    *
    * @param array $data
    * @param integer $id
    * @return void
    */
   private function update(array $data, int $id): void
   {
      try {
         $catalog = "";
         if (isset($data['catalog'])) {
            $catalog = ", catalog = :catalog";
         }
         $whereClause = ['id' => $id];
         $data = array_merge($data, $whereClause);
         $sql = "update  product set category_id = :category, sub_cat_id = :subcategory, product_name = :product, date = :date $catalog where id = :id";
         $stmt = $this->dbConn->getDb()->prepare($sql);
         $stmt->execute($data);

         $imageIdsForUpdate = $this->prepareDataForImageUpdate($id);

         $this->updateImage($id, $imageIdsForUpdate);

      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
   }

   /**
    * This funciton return array of image ids which required to update 
    *
    * @param integer $id
    * @return array
    */
   private function prepareDataForImageUpdate(int $id) :array 
   {
      $productImageIds = $this->getProductImageId($id);
      if(count($productImageIds) == 0) {
         return [];
      } 

      $imageIds = [];
         foreach ($productImageIds as $id) {
            $imageIds[] = $id['id'];
         }
         return array_combine($imageIds, $_FILES['image_addmore']['name']);
         

   }

   /**
    * This function return the product images id
    *
    * @param integer $productId
    * @return array
    */
   private function getProductImageId(int $productId) :array
   {
      $query = "SELECT id  FROM product_image where product_id='$productId'";
      return $stmt = $this->dbConn->query($query);
   }

   /**
    * This function add the product images into db
    *
    * @param integer $id
    * @return void
    */
   private function addImage(int $id) :void
   {
      try {
      $stmt = $this->dbConn->getDb()->prepare('
         insert into product_image
          (product_id, image, created)
       values (:product_id, :image, :created)
         ');
      $date = date("Y-m-d h:i:s");

      $images = $_FILES['image_addmore'];
      for ($i = 0; $i < count($images['name']); $i++) {

         $image_base64 = base64_encode(file_get_contents($images['tmp_name'][$i]));
         $image = "data:image/jpg;charset=utf8;base64," . $image_base64;
         $data = ['product_id' => $id, 'image' => $image, 'created' => $date];
         $stmt->execute($data);
      }
   } catch (Exception $e) {
      throw new Exception($e->getMessage());

   }
   }

   /**
    * This function product images into db
    *
    * @param [type] $id
    * @param [type] $updateImage
    * @return void
    */
   private function updateImage($id, $updateImage) :void
   {
      $images = $_FILES['image_addmore'];
      for ($i = 0; $i < count($images['name']); $i++) {

         if (!empty($images['name'][$i])) {
            $whereId = array_search($images['name'][$i], $updateImage);

            $image_base64 = base64_encode(file_get_contents($images['tmp_name'][$i]));
            $image = "data:image/jpg;charset=utf8;base64," . $image_base64;

            $sql = "update  product_image set image = :image where id = :id";
            $stmt = $this->dbConn->getDb()->prepare($sql);
            $data = ['image' => $image, 'id' => $whereId];
            $stmt->execute($data);            
            unset($updateImage[$whereId]);
         }
      }
   }


   /**
    * This function return all the products
    *
    * @return void
    */
   public function getAll() :array
   {
      $query = "SELECT p.*, pi.id AS imageId, pi.image, c.title as categoryTitle, c2.title as subCatTitle 
      FROM ( SELECT p.id, p.category_id, p.sub_cat_id, p.product_name, p.catalog, (
       SELECT id FROM product_image WHERE product_id = p.id ORDER BY RAND() LIMIT 1 ) as productImageId FROM product p ) p 
       inner join category as c on c.id=p.category_id
       inner join category as c2 on c2.id=p.sub_cat_id  
       LEFT JOIN product_image pi ON pi.id = p.productImageId";
      return $stmt = $this->dbConn->query($query);
   }


   /**
    * This function return single product by product id
    *
    * @param integer $id
    * @return array
    */
   public function getById(int $id) :array
   {
      $query = "SELECT pi.id as imageId, p.id, p.category_id, p.sub_cat_id, p.product_name, pi.image, c.title as subCatTitle FROM product as p
       inner join category as c on c.id=p.sub_cat_id 
      left join product_image as pi on p.id=pi.product_id where p.id='$id'";
      return $stmt = $this->dbConn->query($query);
   }

   public function getCatalogFile($id) {
      $query = "SELECT p.id, p.catalog FROM product as p  where p.id= :id";
      $whereClause = ['id' => $id];
      return $stmt = $this->dbConn->query($query, $whereClause, 'fetch');


   }
}