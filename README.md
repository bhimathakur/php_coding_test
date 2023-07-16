# Programming Exercise - PHP test add/edit/delete products 
Written in the PHP programming language, implemented the code for add new product update existing product and delete. Implemented client side validation, Can upload multiple images for product

## Description
Written in the PHP programming language, implemented the code for add new product update existing product and delete. Implemented client side validation, Can upload multiple images for product

## Getting Started
### Dependencies
 
 * Apache Server (PHP version 7.4)
 * MySQL Server
 
### Installing
 * Download code clone
 
### Executing program 
 
 * Execute the SQL query in MySQL from php_coding_test.sql file to setup the database. 
 
 * Run command in cmd(if composer is not installed then install composer): composer install 
 
### MySql Test
  * Created sotred procedure to segrigate the product_order table. Create new table with product_id based and copy date in newly created table
  * Please run the storedProcedure.sql file new SQL this file will create the Product_Order_Segregate PROCEDURE.
  * Run stored procedure in: CALL Product_Order_Segregate(5). Procedure accept one input parameter this is for how many products product_order table we want to ceate.
