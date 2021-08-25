/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const connection = require('../../config/db');

/**
 * Class Containing Fucntions
 */
class AndroidLogin {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class Android Activated');
  }

  /**
  * Make a Log of UserLogin
  * @param {string} username Username of User
  * @param {float} latitude Username of User
  * @param {float} longitude Username of User
  * @param {string} os Username of User
  * @param {Function} callback
  */
  loginlog(username, latitude, longitude, os, callback) {
    connection.query('INSERT INTO `login_log`(`username`, `current_status`, `latitude`, `longitude`, `os`, `role`) VALUES (?, 1, ?, ?, ?, (SELECT role from user_login WHERE username = ?))', [username, latitude, longitude, os, username], function(error, result, extra) {
      if (error) {
        callback(true, error['code']);
      } else {
        if (result.insertId > 0) {
          callback(false, result);
        } else {
          callback(true, 'Login Not Logged');
        }
      }
    });
  }

  /**
  * All Products
  * @param {Function} callback
  */
  getProducts(callback) {
    connection.query('SELECT * FROM `product_master`', function(error, result, extra) {
      if (error) {
        callback(true, error['code']);
      } else {
        if (result.length > 0) {
          callback(false, result);
        } else {
          callback(true, 'Products Cannot Be Found ! Contact Adminstrator');
        }
      }
    });
  }

  /**
  * Get Specific Product Details
  * @param {string} productName
  * @param {float} type
  * @param {float} clothName
  * @param {string} sizeCode
  * @param {string} show
  * @param {string} max
  * @param {Function} callback
  */
  getProductDetails(productName, type, clothName, sizeCode, show, max, callback) {
    if (type == 'cloth') {
      connection.query(`SELECT product_list.cloth_id, cloth_master.cloth_name FROM product_list JOIN cloth_master ON cloth_master.cloth_id = product_list.cloth_id JOIN color_master ON color_master.color_id = product_list.color_id JOIN size_master ON size_master.size_id = product_list.size_id JOIN product_master on product_master.product_id = product_list.product_id WHERE product_master.product_id = (SELECT product_id FROM product_master WHERE product_name = ?) GROUP BY product_list.cloth_id LIMIT ${max},${show}`
          , [productName], function(error, result, extra) {
            if (error) {
              callback(true, error['code']);
            } else {
              if (result.length > 0) {
                callback(false, result);
              } else {
                callback(true, 'No more data Available');
              }
            }
          });
    } else if (type == 'size') {
      connection.query(`SELECT product_list.size_id, size_master.size_code FROM product_list JOIN cloth_master ON cloth_master.cloth_id = product_list.cloth_id JOIN color_master ON color_master.color_id = product_list.color_id JOIN size_master ON size_master.size_id = product_list.size_id JOIN product_master on product_master.product_id = product_list.product_id WHERE product_master.product_id = (SELECT product_id FROM product_master WHERE product_name = ? AND cloth_master.cloth_name = ?) GROUP BY product_list.size_id LIMIT ${max},${show}`
          , [productName, clothName], function(error, result, extra) {
            if (error) {
              callback(true, error['code']);
            } else {
              if (result.length > 0) {
                callback(false, result);
              } else {
                callback(true, 'Products Cannot Be Found ! Contact Adminstrator');
              }
            }
          });
    } else if (type == 'color') {
      connection.query(`SELECT id, color_master.color_name, product_list.color_id, color_master.color_code FROM product_list JOIN cloth_master ON cloth_master.cloth_id = product_list.cloth_id JOIN color_master ON color_master.color_id = product_list.color_id JOIN size_master ON size_master.size_id = product_list.size_id JOIN product_master on product_master.product_id = product_list.product_id WHERE product_master.product_id = (SELECT product_id FROM product_master WHERE product_name = ? AND cloth_master.cloth_name = ? AND size_master.size_code = ?) GROUP BY product_list.color_id LIMIT ${max},${show}`
          , [productName, clothName, sizeCode], function(error, result, extra) {
            if (error) {
              callback(true, error['code']);
            } else {
              if (result.length > 0) {
                callback(false, result);
              } else {
                callback(true, 'Products Cannot Be Found ! Contact Adminstrator');
              }
            }
          });
    } else {
      callback(true, 'Error Type Not Found');
    }
  }

  /**
   * Get Order List
   * @param {string} username
   * @param {Function} callback
   */
  getOrder(username, callback) {
    connection.query('SELECT order_id, order_status_name FROM orders JOIN order_status_master ON order_status_master.order_status_id = orders.order_status WHERE orders.username = ? ORDER BY orders.order_add_timestamp DESC',
        [username], function(error, result, extra) {
          if (error) {
            callback(true, error['code']);
          } else {
            if (result.length > 0) {
              callback(false, result);
            } else {
              callback(true, 'No Orders Found !');
            }
          }
        });
  }
}

module.exports = AndroidLogin;
