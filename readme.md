### `Project Details`

Andorid App : Flutter
Website : CodeIgniter
API : Node JS 

Query to Bring Product List

SELECT id, product_master.product_name as Product, cloth_master.cloth_name as Cloth, color_master.color_name as Color, size_master.size_code as Size, product_list.price 
FROM product_list 
JOIN product_master ON product_list.product_id = product_master.product_id 
JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id 
JOIN color_master ON product_list.color_id = color_master.color_id 
JOIN size_master ON product_list.size_id = size_master.size_id

-----------------------------------------------

Query to Bring Cart Deatails

SELECT 
product_list_id,
username,
quantity,
rate,
product_master.product_name as Product,
cloth_master.cloth_name as Cloth,
color_master.color_name as Color,
color_master.color_code as Color_Code,
size_master.size_code as Size
FROM `cart` 
JOIN `product_list` ON product_list.id = cart.product_list_id
JOIN product_master ON product_list.product_id = product_master.product_id 
JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id 
JOIN color_master ON product_list.color_id = color_master.color_id 
JOIN size_master ON product_list.size_id = size_master.size_id

----------------------------------------------

CREATE TRIGGER `order_place` AFTER INSERT ON `order_descriptions`
 FOR EACH ROW UPDATE `order_descriptions` SET order_amount = (SELECT cart.amount FROM `cart` WHERE order_descriptions.product_list_id = cart.product_list_id)

CREATE TRIGGER `order_place_quantity` AFTER INSERT ON `order_descriptions`
 FOR EACH ROW UPDATE `order_descriptions` SET order_descriptions.quantity = (SELECT cart.quantity FROM `cart` WHERE order_descriptions.product_list_id = cart.product_list_id)


// Insert INTO WHEN Order Complete 

INSERT INTO `orders`(`order_id`, `username`, `total_amount` ) VALUES ( (SELECT order_descriptions.order_id FROM `order_descriptions` WHERE username = 'Skylar' GROUP BY order_id), 'Skylar', (SELECT SUM(order_amount) order_amount FROM order_descriptions WHERE order_id = 'username.somecode'))

----------------------------------------------

// Select Query -> Order Table for Specific User

SELECT 
orders.order_id as OrderID,
orders.username,
order_descriptions.quantity as Quantity,
order_descriptions.order_amount as Amount,
product_master.product_name as Product,
cloth_master.cloth_name as Cloth,
color_master.color_name as Color,
color_master.color_code as Color_Code,
size_master.size_code as Size 
FROM `orders` 
JOIN `order_descriptions` ON orders.username = order_descriptions.username 
JOIN `product_list` ON product_list.id = order_descriptions.product_list_id 
JOIN product_master ON product_list.product_id = product_master.product_id 
JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id 
JOIN color_master ON product_list.color_id = color_master.color_id 
JOIN size_master ON product_list.size_id = size_master.size_id 
WHERE username = 'Skylar'
ORDER BY `order_add_timestamp` 
ASC LIMIT 0,10

------------------------------------------------------