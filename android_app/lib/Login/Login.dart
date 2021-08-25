import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:auto_size_text/auto_size_text.dart';
import 'package:app/modules/constants.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;
import 'package:dio/dio.dart';

// Global Key for Snack Bar
final GlobalKey<ScaffoldState> _scaffoldKey = new GlobalKey<ScaffoldState>();

// Instances for Plugins
Response response;
Dio dio = new Dio();
final storage = Storage.FlutterSecureStorage();

class Login extends StatefulWidget {
  @override
  _LoginState createState() => _LoginState();
}

class _LoginState extends State<Login> {

  bool _obscureText = true; // Show - Hide Password
  Color _obscureColor = Colors.lightGreen; //Color of Eye for Obscure Icon
  final _usernameController = TextEditingController(); // Username Controller
  final _passwordController = TextEditingController(); // Password Controller

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      key: _scaffoldKey,
      body: SafeArea(
        child: LayoutBuilder(
          builder: (BuildContext context, BoxConstraints viewportConstraints) {
            return SingleChildScrollView(
              child: ConstrainedBox(
                constraints:
                    BoxConstraints(minHeight: viewportConstraints.maxHeight),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: <Widget>[
                    Padding(
                      padding: const EdgeInsets.only(bottom: 20.0),
                      child: Column(
                        children: <Widget>[
                          Image(
                            image: AssetImage('assets/logo.png'),
                            height: MediaQuery.of(context).size.height / 5,
                            width: MediaQuery.of(context).size.width,
                          ),
                          SizedBox(
                            height: 20.0,
                          ),
                        ],
                      ),
                    ),
                    Form(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: <Widget>[
                          SizedBox(
                            height: 20.0,
                          ),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 60.0),
                            child: TextFormField(
                              controller: _usernameController,
                              decoration: InputDecoration(
                                  hintText: 'Enter Username',
                                  labelText: 'Username'),
                              keyboardType: TextInputType.emailAddress,
                            ),
                          ),
                          SizedBox(
                            height: 40.0,
                          ),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 60.0),
                            child: TextFormField(
                              controller: _passwordController,
                              decoration: InputDecoration(
                                  suffixIcon: GestureDetector(
                                    onTap: () {
                                      setState(() {
                                        _obscureColor == Colors.lightGreen
                                            ? _obscureColor = Colors.red
                                            : _obscureColor = Colors.lightGreen;
                                        _obscureText
                                            ? _obscureText = false
                                            : _obscureText = true;
                                      });
                                    },
                                    child: Icon(
                                      Icons.remove_red_eye,
                                      color: _obscureColor,
                                    ),
                                  ),
                                  hintText: 'Enter Password',
                                  labelText: 'Password'),
                              keyboardType: TextInputType.text,
                              obscureText: _obscureText,
                            ),
                          ),
                          SizedBox(
                            height: 40.0,
                          ),
                          SizedBox(
                            height: 40.0,
                            width: 280.0,
                            child: RaisedButton(
                              child: AutoSizeText(
                                'Login',
                                style: TextStyle(
                                    fontSize: 20, fontWeight: FontWeight.w500),
                              ),
                              onPressed: () {
                                login(
                                    username: _usernameController.text,
                                    password: _passwordController.text);
                              },
                              splashColor: Colors.white,
                            ),
                          ),
                        ],
                      ),
                      autovalidate: true,
                    ),
                    Padding(
                      padding: const EdgeInsets.only(top: 40.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: <Widget>[
                          FlatButton(
                            child: Text(
                              'Forgot Password ?',
                              style: TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.w400,
                              ),
                            ),
                            onPressed: () {},
                          ),
                        ],
                      ),
                    )
                  ],
                ),
              ),
            );
          },
        ),
      ),
    );
  }

  // Login Function
  login({String username, String password}) async {
    // URL of Api
    const url = '$apiServer/login';

    // Form Data
    var formData = {"userlogin": username, "password": password};

    // API Request
    Response response = await dio.post(url,
        data: formData,
        options: Options(contentType: Headers.formUrlEncodedContentType));

    // Received Data and Conversion
    dynamic convertString = (response.data);
    Map jsonData = json.decode(convertString) as Map;

    // Parsing and Login Accordingly
    var error = (jsonData['error']);
    if (error) {
      var errorMsg = (jsonData['data']);
      print(errorMsg);
      _scaffoldKey.currentState.showSnackBar(
        SnackBar(
          content: Text(errorMsg),
          backgroundColor: Colors.pink.shade400,
          duration: Duration(seconds: 3),
        ),
      );
    } else {
      final username = (jsonData['data']['username']);
      final apiKey = (jsonData['data']['api_key']);

      await storage.deleteAll();
      await storage.write(key: 'username', value: username);
      await storage.write(key: 'apiKey', value: apiKey);
      Navigator.of(context).pushNamedAndRemoveUntil(
          '/dashboard', (Route<dynamic> route) => false);
    }
  }
}
