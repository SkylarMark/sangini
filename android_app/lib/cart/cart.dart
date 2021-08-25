import 'dart:convert';
import 'package:app/modules/MyDrawer.dart';
import 'package:app/modules/constants.dart';
import 'package:auto_size_text/auto_size_text.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;

Response response;
Dio dio = new Dio();
final storage = new Storage.FlutterSecureStorage();

class Cart extends StatefulWidget {
  @override
  _CartState createState() => _CartState();
}

class _CartState extends State<Cart> {

  @override
  void initState() {
    super.initState();
    getCart();
  }

  List<Widget> cartList = [
    SizedBox(height: 50.0,),
    Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: <Widget>[
        Center(child: Container(child: CircularProgressIndicator())),
      ],
    ),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
//      bottomNavigationBar: BottomNavigationBar(
//      currentIndex: 0, // this will be set when a new tab is tapped
//      items: [
//        BottomNavigationBarItem(
//          icon: new Icon(Icons.shopping_cart),
//          title: new Text('Cart'),
//        ),
//        BottomNavigationBarItem(
//          icon: new Icon(Icons.library_books),
//          title: new Text('Orders'),
//        ),
//      ],
//    ),
      floatingActionButtonLocation: FloatingActionButtonLocation.endTop,
      floatingActionButton: FloatingActionButton(
        onPressed: (){
          if (cartList.length > 0)
            {
              showDialog(
                context: context,
                builder: (_) => AlertDialog(
                  title: AutoSizeText("Place Order ?"),
                  content: AutoSizeText('Place order for all the item in the cart ?'),
                  actions: <Widget>[
                    FlatButton(
                      child: Text('Yes'),
                      onPressed: () {
                        placeOrder();
                        Navigator.pop(context);
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
            }
          else
            {
              return null;
            }
        },
        isExtended: true,
        tooltip: "Place Order",
        backgroundColor: Colors.white,
        elevation: 10.0,
        child:
            Icon(FontAwesomeIcons.check, color: Colors.pink.shade500,),
      ),
//      drawer: MyDrawer(),
      appBar: AppBar(
        title: Text(
          title,
          style: TextStyle(fontFamily: "Mr.Dafoe", fontSize: 30.0),
        ),
        centerTitle: true,
        automaticallyImplyLeading: true,
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Container(
            child: Column(
              children: cartList,
            ),
          ),
        ),
      ),
    );
  }

  getCart() async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');

    if (username != null && apiKey != null) {
      final url = "$apiServer/cart";
      var formData = {
        "username": username,
        "api_key": apiKey,
        "type": 'view',
      };
      Response response = await dio.post(url,
          data: formData,
          options: Options(contentType: Headers.formUrlEncodedContentType));
      dynamic convertString = (response.data);
      Map jsonData = json.decode(convertString) as Map;

      var error = (jsonData['error']);
      if (error) {
        print('error Occured in getting View');
        setState(() {
          cartList.clear();
          cartList.add(
              Card(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: <Widget>[
                    ListTile(
                      title: Center(child: Text('Cart is Empty !')),
                    ),
                  ],
                ),
              )
          );
        });
      } else {
        setState(() {
          cartList.clear();
        });
        for (var x = 0; x < jsonData['data'].length; x++ )
        {
          var productID = (jsonData['data'][x]['product_list_id']);
          var product = (jsonData['data'][x]['Product']);
          var cloth = (jsonData['data'][x]['Cloth']);
          var color = (jsonData['data'][x]['Color']);
          var colorCode = (jsonData['data'][x]['Color_Code']);
          colorCode = hexStringToHexInt(colorCode);
          var size = (jsonData['data'][x]['Size']);
          var quantity = (jsonData['data'][x]['quantity']);
          setState(() {
            cartList.add(
                Card(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: <Widget>[
                      ListTile(
                        leading: SizedBox(width: MediaQuery.of(context).size.width/8, child: Container(color: Color(colorCode),),),
                        title: AutoSizeText(cloth + ' ' + product, maxLines: 1,),
                        subtitle: AutoSizeText('Size: $size, Color: $color, Quantity: $quantity'),
                        trailing: IconButton(icon: Icon(FontAwesomeIcons.times), onPressed: (){
                          showDialog(
                            context: context,
                            builder: (_) => AlertDialog(
                              title: AutoSizeText("Remove From Cart ?"),
                              content: AutoSizeText('Delete item from your cart'),
                              actions: <Widget>[
                                FlatButton(
                                  child: Text('Yes'),
                                  onPressed: () {
                                    removeFromCart(productID);
                                    Navigator.pop(context);
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
                        },),
                      ),
                    ],
                  ),
                )
            );
          });
        }
        setState(() {
          cartList.add(
            SizedBox(width: 100.0,),
          );
        });
      }
    }
  }

  placeOrder() async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');

    if (username != null && apiKey != null) {
      final url = "$apiServer/placeOrder";
      var formData = {
        "username": username,
        "api_key": apiKey,
      };
      Response response = await dio.post(url,
          data: formData,
          options: Options(contentType: Headers.formUrlEncodedContentType));
      dynamic convertString = (response.data);
      Map jsonData = json.decode(convertString) as Map;

      var error = (jsonData['error']);
      if (error) {
          print(error);
      } else {
          print('Order Placed');
          getCart();
          setState(() {
            cartList.clear();
          });
        }
      }
  }

  removeFromCart(productID) async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');

    if (username != null && apiKey != null) {
      final url = "$apiServer/cart";
      var formData = {
        "username": username,
        "api_key": apiKey,
        "type": 'delete',
        "product_list_id": productID,
      };
      Response response = await dio.post(url,
          data: formData,
          options: Options(contentType: Headers.formUrlEncodedContentType));
      dynamic convertString = (response.data);
      Map jsonData = json.decode(convertString) as Map;

      var error = (jsonData['error']);
      if (error) {
        print(error);
      } else {
        print('Item Removed');
        getCart();
      }
    }
  }

  hexStringToHexInt(String hex) {
    hex = hex.replaceFirst('#', '');
    hex = hex.length == 6 ? 'ff' + hex : hex;
    int val = int.parse(hex, radix: 16);
    return val;
  }
}
