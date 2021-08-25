import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import 'package:app/cart/cart.dart';
import 'package:app/modules/constants.dart';
import 'package:app/Login/AutoLoginController.dart';
import 'package:app/Dashboard/Dashboard.dart';
import 'package:app/Login/Login.dart';
import 'package:app/Errors/ConnError.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]).then(
    (_) {
      runApp(
        MaterialApp(
          title: title,
          theme: ThemeData(
            textTheme: TextTheme(
              bodyText1: TextStyle(color: Colors.white)
            ).apply(
              bodyColor: Colors.white,
              displayColor: Colors.pink.shade500,
            ),
            buttonTheme: ButtonThemeData(
              buttonColor: Colors.pink.shade400,
              textTheme: ButtonTextTheme.primary
            ),
            brightness: Brightness.light,
            primaryColor: Colors.pink.shade400,
            accentColor: Colors.pink.shade400,
            fontFamily: 'Motserrat',
          ),
          initialRoute: '/auto',
          routes: {
            '/auto': (context) => AutoLoginController(),
            '/dashboard': (context) => Dashboard(),
            '/login': (context) => Login(),
            '/cart': (context) => Cart(),
            '/connectionerror': (context) => ConnError(),
          },
        ),
      );
    },
  );
}