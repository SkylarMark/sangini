import 'dart:convert';
import 'package:auto_size_text/auto_size_text.dart';
import 'package:dio/dio.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;
import 'package:app/modules/MyDrawer.dart';
import 'package:app/modules/constants.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

Response response;
Dio dio = new Dio();
final storage = new Storage.FlutterSecureStorage();

class AddToCart extends StatefulWidget {
  final String product;
  final String cloth;
  final String size;
  final String color;
  final int colorCode;
  final int productID;
  AddToCart(this.product, this.cloth, this.size, this.color, this.colorCode,
      this.productID);

  @override
  _AddToCartState createState() => _AddToCartState(this.product, this.cloth,
      this.size, this.color, this.colorCode, this.productID);
}

class _AddToCartState extends State<AddToCart> {
  final String product;
  final String cloth;
  final String size;
  final String color;
  final int colorCode;
  final int productID;
  _AddToCartState(this.product, this.cloth, this.size, this.color,
      this.colorCode, this.productID);

  var _isVisible = false;
  bool _isLoading = false;
  final _quantityController = TextEditingController();

  final GlobalKey<ScaffoldState> _scaffoldKey = new GlobalKey<ScaffoldState>();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      key: _scaffoldKey,
      floatingActionButton: Visibility(
        visible: _isVisible,
        child: FloatingActionButton(
          onPressed: () {
            setState(() {
              _isLoading = true;
              FocusScope.of(context).requestFocus(FocusNode());
            });
            showDialog(
              context: context,
              builder: (_) => AlertDialog(
                title: AutoSizeText("Add to Cart ?"),
                content: AutoSizeText('Add this item to your cart'),
                actions: <Widget>[
                  FlatButton(
                    child: Text('Yes'),
                    onPressed: () {
                      addToCart(productID);
                    },
                  ),
                  FlatButton(
                    child: Text('No'),
                    onPressed: () => Navigator.pop(context),
                  ),
                ],
                elevation: 25.0,
              ),
              barrierDismissible: true,
            );
          },
          child: Icon(Icons.add),
        ),
      ),
      appBar: AppBar(
        title: Text(
          title,
          style: TextStyle(fontFamily: "Mr.Dafoe", fontSize: 30.0),
        ),
        centerTitle: true,
        automaticallyImplyLeading: true,
        actions: <Widget>[
          IconButton(
            icon: Icon(Icons.clear),
            onPressed: () {
              Navigator.of(context).pushNamedAndRemoveUntil(
                  '/dashboard', (Route<dynamic> route) => false);
            },
          ),
          SizedBox()
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Container(
            child: Column(
              children: <Widget>[
                SizedBox(
                  height: 20.0,
                ),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: <Widget>[
                    AutoSizeText(
                      'Just A Step More',
                      style: TextStyle(
                          fontSize: 25.0, color: Colors.pink.shade500),
                    )
                  ],
                ),
                SizedBox(
                  height: 20.0,
                ),
                AutoSizeText(
                  'You are going to add this item to cart',
                  style: TextStyle(fontSize: 15.0, color: Colors.pink.shade200),
                ),
                SizedBox(
                  height: 20.0,
                ),
                Row(
                  children: <Widget>[
                    Expanded(
                      child: Card(
                        color: itemCardColor,
                        child: Container(
                          height: 50.0,
                          width: MediaQuery.of(context).size.width,
                          child: Center(
                            child: AutoSizeText(
                              product,
                              style: displayChoice,
                            ),
                          ),
                        ),
                      ),
                    )
                  ],
                ),
                SizedBox(
                  height: 10.0,
                ),
                Column(
                  children: <Widget>[
                    Card(
                      color: Colors.pink.shade400,
                      child: Container(
                          height: 50,
                          width: MediaQuery.of(context).size.width / 1.1,
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: <Widget>[
//                            SizedBox(width: 10.0,),
                              Expanded(
                                flex: 2,
                                child: Icon(
                                  FontAwesomeIcons.tshirt,
                                  color: Colors.white,
                                ),
                              ),
                              Expanded(
                                flex: 2,
                                child: Text(
                                  cloth,
                                  style: TextStyle(
                                      color: Colors.white, fontSize: 16.0),
                                ),
                              ),
                              Expanded(
                                child: Container(
                                  color: Colors.pink.shade400,
                                ),
                              )
//                            SizedBox(width: 10.0,),
                            ],
                          )),
                    ),
                    Card(
                      color: Colors.pink.shade400,
                      child: Container(
                          height: 50,
                          width: MediaQuery.of(context).size.width / 1.1,
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: <Widget>[
//                            SizedBox(width: 10.0,),
                              Expanded(
                                flex: 2,
                                child: Icon(
                                  FontAwesomeIcons.ruler,
                                  color: Colors.white,
                                ),
                              ),
                              Expanded(
                                flex: 2,
                                child: Text(
                                  size,
                                  style: TextStyle(
                                      color: Colors.white, fontSize: 16.0),
                                ),
                              ),
                              Expanded(
                                child: Container(
                                  color: Colors.pink.shade400,
                                ),
                              )
//                            SizedBox(width: 10.0,),
                            ],
                          )),
                    ),
                    Card(
                      color: Colors.pink.shade400,
                      child: Container(
                          height: 50,
                          width: MediaQuery.of(context).size.width / 1.1,
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: <Widget>[
                              Expanded(
                                flex: 2,
                                child: Icon(
                                  FontAwesomeIcons.palette,
                                  color: Colors.white,
                                ),
                              ),
                              Expanded(
                                flex: 2,
                                child: Text(
                                  color,
                                  style: TextStyle(
                                      color: Colors.white, fontSize: 16.0),
                                ),
                              ),
                              Expanded(
                                child: Padding(
                                  padding: const EdgeInsets.all(8.0),
                                  child: Container(
                                    color: Color(colorCode),
                                  ),
                                ),
                              )
                            ],
                          )),
                    ),
                    Card(
                      color: Colors.pink.shade400,
                      child: Container(
                          height: 50,
                          width: MediaQuery.of(context).size.width / 1.1,
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: <Widget>[
                              Expanded(
                                flex: 2,
                                child: Icon(
                                  FontAwesomeIcons.layerGroup,
                                  color: Colors.white,
                                ),
                              ),
                              Expanded(
                                flex: 2,
                                child: Text(
                                  _quantityController.text == ""
                                      ? '0'
                                      : _quantityController.text,
                                  style: TextStyle(
                                      color: Colors.white, fontSize: 16.0),
                                ),
                              ),
                              Expanded(
                                child: Container(
                                  color: Colors.pink.shade400,
                                ),
                              )
                            ],
                          )),
                    ),
                  ],
                ),
                SizedBox(
                  height: 20.0,
                ),
                Padding(
                  padding: EdgeInsets.symmetric(
                      vertical: 0.0,
                      horizontal:
                          MediaQuery.of(context).size.width / 4.toDouble()),
                  child: TextFormField(
                    controller: _quantityController,
                    keyboardType: TextInputType.number,
                    decoration: InputDecoration(
                      isDense: true,
                      hintText: "Quantity",
                      enabledBorder: OutlineInputBorder(
                        borderSide:
                            BorderSide(color: Colors.pink.shade500, width: 0.0),
                      ),
                      labelText: 'Quantity',
                      labelStyle: TextStyle(
                        color: Colors.pink.shade500,
                      ),
                      floatingLabelBehavior: FloatingLabelBehavior.auto,
                      focusedBorder: OutlineInputBorder(
                        borderSide: BorderSide(color: Colors.pink.shade500),
                      ),
                    ),
                    validator: validateQuantity,
                    autovalidate: true,
                    onChanged: (value) {
                      if (value.isNotEmpty) {
                        int check = int.parse(value);
                        if (check > 0) {
                          setState(() {
                            _isVisible = true;
                          });
                          return null;
                        } else {
                          setState(() {
                            _isVisible = false;
                          });
                          return 'Quantity Must be Greater than 0';
                        }
                      } else {
                        setState(() {
                          _isVisible = false;
                        });
                        return null;
                      }
                    },
                  ),
                ),
                SizedBox(
                  height: 10.0,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  String validateQuantity(String value) {
    if (value.isNotEmpty) {
      int check = int.parse(value);
      if (check > 0) {
        _isVisible = true;
        return null;
      } else {
        _isVisible = false;
        return 'Quantity Must be Greater than 0';
      }
    } else {
      _isVisible = false;
      return null;
    }
  }

  addToCart(int productID) async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');
    final url = "$apiServer/cart";
    var formData = {
      "username": username,
      "api_key": apiKey,
      "quantity": _quantityController.text,
      "type": "add",
      "product_list_id": productID,
    };

    Response response = await dio.post(url,
        data: formData,
        options: Options(contentType: Headers.formUrlEncodedContentType));
    dynamic convertString = (response.data);
    Map jsonData = json.decode(convertString) as Map;
    var error = (jsonData['error']);
    if (error) {
      var errorMsg = (jsonData['data']);
      _scaffoldKey.currentState.showSnackBar(
        SnackBar(
          content: Text(errorMsg),
          backgroundColor: Colors.pink.shade400,
          duration: Duration(seconds: 3),
        ),
      );
      Navigator.pop(context);
    } else {
      _scaffoldKey.currentState.showSnackBar(
        SnackBar(
          content: Text('Item Added to Cart Successfully'),
          backgroundColor: Colors.pink.shade400,
          duration: Duration(seconds: 3),
        ),
      );
      Future.delayed(Duration(milliseconds: 1500), () {
        Navigator.of(context).pushNamedAndRemoveUntil(
            '/dashboard', (Route<dynamic> route) => false);
      });
    }
    setState(() {
      _isLoading = false;
    });
  }
}
