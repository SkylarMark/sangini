/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const path = require('path');
const appDir = path.dirname(require.main.filename);
const connection = require(`${appDir}/config/db`);

/**
 * Class Function Container
 */
class ColorMaster {
/**
 * @constructor {console.log(Activated)}
 */
  constructor() {
    console.log('Class Color Master Activated');
  }

  // Color Master
  /**
   * Add Values of Color Master
   * @param {string} name
   * @param {string} code
   * @param {Function} callback
   */
  add(name, code, callback) {
    connection.query('INSERT INTO `color_master`(`color_name`, `color_code`) VALUES (?,?)', [name, code], function(error, results, feilds) {
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
   * Update Values of Color Master
   * @param {int} id
   * @param {string} name
   * @param {string} code
   * @param {Function} callback
   */
  update(id, name, code, callback) {
    connection.query('UPDATE `color_master` SET `color_name`=?, `color_code`=? WHERE color_id = ?', [name, code, id], function(error, results, feilds) {
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
   * Delete Values of Color Master
   * @param {int} id
   * @param {Function} callback
   */
  delete(id, callback) {
    connection.query('DELETE FROM `color_master` WHERE color_id = ?', [id], function(error, results, feilds) {
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

module.exports = ColorMaster;
