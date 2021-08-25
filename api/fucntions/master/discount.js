/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class
 */
class DiscountMaster {
/**
 * @constructor {console.log(Activated)}
 */
  constructor() {
    console.log('Class Discount Master Activated');
  }

  /**
     *
     * @param {string} username
     * @param {int} discount
     * @param {Function} callback
     */
  update(username, discount, callback) {
    connection.query('UPDATE `discount_master` SET `discount`= ? WHERE username = ?', [discount, username], function(error, results, feilds) {
      if (error) {
        callback(error, error['code']);
      } else {
        if (results.affectedRows > 0) {
          callback(null, results);
        } else {
          callback('true', 'Entry Not Updated');
        }
      }
    });
  }
}

module.exports = DiscountMaster;
