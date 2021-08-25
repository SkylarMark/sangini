/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class Function Container
 */
class ProductMaster {
/**
 * @constructor {console.log(Activated)}
 */
  constructor() {
    console.log('Class Product Master Activated');
  }

  /**
     * Add item in Product Master
     * @param {string} name
     * @param {Function} callback
     */
  add(name, callback) {
    connection.query('INSERT INTO `product_master`(`product_name`) VALUES (?)', [name], function(error, results, feilds) {
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
     * Update item in Product Master
     * @param {int} id
     * @param {string} name
     * @param {Function} callback
     */
  update(id, name, callback) {
    connection.query('UPDATE `product_master` SET `product_name`=? WHERE product_id = ?', [name, id], function(error, results, feilds) {
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
     * Delete item from Product Master
     * @param {int} id
     * @param {Function} callback
     */
  delete(id, callback) {
    connection.query('DELETE FROM `product_master` WHERE product_id = ?', [id], function(error, results, feilds) {
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

module.exports = ProductMaster;
