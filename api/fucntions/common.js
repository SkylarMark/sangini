/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const connection = require('../config/db');
const Isemail = require('isemail');
const saltedMd5 = require('salted-md5');
const Promise = require('promise');
const uuid = require('uuid-random');
const md5 = require('md5');

/**
 * Class Containing Fucntions
*/
class Common {
  /**
  * @constructor {console.log(Activated)}
  */
  constructor() {
    console.log('Class Common Activated');
  }

  /**
 * Check if Api Key is Valid
 * @param {string} username Username of User
 * @param {string} apiKey Api Key of User From Client Side
 * @return {Promise} Promise
 */
  checkapi(username, apiKey) {
    return new Promise(function(resolve, reject) {
      connection.query(
          'SELECT * FROM user_login where username = ? AND api_key = ?',
          [username, apiKey], function(error, results, feilds) {
            if (error) {
              console.log('Error: ' + error);
              return reject(error);
            } else {
              if (results.length > 0) {
                return resolve(results);
              } else {
                return reject(new Error('Not a Valid User !'));
              }
            }
          });
    });
  }

  /**
  * Check if Login details are Correct
  * @param {string} userlogin The first number.
  * @param {string} password The second number.
  * @param {Function} callback The second number.
  */
  Login(userlogin, password, callback) {
    if (!isNaN(userlogin)) {
      // Check for Phone Number
      connection.query(
          'SELECT username, api_key, user_pass, salt, role FROM `user_login` WHERE phoneNumber = ?',
          [userlogin, password], function(error, results, feilds) {
            if (error) {
              callback(error, null, 'Some Error Occured');
            } else {
              if (results.length > 0) {
                const username = results['0']['username'];
                const saltedHashAsync = saltedMd5(password,
                    results['0']['salt']);
                const dbpassword = results['0']['user_pass'];
                const role = results['0']['role'];

                if (dbpassword == saltedHashAsync) {
                  connection.query(
                      'INSERT INTO `login_log` (username, current_status, latitude, longitude, os, role) VALUES (?,?,?,?,?,?)',
                      [username, 1, 27, 36, 'Web', role]);

                  results = {
                    'username': results['0']['username'],
                    'api_key': results['0']['api_key'],
                    'role': results['0']['role'],
                  };

                  callback(null, results, 'Username and API_Key');
                } else {
                  callback(true, 'Incorrect Phone Number or Password');
                }
              } else {
                callback(true, 'Incorrect Phone Number or Password');
              }
            }
          });
    } else {
      if (Isemail.validate(userlogin)) {
        // Check for Email
        connection.query('SELECT username, api_key, user_pass, salt, role FROM `user_login` WHERE email = ?',
            [userlogin],
            function(error, results, feilds) {
              if (error) {
                callback(error, null, 'Some Error Occured ! Contact Administrator');
              } else {
                if (results.length > 0) {
                  const saltedHashAsync = saltedMd5(password, results['0']['salt']);
                  const dbpassword = results['0']['user_pass'];
                  if (dbpassword == saltedHashAsync) {
                    results = {'username': results['0']['username'], 'api_key': results['0']['api_key'], 'role': results['0']['role']};
                    callback(null, results, 'Username and API_Key');
                  } else {
                    callback(true, 'Incorrect Email or Password');
                  }
                } else {
                  callback(true, 'Incorrect Email or Password');
                }
              }
            });
      } else {
        // Check for Username

        connection.query(
            'SELECT username, api_key, user_pass, salt, role FROM `user_login` WHERE username = ?',
            [userlogin],
            function(error, results, feilds) {
              if (!results.length > 0) {
                callback(true, results, 'Incorrect Username or Password');
              } else {
                const saltedHashAsync = saltedMd5(password, results['0']['salt']);
                const dbpassword = results['0']['user_pass'];
                if (dbpassword == saltedHashAsync) {
                  results = {
                    'username': results['0']['username'],
                    'api_key': results['0']['api_key'],
                    'role': results['0']['role'],
                  };
                  callback(null, results, 'Username and API_Key');
                } else {
                  callback(true, 'Incorrect Username or Password');
                }
              }
            });
      }
    }
  }

  /**
  * Logout User
  * @param {string} username The first number.
  * @param {Function} callback The second number.
  */
  Logout(username, callback) {
    connection.query('UPDATE `login_log` SET `current_status` = ? WHERE username = ?', [0, username], function(error, results) {
      if (error) {
        callback(error, null);
      } else {
        callback(null, results);
      }
    });
  }

  /**
  * Check Registration
  * @param {string} username
  * @param {string} email
  * @param {int} phoneNumber
  * @return {Promise}
  */
  checkRegistration(username, email, phoneNumber) {
    if (username && email == null && phoneNumber == null) {
      return new Promise(function(resolve, reject) {
        connection.query(`SELECT username, email, phoneNumber FROM user_login WHERE username = ?`,
            [username],
            function(err, rows, fields) {
              if (err) {
                return reject(err);
              } else {
                return resolve(rows);
              }
            });
      });
    } else if (username == null && email && phoneNumber == null) {
      return new Promise(function(resolve, reject) {
        connection.query(`SELECT username, email, phoneNumber FROM user_login WHERE email = ?`,
            [email],
            function(err, rows, fields) {
              if (err) {
                return reject(err);
              } else {
                resolve(rows);
              }
            });
      });
    } else if (username == null && email == null && phoneNumber) {
      return new Promise(function(resolve, reject) {
        connection.query(`SELECT username, email, phoneNumber FROM user_login WHERE phoneNumber = ?`,
            [phoneNumber],
            function(err, rows, fields) {
              if (err) {
                return reject(err);
              } else {
                resolve(rows);
              }
            });
      });
    }
  }

  /**
  * Registration
  * @param {string} username
  * @param {string} email
  * @param {int} phoneNumber
  * @param {int} password
  * @param {int} salt
  * @param {Function} callback
  */
  Register(username, email, phoneNumber, password, salt, callback) {
    const genuuid = uuid();
    const genname = md5(username);
    const apiKey = genname + '.' + genuuid;

    connection.query('INSERT INTO `user_login` (username, email, phoneNumber, user_pass, salt, api_key) VALUES (?, ?, ?, ?, ?, ?)',
        [username, email, phoneNumber, password, salt, apiKey], function(error, results, feilds) {
          if (error) {
            callback(true, results);
          } else {
            if (results.insertId != null) {
              callback(null, results);
            } else {
              callback(true, 'No Data Found');
            }
          }
        });
  }

  // Dynamic Query
  // TableName, SELECT Feilds, Sort by ID, Sorting : ASC / DESC
  /**
  * Dynamic Query
  * @param {string} tableName
  * @param {string} feilds
  * @param {int} id
  * @param {string} sort
  * @param {int} limit
  * @param {int} show
  * @param {Function} callback
  */
  viewTable(tableName, feilds, id, sort, limit, show, callback) {
    // Parse Array
    // eslint-disable-next-line no-var
    var x = 0;
    feilds.forEach((element) => {
      if (x == 0) {
        feilds = element;
        x++;
      } else {
        feilds = feilds + ',' + element;
      }
    });

    // eslint-disable-next-line no-var
    var x = 0;
    tableName.forEach((element) => {
      if (x == 0) {
        tableName = element;
        x++;
      } else {
        tableName = tableName + ' ' + element;
      }
    });

    connection.query(`SELECT ${feilds} FROM ${tableName} ORDER BY ?? ${sort} LIMIT ${limit},${show}`, [id], function(error, results, extra) {
      if (error) {
        callback(true, error);
      } else {
        if (results.length > 0) {
          callback(false, results);
        } else {
          callback(true, 'Add Some Data First');
        }
      }
    });
  }

  /**
  * Dynamic Query
  * @param {string} tableName
  * @param {Function} callback
  */
  getRows(tableName, callback) {
    // eslint-disable-next-line no-var
    var x = 0;
    tableName.forEach((element) => {
      if (x == 0) {
        tableName = element;
        x++;
      } else {
        tableName = tableName + ' ' + element;
      }
    });

    connection.query(`SELECT * FROM ${tableName}`, function(error, results, extra) {
      error == null ?
        callback(false, results) :
        callback(true, error.sql);
    });
  }

  /**
  * Keep Connection Alive
  */
  stayawake() {
    console.log('OMG Its Been 7 Hours !');
    connection.query('SELECT * FROM error_log');
  }
}

module.exports = Common;
