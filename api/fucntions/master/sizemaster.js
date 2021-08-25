/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class Function Container
 */
class SizeMaster {
/**
 * @constructor {console.log(Activated)}
 */
  constructor() {
    console.log('Class Size Master Activated');
  }

  /**
     * Add Item in Size Master
     * @param {string} name
     * @param {Function} callback
     */
  add(name, callback) {
    connection.query('INSERT INTO `size_master`(`size_code`) VALUES (?)', [name], function(error, results, feilds) {
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
     * Update Item in Size Master
     * @param {int} id
     * @param {string} name
     * @param {Function} callback
     */
  update(id, name, callback) {
    connection.query('UPDATE `size_master` SET `size_code`=? WHERE size_id = ?', [name, id], function(error, results, feilds) {
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
     * Delete Item from Size Master
     * @param {int} id
     * @param {Function} callback
     */
  delete(id, callback) {
    connection.query('DELETE FROM `size_master` WHERE size_id = ?', [id], function(error, results, feilds) {
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

module.exports = SizeMaster;
