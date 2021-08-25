import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';

// Title of The App
const title = 'Sangini';

// Server API
const apiServer = 'http://3.7.20.47:8000';

// Platform Check
final os = Platform.isAndroid ? 'Android' : 'IOS';

// Card Properties
const itemCardSize = 50.0;
const displayChoice = TextStyle(fontSize: 20.0, color: Colors.white, fontWeight: FontWeight.w700);
final itemCardColor = Colors.pink.shade500;
final showCards = 20;