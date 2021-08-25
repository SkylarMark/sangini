/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const connection = require('../config/db');

/**
 * Class Containing Fucntions
*/
class User {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class User Activated');
  }

  /**
 * Place Order for Username
 * @param {string} username Username of User
 * @param {Function} callback
 */
  placeOrder(username, callback) {
    const datetime = Date.now();
    const orderid = username.concat('-').concat(datetime);

    console.log(Date(Date.now()));

    connection.query(`INSERT INTO order_descriptions 
        (product_list_id, quantity, order_amount, username, order_id) 
        SELECT product_list_id, quantity, amount, username, ? FROM cart WHERE username = ?`,
    [orderid, username], function(error, results) {
      if (error) {
        callback(true, error['message']);
      } else {
        if (results.affectedRows > 0) {
          connection.query('INSERT INTO `orders`(`order_id`, `username`) VALUES ((SELECT order_descriptions.order_id FROM `order_descriptions` WHERE username = ? ORDER BY id DESC LIMIT 1), ?)', [username, username], function(error) {
            if (error) {
              callback(true, 'Error Occured');
            } else {
              connection.query(`UPDATE orders SET total_amount = (SELECT SUM(order_amount) order_amount FROM order_descriptions WHERE order_descriptions.order_id = "${orderid}") WHERE order_id = "${orderid}"`, function(error) {
                if (error) {
                  callback(true, 'Error Occured');
                } else {
                  connection.query('DELETE FROM `cart` WHERE username = ?', [username], function() {

                  });
                }
              });
            }
          });
          callback(null, results);
        } else {
          callback(true, 'Order Canceled');
        }
      }
    });
  }

  /**
  * Check Cart
  * @param {string} username Username of User
  * @param {string} productId
  * @return {Promise}
  */
  checkCart(username, productId) {
    return new Promise(function(resolve, reject) {
      connection.query('SELECT * FROM cart where username = ? AND product_list_id = ?', [username, productId], function(error, results) {
        if (error) {
          return reject(error);
        } else {
          if (results.length > 0) {
            return resolve(results);
          } else {
            return reject(new Error('Item Does Not Exists in User Cart !, Username: '+username+', Product ID: '+productId));
          }
        }
      });
    });
  }


  /**
  * Check Orders
  * @param {string} username Username of User
  * @param {string} productId
  * @param {string} orderId
  * @return {Promise}
  */
  checkOrder(username, productId, orderId) {
    return new Promise(function(resolve, reject) {
      connection.query('SELECT * FROM order_descriptions WHERE username = ? AND product_list_id = ? AND order_id = ? ', [username, productId, orderId], function(error, results) {
        if (error) {
          return reject(error);
        } else {
          if (results.length == 0) {
            return resolve(results);
          } else {
            return reject(new Error('Order Already Exists !, Username: '+username+', Product ID: '+productId+', Order ID: '+orderId));
          }
        }
      });
    });
  }
}

module.exports = User;
