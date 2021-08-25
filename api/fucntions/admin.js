/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const connection = require('../config/db');

/**
 * Class Containing Admin Only Fucntions these
 * are not suitable for normal Users
*/
class Admin {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class Admin Activated');
  }

  /**
  * Get Table Data (ALL)
  * @param {string} table Username of User
  * @param {Function} callback
  */
  getTableview(table, callback) {
    let id;

    if (table == 'product_master') {
      id = 'product_id';
    } else if (table == 'color_master') {
      id = 'color_id';
    } else if (table == 'cloth_master') {
      id = 'cloth_id';
    } else if (table == 'size_master') {
      id = 'size_id';
    } else if (table == 'product_list') {
      id = 'id';
    } else {
      callback(true, 'Not a Allowed Table');
    }

    connection.query(`SELECT * FROM ${table} ORDER BY ${table}.${id} ASC`, function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.length > 0) {
          callback(null, results);
        } else {
          callback('true', 'No Data Found');
        }
      }
    });
  }

  /**
  * Error Reporting
  * @param {string} error Username of User
  * @param {string} location Username of User
  * @param {string} username Username of User
  */
  reportError(error, location, username) {
    connection.query('INSERT INTO `error_log` (error, location, user) VALUES (?, ?, ?)', [error['code'], location, username]);
  }

  /**
  * Update Price of Item
  * @param {int} id Username of User
  * @param {int} price Username of User
  * @param {string} username Username of User
  * @param {string} callback Username of User
  */
  updateprice(id, price, username, callback) {
    connection.query('UPDATE `product_list` SET `price`= ? WHERE id = ?', [price, id], function(error, results, extra) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          connection.query('INSERT INTO `product_log`(`username`, `price`, `product_id`) VALUES (?,?,?)', [username, price, id]);
          callback(false, results);
        } else {
          callback(true, 'Value Not Updated');
        }
      }
    });
  }

  /**
  * Update Stock of Item
  * @param {int} id Username of User
  * @param {int} stock Username of User
  * @param {string} username Username of User
  * @param {string} callback Username of User
  */
  updatestock(id, stock, username, callback) {
    connection.query('UPDATE `product_list` SET `quantity`= quantity + ? WHERE id = ?', [stock, id], function(error, results, extra) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          connection.query('INSERT INTO `product_log`(`username`, `quantity`, `product_id`) VALUES (?,?,?)', [username, stock, id]);
          callback(false, results);
        } else {
          callback(true, 'Value Not Updated');
        }
      }
    });
  }

  /**
  * Get Order Status
  * @param {int} id Username of User
  * @param {int} value Username of User
  * @param {string} callback Username of User
  */
  orderstatus(id, value, callback) {
    connection.query('UPDATE `orders` SET `order_status`= ? WHERE order_id = ?', [value, id], function(error, results, extra) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          if (value == 1) {
            connection.query('SELECT username FROM `orders` WHERE order_id = ?', [id], function(error, result, extra) {
              if (error) {
                console.log(error);
              } else {
                connection.query('UPDATE `orders` SET `discount` = (SELECT discount FROM `discount_master` WHERE username = ?) WHERE order_id = ?', [result[0]['username'], id], function(error, result, extra) {
                  if (error) {
                    console.log(error);
                  } else {
                    callback(false, results);
                  }
                });
              }
            });
          } else {
            callback(false, results);
          }
        } else {
          callback(true, 'Value Not Updated');
        }
      }
    });
  }

  /**
  * Check Admin
  * @param {string} userlogin Username of User
  * @param {string} apiKey Api Key of User
  * @return {Promise}
  */
  checkadmin(userlogin, apiKey) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT role FROM user_login WHERE username = ? AND api_key = ? AND role = ?`, [userlogin, apiKey, 0], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length > 0) {
            return resolve(results);
          } else {
            return reject(new Error('Not Authorized'));
          }
        }
      });
    });
  }

  /**
  * Check Product List
  * @param {int} clothId Username of User
  * @param {int} colorId Username of User
  * @param {int} sizeId Username of User
  * @param {int} productId Username of User
  * @return {Promise}
  */
  checkProduct(clothId, colorId, sizeId, productId) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM product_list WHERE cloth_id = ? AND color_id = ? AND size_id = ? AND product_id = ?`,
          [clothId, colorId, sizeId, productId],
          function(error, results, feilds) {
            if (error) {
              return reject(error);
            } else {
              if (results.length == 0) {
                return resolve(results);
              } else {
                return reject(new Error('Product Already Exists'));
              }
            }
          });
    });
  }

  /**
  * Check Before Adding to Cart
  * @param {int} username Username of User
  * @param {int} productListId Username of User
  * @return {Promise}
  */
  checkCart(username, productListId) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM cart WHERE username = ? AND product_list_id = ?`,
          [username, productListId],
          function(error, results, feilds) {
            if (error) {
              return reject(error);
            } else {
              if (results.length == 0) {
                return resolve(results);
              } else {
                return reject(new Error('Product Already in Cart'));
              }
            }
          });
    });
  }

  /**
  * Check Product List
  * @param {id} productListId
  * @return {Promise}
  */
  checkProductList(productListId) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM product_list WHERE id = ?`, [productListId], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length > 0) {
            return resolve(results);
          } else {
            return reject(new Error('Product Dosent Exists'));
          }
        }
      });
    });
  }

  /**
  * Check Product Master
  * @param {string} productName
  * @return {Promise}
  */
  checkProductMaster(productName) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM product_master WHERE product_name = ?`, [productName], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length == 0) {
            return resolve(results);
          } else {
            return reject(new Error('Product Already Exists'));
          }
        }
      });
    });
  }

  /**
  * Check Cloth Master
  * @param {string} clothName
  * @return {Promise}
  */
  checkClothMaster(clothName) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM cloth_master WHERE cloth_name = ?`, [clothName], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length == 0) {
            return resolve(results);
          } else {
            return reject(new Error('Product Already Exists'));
          }
        }
      });
    });
  }

  /**
  * Check Color Master
  * @param {string} clothName
  * @param {int} colorCode
  * @return {Promise}
  */
  checkColorMaster(clothName, colorCode) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM color_master WHERE cloth_name = ? AND color_code = ?`, [clothName, colorCode], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length == 0) {
            return resolve(results);
          } else {
            return reject(new Error('Product Already Exists'));
          }
        }
      });
    });
  }

  /**
  * Check Size Master
  * @param {int} sizeCode
  * @return {Promise}
  */
  checkColorMaster(sizeCode) {
    return new Promise(function(resolve, reject) {
      connection.query(`SELECT * FROM color_master WHERE size_code = ?`, [sizeCode], function(error, results, feilds) {
        if (error) {
          return reject(error);
        } else {
          if (results.length == 0) {
            return resolve(results);
          } else {
            return reject(new Error('Product Already Exists'));
          }
        }
      });
    });
  }
}

module.exports = Admin;
