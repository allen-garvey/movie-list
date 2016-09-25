"use strict";

var path = require('path');
var config = {};


/*
* Sass/Styles configuration
*/
config.styles = {};
config.styles.SOURCE_DIR = path.join(__dirname, 'sass/');
config.styles.DEST_DIR = path.join(__dirname, 'public_html', 'styles/');
config.styles.sass_options = {
  errLogToConsole: true,
  // sourceComments: true, //turns on line number comments 
  outputStyle: 'compressed' //options: expanded, nested, compact, compressed
};



/*
* Export config
*/
module.exports = config;
