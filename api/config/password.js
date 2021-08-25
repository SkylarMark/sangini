var passwordValidator = require('password-validator');
var schema = new passwordValidator();

var notallowed = [
    'Passw0rd', 
    'Password123', 
    'Password', 
    'Admin123$',
    'Test123$'
];

    // Add properties to it
    schema
    .is().min(8)                                    // Minimum length 8
    .is().max(20)                                   // Maximum length 100
    .has().uppercase()                              // Must have uppercase letters
    .has().lowercase()                              // Must have lowercase letters
    .has().digits()                                 // Must have digits
    .has().not().spaces()                           // Should not have spaces
    .has().symbols()                                // Should Have Symbols
    .is().not().oneOf(notallowed);                  // Blacklist these values

module.exports = schema;