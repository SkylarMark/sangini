/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

// Important Plugins/Configs/Class Imports
const app = require('./server/server');
const schema = require('./config/password');

// Fucntion Classes
const Common = require('./fucntions/common');
const Admin = require('./fucntions/admin');
const User = require('./fucntions/user');
const ColorMaster = require('./fucntions/master/colormaster');
const ClothMaster = require('./fucntions/master/clothmaster');
const SizeMaster = require('./fucntions/master/sizemaster');
const ProductMaster = require('./fucntions/master/productmaster');
const ProductList = require('./fucntions/master/productlist');
const Cart = require('./fucntions/cart');
const DiscountMaster = require('./fucntions/master/discount');

// Class Initializers
const common = new Common();
const admin = new Admin();
const user = new User();
const color = new ColorMaster();
const cloth = new ClothMaster();
const size = new SizeMaster();
const product = new ProductMaster();
const discount = new DiscountMaster();
const productlist = new ProductList();
const cart = new Cart();

// Android App Api
const AndroidLogin = require('./fucntions/android/Login');

// Android App Initializers
const android = new AndroidLogin();

// Plugins
const uuid = require('uuid-random');
const md5 = require('md5');
const validator = require('email-validator');
const saltedMd5 = require('salted-md5');

// Login
app.post('/login', async (req, res) => {
  try {
    if ( req.body.userlogin == '' || req.body.password == '' ) {
      const response = {'error': true, 'data': 'Username or Password Missing'};
      res.end(JSON.stringify(response));
      console.log('Someone Tried to Login without Login and Password !');
    } else {
      const userlogin = req.body.userlogin.trim();
      const password = req.body.password.trim();
      common.login(userlogin, password, function(error, results, fields) {
        if (error) {
          const response = {'error': true, 'data': results};
          res.end(JSON.stringify(response));
        } else {
          const response = {'error': false, 'data': results};
          console.log('Login : '+ results);
          res.end(JSON.stringify(response));
        }
      });
    }
  } catch (error) {
    admin.reportError(error, 'Try-Catch-Register', userlogin);
    console.log('Warning : Login Try-Catch ! Error : '+ error);
    const response = {'error': true, 'data': error};
    res.end(JSON.stringify(response));
  }
});

// Logout
app.post('/logout', async (req, res) => {
  try {
    const username = req.body.username;
    username = username.trim();
    common.logout(username, function(error, results, feilds) {
      if (error) {
        const response = {'error': true, 'data': 'Logout Not Logged'};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': true, 'data': 'Successfully Logged Out'};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    admin.reportError(error, 'Logout', username);
    console.log('Warning : Logout Try Catch ! Error : '+ error);
    const response = {'error': true, 'data': error['message']};
    res.end(JSON.stringify(response));
  }
});

/** Registration */
app.post('/register', async (req, res) => {
  try {
    const username = req.body.username;
    const email = req.body.email;
    const phoneNumber = req.body.phoneNumber;
    const password = req.body.password;
    const cnfpassword = req.body.cnfpassword;
    const phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

    username = username.trim();
    email = email.trim();
    phoneNumber = phoneNumber.trim();
    password = password.trim();
    cnfpassword = cnfpassword.trim();

    // Check if cnfpassword == password
    if (password == cnfpassword) {
      cnfpassword = false;
    } else {
      cnfpassword = true;
    }

    const checkusername = '';
    const checkphoneNumber = '';
    const checkemail = '';

    await common.checkRegistration(username, null, null).then(function(rows) {
      if (rows.length > 0) {
        checkusername = rows['0']['username'];
        console.log(checkusername);
        const response = {'error': true, 'data': 'Username Already Exists'};
        res.end(JSON.stringify(response));
      }
    }).catch((err) => setImmediate(() => {
      throw err;
    })); // Check Username

    await common.checkRegistration(null, email, null).then(function(rows) {
      if (rows.length > 0) {
        checkemail = rows['0']['email'];
        console.log(checkemail);
        const response = {'error': true, 'data': 'Email Already Exists'};
        res.end(JSON.stringify(response));
      }
    }).catch((err) => setImmediate(() => {
      throw err;
    })); // Check Email

    await common.checkRegistration(null, null, phoneNumber)
        .then(function(rows) {
          if (rows.length > 0) {
            checkphoneNumber = rows['0']['phoneNumber'];
            console.log(checkphoneNumber);
            const response = {'error': true,
              'data': 'Phone Number Already Exists'};
            res.end(JSON.stringify(response));
          }
        }).catch((err) => setImmediate(() => {
          throw err;
        })); // Check Phone Number

    if (!validator.validate(email)) {
      const response = {'error': true, 'data': 'Email should be correct'};
      res.end(JSON.stringify(response));
    } else if (!phoneNumber.match(phoneno)) {
      const response = {'error': true, 'data': 'Phone Number is Incorrect'};
      res.end(JSON.stringify(response));
    } else if (!schema.validate(password)) {
      const response = {'error': true, 'data': 'Password format is Incorrect'};
      res.end(JSON.stringify(response));
    } else if (cnfpassword) {
      const response = {'error': true, 'data': 'Password Does not Match'};
      res.end(JSON.stringify(response));
    } else {
      const salt = md5(uuid());
      const saltedHash = saltedMd5(password, salt);
      common.register(username, email, phoneNumber, saltedHash, salt,
          function(error, data) {
            if (error) {
              const response = {'error': true, 'data': error['message']};
              res.end(JSON.stringify(response));
            } else {
              const response = {'error': false, 'data': 'Registered'};
              res.end(JSON.stringify(response));
            }
          });
    }
  } catch (err) {
    admin.reportError(err, 'Try-Catch-Register', username);
    const response = {'error': true, 'data': err['message']};
    res.end(JSON.stringify(response));
  }
});

// Admin Side Links
// Table View
app.post('/tables', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const table = req.body.table.trim();

    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    admin.getTableview(table, function(err, data) {
      if (err) {
        const errorTrue = {'error': true, 'data': data};
        res.end(JSON.stringify(errorTrue));
      } else {
        const errorFalse = {'error': false, 'data': data};
        res.end(JSON.stringify(errorFalse));
      }
    });
  } catch (error) {
    // admin.ReportError(error, 'Try-Catch-Tables' ,username)
    const response = {'error': true,
      'data': (username && apiKey && table) == null ?
      'Required Parameters Missing' : error};
    res.end(JSON.stringify(response));
  }
});

// CURD Operation
app.post('/curd', async (req, res) => {
  try {
    const username = req.body.username;
    const apiKey = req.body.api_key;
    const table = req.body.table;
    const type = req.body.type;

    await common.checkapi(username, apiKey);

    await admin.checkadmin(username, apiKey);

    if (table == 'color') {
      if (type == 'add') {
        const colorName = req.body.color_name.trim();
        const colorCode = req.body.color_code.trim();
        color.add(colorName, colorCode, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'update') {
        const colorId = req.body.color_id.trim();
        const colorName = req.body.color_name.trim();
        const colorCode = req.body.color_code.trim();

        color.update(colorId, colorName, colorCode,
            function(error, results, feilds) {
              if (error) {
                const response = {'error': true, 'data': results};
                res.end(JSON.stringify(response));
              } else {
                const response = {'error': true, 'data': results};
                res.end(JSON.stringify(response));
              }
            });
      } else if (type == 'delete') {
        const colorId = req.body.color_id.trim();

        color.delete(colorId, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else {
        const response = {'error': true, 'data': 'Define a Valid Type'};
        res.end(JSON.stringify(response));
      }
    } else if (table == 'size') {
      if (type == 'add') {
        const sizeCode = req.body.size_code.trim();

        size.add(sizeCode, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'update') {
        const sizeID = req.body.size_id.trim();
        const sizeCode = req.body.size_code.trim();

        size.update(sizeID, sizeCode, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'delete') {
        const sizeID = req.body.size_id.trim();

        size.delete(sizeID, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else {
        const response = {'error': true, 'data': 'Define a Valid Type'};
        res.end(JSON.stringify(response));
      }
    } else if (table == 'product') {
      if (type == 'add') {
        const productName = req.body.product_name.trim();

        product.add(productName, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'update') {
        const productName = req.body.product_name.trim();
        const productId = req.body.product_id.trim();

        product.update(productId, productName,
            function(error, results, feilds) {
              if (error) {
                const response = {'error': true, 'data': results};
                res.end(JSON.stringify(response));
              } else {
                const response = {'error': false, 'data': results};
                res.end(JSON.stringify(response));
              }
            });
      } else if (type == 'delete') {
        const productId = req.body.product_id.trim();

        product.delete(productId, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else {
        const response = {'error': true, 'data': 'Define a Valid Type'};
        res.end(JSON.stringify(response));
      }
    } else if (table == 'cloth') {
      if (type == 'add') {
        const clothName = req.body.cloth_name.trim();

        cloth.add(clothName, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'update') {
        const clothId = req.body.cloth_id.trim();
        const clothName = req.body.cloth_name.trim();

        cloth.update(clothId, clothName, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else if (type == 'delete') {
        const clothId = req.body.cloth_id.trim();

        cloth.delete(clothId, function(error, results, feilds) {
          if (error) {
            const response = {'error': true, 'data': results};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false, 'data': results};
            res.end(JSON.stringify(response));
          }
        });
      } else {
        const response = {'error': true, 'data': 'Define a Valid Type'};
        res.end(JSON.stringify(response));
      }
    } else if (table == 'discount') {
      if (type == 'update') {
        const changeusername = req.body.changeusername.trim();
        const discountvalue = req.body.discount.trim();

        discount.update(changeusername, discountvalue,
            function(error, results, feilds) {
              if (error) {
                const response = {'error': true, 'data': results};
                res.end(JSON.stringify(response));
              } else {
                const response = {'error': false, 'data': results};
                res.end(JSON.stringify(response));
              }
            });
      } else {
        const response = {'error': true, 'data': 'Define a Valid Type'};
        res.end(JSON.stringify(response));
      }
    } else {
      const response = {'error': true, 'data': 'Define a Valid Table Type'};
      res.end(JSON.stringify(response));
    }
  } catch (error) {
    // admin.ReportError(error, 'Logout', username);
    const response = {'error': true, 'data': error};
    res.end(JSON.stringify(response));
  }
});

// CURD Product List
app.post('/productlist', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const type = req.body.type.trim();

    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    if (type == 'add') {
      const clothId = req.body.cloth_id.trim();
      const colorId = req.body.color_id.trim();
      const sizeID = req.body.size_id.trim();
      const productId = req.body.product_id.trim();
      const price = req.body.price.trim();

      await admin.checkProduct(clothId, colorId, sizeID, productId);

      productlist.add(clothId, colorId, sizeID, productId, price,
          function(error, results, feilds) {
            if (error) {
              const response = {'error': true, 'data': results};
              res.end(JSON.stringify(response));
            } else {
              const response = {'error': false, 'data': results};
              res.end(JSON.stringify(response));
            }
          });
    } else if (type == 'update') {
      const id = req.body.id.trim();
      const clothId = req.body.cloth_id.trim();
      const colorId = req.body.color_id.trim();
      const sizeID = req.body.size_id.trim();
      const productId = req.body.product_id.trim();
      const price = req.body.price.trim();

      productlist.update(id, clothId, colorId, sizeID, productId, price,
          function(error, results, feilds) {
            if (error) {
              const response = {'error': true, 'data': results};
              res.end(JSON.stringify(response));
            } else {
              const response = {'error': false, 'data': results};
              res.end(JSON.stringify(response));
            }
          });
    } else if (type == 'delete') {
      const id = req.body.id.trim();

      productlist.delete(id, function(error, results, feilds) {
        if (error) {
          const response = {'error': true, 'data': results};
          res.end(JSON.stringify(response));
        } else {
          const response = {'error': false, 'data': results};
          res.end(JSON.stringify(response));
        }
      });
    } else {
      const response = {'error': true, 'data': 'Define a Valid Type'};
      res.end(JSON.stringify(response));
    }
  } catch (error) {
    // admin.ReportError(error, 'Try-Catch-Tables' ,username)
    const response = {'error': true,
      'data': (username && apiKey) == null ?
      'Required Parameters Missing' :
      error};

    res.end(JSON.stringify(response));
  }
});


// User Side Links
// Product List
app.get('/products', async (req, res) => {
  try {
    user.products(function(err, data) {
      if (err) {
        const errorTrue = {'error': true, 'data': data};
        res.end(JSON.stringify(errorTrue));
      } else {
        const errorFalse = {'error': false, 'data': data};
        res.end(JSON.stringify(errorFalse));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data': error};
    res.end(JSON.stringify(response));
  }
});

// Cart
app.post('/cart', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const type = req.body.type.trim();

    await common.checkapi(username, apiKey);

    if (type == 'view') {
      cart.cartView(username, function(err, data) {
        if (err) {
          const errorTrue = {'error': true, 'data': data};
          res.end(JSON.stringify(errorTrue));
        } else {
          const errorFalse = {'error': false, 'data': data};
          res.end(JSON.stringify(errorFalse));
        }
      });
    } else if (type == 'add') {
      const productListId = req.body.product_list_id.trim();
      const quantity = req.body.quantity.trim();

      await admin.checkCart(username, productListId);

      await admin.checkProductList(productListId);

      cart.getRate(productListId, function(err, data) {
        if (err) {
          const errorTrue = {'error': true, 'data': data};
          res.end(JSON.stringify(errorTrue));
        } else {
          const rate = data * quantity;

          cart.cartAdd(productListId, username, quantity, rate,
              function(err, data) {
                if (err) {
                  const errorTrue = {'error': true, 'data': data};
                  res.end(JSON.stringify(errorTrue));
                } else {
                  const errorFalse = {'error': false,
                    'data': 'Item added to Cart Successfully'};
                  res.end(JSON.stringify(errorFalse));
                }
              });
        }
      });
    } else if (type == 'update') {
      const productListId = req.body.product_list_id.trim();
      const quantity = req.body.quantity.trim();

      productListId = productListId.trim();
      username = username.trim();
      quantity = quantity.trim();

      cart.getRate(productListId, function(err, data) {
        if (err) {
          const errorTrue = {'error': true, 'data': data};
          res.end(JSON.stringify(errorTrue));
        } else {
          const rate = data * quantity;

          cart.cartUpdate(productListId, username, quantity, rate,
              function(err, data) {
                if (err) {
                  const errorTrue = {'error': true, 'data': data};
                  res.end(JSON.stringify(errorTrue));
                } else {
                  const errorFalse = {'error': false,
                    'data': 'Item Updated Successfully'};
                  res.end(JSON.stringify(errorFalse));
                }
              });
        }
      });
    } else if (type == 'delete') {
      const productListId = req.body.product_list_id.trim();

      productListId = productListId.trim();
      username = username.trim();

      cart.cartdelete(productListId, username, function(err, data) {
        if (err) {
          const errorTrue = {'error': true, 'data': data};
          res.end(JSON.stringify(errorTrue));
        } else {
          const errorFalse = {'error': false, 'data': data};
          res.end(JSON.stringify(errorFalse));
        }
      });
    }
  } catch (error) {
    const response = {'error': true, 'data': error};
    res.end(JSON.stringify(response));
  }
});


// Cart
app.post('/updateprice', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const id = req.body.id.trim();
    const price = req.body.price.trim();


    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    admin.updateprice(id, price, username, function(error, data) {
      if (error) {
        const response = {'error': true, 'data': data};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'data': data};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data': error == null ?
    'Required Parameter Missing' :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Update Stock
app.post('/updatestock', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const id = req.body.id.trim();
    const stock = req.body.stock.trim();


    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    admin.updatestock(id, stock, username, function(error, data) {
      if (error) {
        const response = {'error': true, 'data': data};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'data': data};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data': error == null ?
    'Required Parameter Missing' :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Place Order
app.post('/placeOrder', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();

    await common.checkapi(username, apiKey);

    user.placeOrder(username, function(error, data) {
      if (error) {
        const response = {'error': true, 'data': data};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'data': data};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data': error == null ?
    'Required Parameter Missing' :
    error};
    res.end(JSON.stringify(response));
  }
});

// Can be used to view any table
app.post('/viewtable', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const type = req.body.type.trim();
    const table = req.body.table;
    const feilds = req.body.feilds;
    const orderby = req.body.orderby.trim();
    const sort = req.body.sort.trim();
    const limit = req.body.limit.trim();
    const show = req.body.show.trim();

    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    if (type == 'view') {
      common.viewTable(table, feilds, orderby, sort, limit, show,
          function(error, result) {
            if (error) {
              const response = {'error': true, 'data': result['sql']};
              res.end(JSON.stringify(response));
            } else {
              const response = {'error': false,
                'rows': result.length, 'data': result};
              res.end(JSON.stringify(response));
            }
          });
    } else if (type == 'add') {
      console.log('add');
    } else if (type == 'update') {
      console.log('update');
    } else if (type == 'delete') {
      console.log('delete');
    } else {
      const response = {'error': true, 'data': 'Not a Valid Operation'};
      res.end(JSON.stringify(response));
    }
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ?
    error : error['message']};
    res.end(JSON.stringify(response));
  }
});


// Set Status of Order
app.post('/orderstatus', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const id = req.body.id.trim();
    const value = req.body.value.trim();

    await common.checkapi(username, apiKey);

    await admin.checkadmin(username, apiKey);

    admin.orderstatus(id, value, function(error, result) {
      if (error) {
        const response = {'error': true, 'data': result};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'data': result};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ?
    error : error['message']};
    res.end(JSON.stringify(response));
  }
});

// Get Rows of Table
app.post('/getRows', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const table = req.body.table;

    await common.checkapi(username, apiKey);
    await admin.checkadmin(username, apiKey);

    common.getRows(table, function(error, result) {
      if (error) {
        const response = {'error': true, 'data': result};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'rows': result.length};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ? error :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Android App Api's
app.post('/android/loginlog', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const latitude = req.body.latitude.trim();
    const longitude = req.body.longitude.trim();
    const os = req.body.os.trim();

    await common.checkapi(username, apiKey);

    android.loginlog(username, latitude, longitude, os, function(error, reply) {
      if (error) {
        const response = {'error': true, 'data': reply};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'rows': reply.length, 'data': reply};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ? error :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Android Get Product
app.post('/android/getProducts', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();

    await common.checkapi(username, apiKey);

    android.getProducts(function(error, reply) {
      if (error) {
        const response = {'error': true, 'data': reply};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'rows': reply.length, 'data': reply};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ? error :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Android Get Product Details
app.post('/android/getProductDetails', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();
    const productName = req.body.product_name.trim();
    const type = req.body.type.trim();
    const show = req.body.show.trim();
    const max = req.body.max.trim();

    if (type == 'size') {
      // eslint-disable-next-line no-unused-vars
      const clothName = req.body.cloth_name.trim();
    } else {
      clothId = null;
    }

    if (type == 'color') {
      // eslint-disable-next-line no-unused-vars
      const clothName = req.body.cloth_name.trim();
      // eslint-disable-next-line no-unused-vars
      const sizeCode = req.body.size_code.trim();
    } else {
      if (type != 'size') {
        clothName = null;
      }
      sizeCode = null;
    }

    await common.checkapi(username, apiKey);

    android.getProductDetails(productName, type, clothName, sizeCode, show, max,
        function(error, reply) {
          if (error) {
            const response = {'error': true, 'data': reply};
            res.end(JSON.stringify(response));
          } else {
            const response = {'error': false,
              'rows': reply.length, 'data': reply};
            res.end(JSON.stringify(response));
          }
        });
  } catch (error) {
    const response = {'error': true, 'data':
    error['message'] == null ? error :
    error['message']};
    res.end(JSON.stringify(response));
  }
});

// Products Master Table Show
app.post('/android/getOrder', async (req, res) => {
  try {
    const username = req.body.username.trim();
    const apiKey = req.body.api_key.trim();

    await common.checkapi(username, apiKey);

    android.getProducts(username, function(error, reply) {
      if (error) {
        const response = {'error': true, 'data': reply};
        res.end(JSON.stringify(response));
      } else {
        const response = {'error': false, 'rows': reply.length, 'data': reply};
        res.end(JSON.stringify(response));
      }
    });
  } catch (error) {
    const response = {'error': true,
      'data': error['message'] == null ? error :
      error['message']};
    res.end(JSON.stringify(response));
  }
});

/**
 * To Keep MY-SQL Connection Alive (Disconnect Ideal Connection in 8 Hours)
 * @return {null}
 */
function intervalFunc() {
  common.stayawake();
  return null;
} setInterval(intervalFunc, 25200000);
