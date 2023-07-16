DELIMITER $$

CREATE PROCEDURE Product_Order_Segregate(IN repeatConter int)
BEGIN
    DECLARE productid INT DEFAULT 1;
	DECLARE counter INT DEFAULT 1;
	
    
    REPEAT
		select product_id into productid from product_order where data_transfer = 0 limit 1;
		SET @SQL = CONCAT('create TABLE product_order_',productid, ' like product_order');
		
		PREPARE stmt FROM @SQL;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;

		SET @SQL = CONCAT('insert into product_order_', productid, ' select * from product_order where product_id =', productid, ' and data_transfer =0');
		PREPARE stmt FROM @SQL;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SET @SQL = CONCAT('update product_order set data_transfer = 1 where product_id = ',productid,' and data_transfer = 0');
		PREPARE stmt FROM @SQL;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
        SET counter = counter + 1;
    UNTIL counter > repeatConter
    END REPEAT;
    
END$$

DELIMITER ;