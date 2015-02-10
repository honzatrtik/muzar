module.exports = function(markup) {
    var jsdom = require('jsdom').jsdom;
    global.document = jsdom(markup || '<html><body></body></html>');
    global.window = document.defaultView;
    global.navigator = window.navigator = {};
    global.navigator.userAgent = 'NodeJs JsDom';
    global.navigator.appVersion = '';
};