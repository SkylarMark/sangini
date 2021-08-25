import 'dart:convert';
// TODO : Orders Page
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
//    getOrders();
  }

  List<Widget> orderList = [
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
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 0, // this will be set when a new tab is tapped
        items: [
          BottomNavigationBarItem(
            icon: new Icon(Icons.shopping_cart),
            title: new Text('Cart'),
          ),
          BottomNavigationBarItem(
            icon: new Icon(Icons.library_books),
            title: new Text('Orders'),
          ),
        ],
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.endTop,
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
              children: orderList,
            ),
          ),
        ),
      ),
    );
  }

//  getOrders() async {
//    String username = await storage.read(key: 'username');
//    String apiKey = await storage.read(key: 'apiKey');
//
//    if (username != null && apiKey != null) {
//      final url = "$apiServer/cart";
//      var formData = {
//        "username": username,
//        "api_key": apiKey,
//        "type": 'view',
//      };
//      Response response = await dio.post(url,
//          data: formData,
//          options: Options(contentType: Headers.formUrlEncodedContentType));
//      dynamic convertString = (response.data);
//      Map jsonData = json.decode(convertString) as Map;
//
//      var error = (jsonData['error']);
//      if (error) {
//        print('error Occured in getting View');
//        setState(() {
//          cartList.clear();
//          cartList.add(
//              Card(
//                child: Column(
//                  mainAxisSize: MainAxisSize.min,
//                  children: <Widget>[
//                    ListTile(
//                      title: Center(child: Text('Cart is Empty !')),
//                    ),
//                  ],
//                ),
//              )
//          );
//        });
//      } else {
//        setState(() {
//          cartList.clear();
//        });
//        for (var x = 0; x < jsonData['data'].length; x++ )
//        {
//          var productID = (jsonData['data'][x]['product_list_id']);
//          var product = (jsonData['data'][x]['Product']);
//          var cloth = (jsonData['data'][x]['Cloth']);
//          var color = (jsonData['data'][x]['Color']);
//          var colorCode = (jsonData['data'][x]['Color_Code']);
//          var size = (jsonData['data'][x]['Size']);
//          var quantity = (jsonData['data'][x]['quantity']);
//          setState(() {
//            cartList.add(
//                Card(
//                  child: Column(
//                    mainAxisSize: MainAxisSize.min,
//                    children: <Widget>[
//                      ListTile(
//                        leading: Icon(Icons.image),
//                        title: Text(product),
//                        subtitle: Text('Cloth : $cloth, Size: $size, Color: $colorCode'),
//                        trailing: IconButton(icon: Icon(FontAwesomeIcons.times), onPressed: (){
//                          showDialog(
//                            context: context,
//                            builder: (_) => AlertDialog(
//                              title: AutoSizeText("Remove From Cart ?"),
//                              content: AutoSizeText('Delete item from your cart'),
//                              actions: <Widget>[
//                                FlatButton(
//                                  child: Text('Yes'),
//                                  onPressed: () {
//                                    removeFromCart(productID);
//                                    Navigator.pop(context);
//                                  },
//                                ),
//                                FlatButton(
//                                  child: Text('No'),
//                                  onPressed: () => Navigator.pop(context),
//                                ),
//                              ],
//                              elevation: 25.0,
//                            ),
//                            barrierDismissible: true,
//                          );
//                        },),
//                      ),
//                    ],
//                  ),
//                )
//            );
//          });
//        }
//        setState(() {
//          cartList.add(
//            SizedBox(width: 100.0,),
//          );
//        });
//      }
//    }
//  }
}
