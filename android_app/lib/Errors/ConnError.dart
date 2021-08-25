import 'package:auto_size_text/auto_size_text.dart';
import 'package:flutter/material.dart';

class ConnError extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Container(
              child: Center(
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: MediaQuery.of(context).padding.horizontal+20.0),
                  child: AutoSizeText(
                    'Connection Error !, Please Check your Network Connection',
                    maxLines: 2,
                    maxFontSize: 30.0,
                    minFontSize: 20.0,
                    textAlign: TextAlign.center,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
