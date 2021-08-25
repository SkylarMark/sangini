/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const connection = require('../config/db');

/**
 * Class Containing Fucntions
*/
class Cart {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class Cart Activated');
  }

  /**
 * Items In Cart
 * @param {string} username Username of User
 * @param {Function} callback
 */
  cartView(username, callback) {
    connection.query(`
        SELECT 
        product_list_id,
        username,
        cart.quantity,
        amount,
        product_master.product_name as Product,
        cloth_master.cloth_name as Cloth,
        color_master.color_name as Color,
        color_master.color_code as Color_Code,
        size_master.size_code as Size
        FROM cart 
        JOIN product_list ON product_list.id = cart.product_list_id
        JOIN product_master ON product_list.product_id = product_master.product_id 
        JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id 
        JOIN color_master ON product_list.color_id = color_master.color_id 
        JOIN size_master ON product_list.size_id = size_master.size_id
        WHERE username = ? ORDER BY cart_id DESC`
    , [username], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.length > 0) {
          callback(null, results);
        } else {
          callback('true', 'Cart is Empty');
        }
      }
    });
  }

  /**
   * Add Items In Cart
   * @param {int} productListId Username of User
   * @param {string} username Username of User
   * @param {int} quantity Username of User
   * @param {int} rate Username of User
   * @param {Function} callback
   */
  cartAdd(productListId, username, quantity, rate, callback) {
    connection.query('INSERT INTO `cart`(`product_list_id`, `username`, `quantity`, `amount`) VALUES (?,?,?,?)', [productListId, username, quantity, rate], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows != null) {
          callback(null, results);
        } else {
          callback('true', 'Order Already Exists');
        }
      }
    });
  }

  /**
   * Update Items In Cart
   * @param {int} productListId Username of User
   * @param {string} username Username of User
   * @param {int} quantity Username of User
   * @param {int} rate Username of User
   * @param {Function} callback
   */
  cartUpdate(productListId, username, quantity, rate, callback) {
    connection.query('UPDATE `cart` SET `product_list_id`= ?,`quantity`= ?, `amount`= ? WHERE username = ? && product_list_id = ?', [productListId, quantity, rate, username, productListId], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows != null) {
          callback(null, results);
        } else {
          callback('true', 'Order Already Exists');
        }
      }
    });
  }

  /**
   * Update Items In Cart
   * @param {int} productListId Username of User
   * @param {string} username Username of User
   * @param {Function} callback
  */
  cartdelete(productListId, username, callback) {
    connection.query('DELETE FROM `cart` WHERE `cart`.`product_list_id` = ? && username = ?', [productListId, username], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows != null) {
          callback(null, results);
        } else {
          callback('true', 'Data Does not Exists');
        }
      }
    });
  }

  /**
   * Update Items In Cart
   * @param {int} productListId Username of User
   * @param {Function} callback
   * Calculate Rate (Product_list.price * quantity = rate)
  */
  getRate(productListId, callback) {
    connection.query(`SELECT * FROM product_list WHERE product_list.id = ?`, [productListId], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.length > 0) {
          let result = (JSON.stringify(results[0]));
          result = JSON.parse(result);
          callback(null, result.price);
        } else {
          callback('true', 'No Product Found');
        }
      }
    });
  }
}

module.exports = Cart;
