"use strict";

// This must be first!
var render = require('../../../test-render.js');

var React = require('react/addons');
var TestUtils = React.addons.TestUtils;
var assert = require('assert');

var items = [
    {
        "strId": "kytarove-nastroje",
        "name": "Kytarové nastroje",
        "newSince": 10,
        "children": [
            {
                "strId": "elektricke-kytary",
                "name": "Elektrické kytary",
                "newSince": 15
            },
            {
                "strId": "kytarove-aparaty",
                "name": "Kytarové aparáty",
                "newSince": 1
            },
            {
                "strId": "akusticke-a-klasicke-kytary",
                "name": "Akustické a klasické kytary",
                "newSince": 2
            }
        ]
    },
    {
        "strId": "bici-nastroje",
        "name": "Bicí nastroje",
        "newSince": 28
    }
];

describe('CategoryMenuLevel', function() {


    it('renders correct number of ul & li', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = render(CategoryMenuLevel, ['list', 'detail'], {
            items: items
        });

        var uls = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'mainMenu-level');
        var lis = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'mainMenu-level-item');

        assert.equal(uls.length, 1);
        assert.equal(lis.length, 2);
    });


    it('renders active trail if path param passed', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = render(CategoryMenuLevel, ['list', 'detail'], {
            items: items,
            path: ['kytarove-nastroje', 'akusticke-a-klasicke-kytary']
        });

        var active = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'is-active');
        assert.equal(active.length, 2);
    });



    it('renders no active trail if path param is not passed', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = render(CategoryMenuLevel, ['list', 'detail'], {
            items: items,
            path: []
        });

        var active = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'is-active');
        assert.equal(active.length, 0);
    });
});