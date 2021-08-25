/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class Function Container
 */
class ClothMaster {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class Cloth Master Activated');
  }

  /**
   * Add item in Cloth Master
   * @param {string} name
   * @param {Function} callback
   */
  add(name, callback) {
    connection.query('INSERT INTO `cloth_master`(`cloth_name`) VALUES (?)', [name], function(error, results, feilds) {
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
   * Update Values of Cloth Master
   * @param {int} id
   * @param {string} name
   * @param {Function} callback
   */
  update(id, name, callback) {
    connection.query('UPDATE `cloth_master` SET `cloth_name`=? WHERE cloth_id = ?', [name, id], function(error, results, feilds) {
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
   * Delete Values of Cloth Master
   * @param {int} id
   * @param {Function} callback
   */
  delete(id, callback) {
    connection.query('DELETE FROM `cloth_master` WHERE cloth_id = ?', [id], function(error, results, feilds) {
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

module.exports = ClothMaster;
