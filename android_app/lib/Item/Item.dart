import 'dart:convert';
import 'package:app/Item/sizeItem.dart';
import 'package:app/modules/MyDrawer.dart';
import 'package:app/modules/constants.dart';
import 'package:auto_size_text/auto_size_text.dart';
import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;

Response response;
Dio dio = new Dio();
final storage = new Storage.FlutterSecureStorage();

class Items extends StatefulWidget {
  final String product;
  Items(this.product);
  @override
  _ItemsState createState() => _ItemsState(this.product);
}

class _ItemsState extends State<Items> {
  String product;
  _ItemsState(this.product);
  bool _last = false;

  @override
  void initState() {
    this._getMoreData();
    super.initState();
    _scrollController.addListener(() {
      if (_scrollController.position.pixels ==
          _scrollController.position.maxScrollExtent) {
        _getMoreData();
      }
    });
  }
  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  _getMoreData() async {
    if (!isLoading) {
      setState(() {
        isLoading = true;
      });

      String username = await storage.read(key: 'username');
      String apiKey = await storage.read(key: 'apiKey');
        final url = "$apiServer/android/getProductDetails";
        var formData = {
          "username": username,
          "api_key": apiKey,
          "product_name": product,
          "type": 'cloth',
          "show": showCards,
          "max": names.length,
        };
        Response response = await dio.post(url,
        data: formData,
        options: Options(contentType: Headers.formUrlEncodedContentType));
        dynamic convertString = (response.data);
        Map jsonData = json.decode(convertString) as Map;
        var error = (jsonData['error']);
        if (error) {
          setState(() {
            _last = true;
            names.add(Center(
              child: Card(
                color: itemCardColor,
                child: InkWell(
                  splashColor: Colors.blue.withAlpha(30),
                  child: Container(
                    width: MediaQuery.of(context).size.width,
                    height: itemCardSize,
                    child: Center(
                      child: AutoSizeText(
                        'Thats the End of the List !',
                        style: displayChoice,
                      ),
                    ),
                  ),
                ),
              ),
            ));
          });
        } else {
          setState(() {
            tempList.clear();
          });
          for (var x = 0; x < jsonData['data'].length; x++) {
            var clothId = jsonData['data'][x]['cloth_id'];
            var clothName = jsonData['data'][x]['cloth_name'];
            tempList.add(
                Center(
                  child: Card(
                    elevation: 20.0,
                    color: itemCardColor,
                    child: InkWell(
                      splashColor: Colors.blue.withAlpha(30),
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => SizeItems(product, clothName),),
                        );
                      },
                      child: Container(
                        width: MediaQuery.of(context).size.width,
                        height: itemCardSize,
                        child: Center(
                          child: AutoSizeText(
                              clothName,
                            style: displayChoice,
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
            );
          }
          setState(() {
                isLoading = false;
                names.addAll(tempList);
          });
        }
    }
  }

  ScrollController _scrollController = new ScrollController();
  bool isLoading = false;
  List<Widget> names = List();
  List<Widget> tempList = List();

  @override
  Widget build(BuildContext context) {
    return Hero(
      tag: 'Item',
      child: Scaffold(
        appBar: AppBar(
          title: Text(
            title,
            style: TextStyle(fontFamily: "Mr.Dafoe", fontSize: 30.0),
          ),
          centerTitle: true,
          automaticallyImplyLeading: true,
        ),
        body: SafeArea(
          child: Container(
            child: _buildList(),
          ),
        ),
      ),
    );
  }

  Widget _buildProgressIndicator() {
    return new Padding(
      padding: const EdgeInsets.all(8.0),
      child: new Center(
        child: new Opacity(
          opacity: isLoading ? 1.0 : 00,
          child: new CircularProgressIndicator(),
        ),
      ),
    );
  }

  Widget _buildList() {
    return ListView.builder(
      physics: BouncingScrollPhysics(),
      //+1 for progressbar
      itemCount: names.length + 1,
      itemBuilder: (BuildContext context, int index) {
        if (index == names.length) {
          return _last == true ? null : _buildProgressIndicator();
        } else {
          return new ListTile(
            title: names[index],
          );
        }
      },
      controller: _scrollController,
    );
  }
}


