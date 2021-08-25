import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;
import 'package:dio/dio.dart';
import 'package:app/modules/constants.dart';

// Instances for Plugins
Response response;
Dio dio = new Dio();
final storage = new Storage.FlutterSecureStorage();

// AutoLogin Controller
class AutoLoginController extends StatefulWidget {
  @override
  _AutoLoginControllerState createState() => _AutoLoginControllerState();
}

class _AutoLoginControllerState extends State<AutoLoginController> {
  
  @override
  void initState() {
    super.initState();
    checkLogin();
  }

  @override
  void dispose() {
    super.dispose();
  }

  

  // Responsible to Check if user session data is set
  checkLogin() async {
    String username = await storage.read(key: 'username');
    String apiKey = await storage.read(key: 'apiKey');
    dynamic latitude = 0;
    dynamic longitude = 0;
    try {
    // IF-ELSE for Redirection to Login or Dashboard
    if (username != null && apiKey != null) {
      final url = "$apiServer/android/loginlog";
      //TODO: Get Location Lat,Long and Post them to API

      var formData = {
        "username": username,
        "api_key": apiKey,
        "latitude": latitude,
        "longitude": longitude,
        "os": os,
      };

        Response response = await dio.post(url, data: formData,
            options: Options(contentType: Headers.formUrlEncodedContentType));
        dynamic convertString = (response.data);
        Map jsonData = json.decode(convertString) as Map;
        var error = (jsonData['error']);
        if (error) {
          setState(() {
            latitude = 12;
            longitude = 24;
          });
          var errorMsg = (jsonData['data']);
          print(errorMsg);
          await storage.deleteAll();
          Navigator.of(context).pushNamedAndRemoveUntil(
              '/login', (Route<dynamic> route) => false);
        } else {
          var data = (jsonData['data']);
          print(data);
          Navigator.of(context).pushNamedAndRemoveUntil(
              '/dashboard', (Route<dynamic> route) => false);
        }
      } else
        {
          Navigator.of(context).pushNamedAndRemoveUntil(
              '/login', (Route<dynamic> route) => false);
        }
    }
    catch(error)
    {
    Navigator.of(context).pushNamedAndRemoveUntil('/connectionerror', (Route<dynamic> route) => false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(),
    );
  }
}
