import 'package:auto_size_text/auto_size_text.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart' as Storage;

final storage = new Storage.FlutterSecureStorage();

class MyDrawer extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
      padding: EdgeInsets.zero,
      children: <Widget>[
        DrawerHeader(
          decoration: BoxDecoration(
            color: Colors.pink.shade500,
          ),
          child: Column(
            children: <Widget>[
              SizedBox(height: 20.0,),
              AutoSizeText(
                'Sangini',
                style: TextStyle(
                    color: Colors.white,
                    fontSize: 24,
                    fontFamily: 'Mr.Dafoe'
                ),
              ),
              AutoSizeText('The Coolest Cotton', style: TextStyle(
                  color: Colors.white,
                  fontSize: 24,
                  fontFamily: 'Mr.Dafoe'
              ),
              ),
            ],
          )
        ),
        ListTile(
          onTap: () {
            logout(context);
          },
          leading: Icon(FontAwesomeIcons.signOutAlt,
            color: Colors.pink.shade500,
          ),
          title: AutoSizeText('Logout',
            style: TextStyle(
              color: Colors.pink.shade500,
            ),
          ),
        ),
      ],
    ),
    );
  }

  logout(context) async {
    await storage.deleteAll();
    Navigator.of(context).pushNamedAndRemoveUntil('/login', (Route<dynamic> route) => false);
  }
}
