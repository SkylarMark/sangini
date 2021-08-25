/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class
 */
class ProductList {
/**
 * @constructor {console.log(Activated)}
 */
  constructor() {
    console.log('Class Product List Activated');
  }

  /**
     * Add Item in Product List
     * @param {int} clothId
     * @param {int} colorId
     * @param {int} sizeId
     * @param {int} productId
     * @param {int} price
     * @param {int} callback
     */
  add(clothId, colorId, sizeId, productId, price, callback) {
    connection.query('INSERT INTO `product_list`(`cloth_id`, `color_id`, `size_id`, `product_id`, `price`,`quantity`) VALUES (?,?,?,?,?,?)', [clothId, colorId, sizeId, productId, price, 0], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.insertId > 0) {
          callback(null, results);
        } else {
          callback('true', 'No Data Found');
        }
      }
    });
  }

  /**
     * Update Item in Product List
     * @param {int} id
     * @param {int} clothId
     * @param {int} colorId
     * @param {int} sizeId
     * @param {int} productId
     * @param {int} price
     * @param {int} quantity
     * @param {Function} callback
     */
  update(id, clothId, colorId, sizeId, productId, price, quantity, callback) {
    connection.query('UPDATE `product_list` SET `cloth_id`=?,`color_id`=?,`size_id`=?,`product_id`=?,`price`=?,`quantity`=? WHERE id = ?', [clothId, colorId, sizeId, productId, price, quantity, id], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          callback(null, results);
        } else {
          callback('true', 'No Data Found');
        }
      }
    });
  }

  /**
     * Delete Item from Product List
     * @param {int} id
     * @param {Function} callback
     */
  delete(id, callback) {
    connection.query('DELETE FROM `product_list` WHERE id = ?', [id], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          callback(null, results);
        } else {
          callback('true', 'No Data Found');
        }
      }
    });
  }
}

module.exports = ProductList;
