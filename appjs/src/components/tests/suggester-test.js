"use strict";

// This must be first!
var render = require('../../../test-render.js');

var React = require('react/addons');
var TestUtils = React.addons.TestUtils;
var assert = require('assert');
var Promise = require('es6-promise').Promise;


var suggestionsFunction = function(query) {
    return {
        "categories": [
            {
                "strId": "akusticke-a-klasicke-kytary",
                "name": "Akustické a klasické kytary",
                "path": ["Kytary", "Akustické a klasické kytary"]
            },
            {
                "strId": "akusticke-kontrabasy",
                "name": "Akustické a kontrabasy",
                "path": ["Baskytary", "Akustické a kontrabasy"]
            }
        ],
        "ads": [
            {
                "id": 1,
                "name": "Akusticka kytara Jumbo",
                "image_url": "http://lorempixel.com/300/200",
                "district": "Olomoucký kraj",
                "price": 900,
                "description": "",
                "created": "2013-12-12 12:45:30",
                "url": "/ads/10"
            },
            {
                "id": 2,
                "name": "Kontrabas akusticky, preklizka",
                "image_url": "http://lorempixel.com/300/200",
                "district": "Královehradecký kraj",
                "price": 20000,
                "description": "",
                "created": "2013-12-12 12:45:30",
                "url": "/ads/2"
            }
        ]
    };
};

var routeNames = ['detail', 'list', 'search', 'listAll', 'home'];

describe('Suggester', function() {

    beforeEach(function(done){
        this.timeout(5000);
        done();
    });


    it('render suggestion box on input', function(done) {

        var Suggester = require('../suggester.js');

        var suggester = render(Suggester, routeNames, {
            suggestionsFunction: suggestionsFunction
        });

        var input = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-input');
        assert.equal(input.length, 1, 'Exactly one .suggester-input must exist');
        TestUtils.Simulate.change(input[0].getDOMNode(), { target: { value: 'akust' } });

        setTimeout(function() {

            var box = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-suggestionsBox');
            assert.equal(box.length, 1);

            var items = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-suggestionsBox-item');
            assert.equal(items.length, 5);

            done();
        }, 1000)
    });

    it('render suggestion box on input if suggestionsFunction returns promise', function(done) {

        var Suggester = require('../suggester.js');

        var suggester = render(Suggester, routeNames, {
            suggestionsFunction: function(query) {
                return new Promise(function(resolve, reject) {
                    resolve(suggestionsFunction(query));
                });
            }
        });

        var input = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-input');
        assert.equal(input.length, 1, 'Exactly one .suggester-input must exist');
        TestUtils.Simulate.change(input[0].getDOMNode(), { target: { value: 'akust' } });

        setTimeout(function() {

            var box = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-suggestionsBox');
            assert.equal(box.length, 1);

            done();
        }, 10)
    });


    it('closes suggestion box and clear query on escape', function(done) {

        var Suggester = require('../suggester.js');

        var suggester = render(Suggester, routeNames, {
            suggestionsFunction: function(query) {
                return new Promise(function(resolve, reject) {
                    resolve(suggestionsFunction(query));
                });
            }
        });

        var input = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-input');
        TestUtils.Simulate.change(input[0].getDOMNode(), { target: { value: 'akust' } });

        setTimeout(function() {

            TestUtils.Simulate.keyDown(input[0].getDOMNode(), { keyCode: 27 });

            var box = TestUtils.scryRenderedDOMComponentsWithClass(suggester, 'suggester-suggestionsBox');
            assert.equal(box.length, 0);
            assert.equal(input[0].getDOMNode().value, 0);

            done();
        }, 10)
    });

});