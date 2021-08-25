import 'dart:convert';
import 'dart:ui';

import 'package:app/modules/MyDrawer.dart';
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;
import 'package:flutter/material.dart';

import 'package:app/modules/constants.dart';
import 'package:app/Item/Item.dart';

Response response;
Dio dio = new Dio();
final storage = new Storage.FlutterSecureStorage();

class Dashboard extends StatefulWidget {
  @override
  _DashboardState createState() => _DashboardState();
}

class _DashboardState extends State<Dashboard> {

  @override
  void initState() {
    super.initState();
    getProductList();
  }

  getProductList() async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');

    if (username != null && apiKey != null) {
      final url = "$apiServer/android/getProducts";
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
        setState(() {
          _productList.clear();
        });
        for (var x = 0; x < jsonData['data'].length; x++ )
          {
            var productName = (jsonData['data'][x]['product_name']);
            var productImage = (jsonData['data'][x]['product_image']);
            setState(() {
              _productList.add(
                ItemCart(
                  heading: productName,
                  image: productImage.toString(),
                  redirect: productName,
                ),
              );
            });
          }
      }
    }
  }

  List<ItemCart> _productList = [];

  @override
  Widget build(BuildContext context) {
    return Hero(
      tag: 'Item',
      child: Scaffold(
        drawer: MyDrawer(),
        appBar: AppBar(
          title: Text(
            title,
            style: TextStyle(fontFamily: "Mr.Dafoe", fontSize: 30.0),
          ),
          centerTitle: true,
          actions: <Widget>[
            IconButton(
                icon: Icon(Icons.shopping_cart),
              onPressed: () {
                Navigator.pushNamed(context, '/cart');
              },
            ),
            SizedBox(width: 10.0),
          ],
          automaticallyImplyLeading: true,
        ),
        body: SafeArea(
          child: SingleChildScrollView(
            child: Column(
              children: _productList,
            ),
          ),
        ),
      ),
    );
  }
}

class ItemCart extends StatelessWidget {
  ItemCart(
      {@required this.redirect, @required this.image, @required this.heading});

  final String redirect;
  final String image;
  final String heading;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => Items(redirect)),
        );
      },
      child: Stack(
        alignment: Alignment.bottomLeft,
        children: <Widget>[
          Image.network( image.toString(),
            fit: BoxFit.fill,
          ),
          Container(
            color: Colors.black.withOpacity(0.5),
            padding: const EdgeInsets.all(8.0),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                Center(
                  child: Text(
                    heading,
                    style: TextStyle(
                        fontSize: 22,
                        color: Colors.white,
                        fontFamily: "Mr.Dafoe"),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
