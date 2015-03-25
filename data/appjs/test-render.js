"use strict";

var jsdom = require('jsdom').jsdom;
var document = jsdom('<html><body></body></html>');

global.document = document;
global.window = document.defaultView;
global.navigator = window.navigator = {};
global.navigator.userAgent = 'NodeJs JsDom';
global.navigator.appVersion = '';

var React = require('react/addons');

var TestUtils = React.addons.TestUtils;
var Router = require('react-router');
var Route = Router.Route;
var _ = require('lodash');
var TestLocation = require('react-router/modules/locations/TestLocation.js');

var Dispatcher = require('./src/bootstrap-dispatcher.js');
var morearty = require('./src/bootstrap-morearty.js')(true);
var dispatcher = new Dispatcher({
    morearty: morearty
});

module.exports = function render(component, links, props) {

    var routes = [
        React.createFactory(Route)({
            name: 'test',
            handler: component
        })
    ];

    _.each(links, function(link) {
        routes.push(React.createFactory(Route)({
            name: link,
            handler: component
        }));
    });

    var context = {
        dispatcher: dispatcher,
        morearty: morearty
    };

    var renderedComponent;

    TestLocation.history = ['/test'];
    Router.run(routes, TestLocation, function(Handler) {

        var mainComponent = React.withContext(context, function () {
            return React.render(React.createFactory(Handler)(props), document.createElement('div'));
        });

        // If nesting components, we take first component of type (we presume breadth first search)
        renderedComponent = TestUtils.scryRenderedComponentsWithType(mainComponent, component)[0];

    });

    return renderedComponent;


};