"use strict";

// This must be first!
var render = require('../../../test-render.js');
var _ = require('lodash');
var React = require('react/addons');
var TestUtils = React.addons.TestUtils;
var assert = require('assert');
var Promise = require('../../promise.js');


var items = [
    {
        "id": "kytarove-nastroje",
        "name": "Kytarové nastroje",
        "newSince": 10,
        "children": [
            {
                "id": "elektricke-kytary",
                "name": "Elektrické kytary",
                "newSince": 15,
                children: [
                    {
                        "id": "lp",
                        "name": "LP",
                        "newSince": 15
                    },
                    {
                        "id": "strat",
                        "name": "Strat",
                        "newSince": 15
                    }
                ]
            },
            {
                "id": "kytarove-aparaty",
                "name": "Kytarové aparáty",
                "newSince": 1
            },
            {
                "id": "akusticke-a-klasicke-kytary",
                "name": "Akustické a klasické kytary",
                "newSince": 2
            }
        ]
    },
    {
        "id": "bici-nastroje",
        "name": "Bicí nastroje",
        "newSince": 28
    }
];


var routeNames = ['detail', 'list', 'search', 'listAll', 'home'];

describe('CategoryMultiselect', function() {

    beforeEach(function(done){
        this.timeout(5000);
        done();
    });


    it('render three select boxes', function(done) {

        var CategoryMultiselect = require('../category-multiselect.js');

        var cm = render(CategoryMultiselect, routeNames, {
            items
        });

        var selects = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'select');
        assert.equal(selects.length, 3);
        var options = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'option');
        assert.equal(options.length, 5); // 2 top level + 3 empty
        done();

    });

    it('changes second select if value of first select is selected', function(done) {

        var CategoryMultiselect = require('../category-multiselect.js');

        var cm = render(CategoryMultiselect, routeNames, {
            items
        });

        var selects = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'select');
        TestUtils.Simulate.change(selects[0].getDOMNode(), { target: { value: 'kytarove-nastroje' } });

        setTimeout(function() {

            var options = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'option');
            assert.equal(options.length, 8); // +3 empty options
            done();
        }, 10);

    });

    it('reflects value prop', function(done) {

        var CategoryMultiselect = require('../category-multiselect.js');

        var cm = render(CategoryMultiselect, routeNames, {
            items,
            value: ['kytarove-nastroje', 'elektricke-kytary']
        });

        var selects = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'select');
        assert.equal(selects[0].getDOMNode().value, 'kytarove-nastroje');
        assert.equal(selects[1].getDOMNode().value, 'elektricke-kytary');
        assert.equal(selects[2].getDOMNode().value, '');
        done();


    });

    it('calls onChange prop function if value is changed', function(done) {

        var CategoryMultiselect = require('../category-multiselect.js');

        var cm = render(CategoryMultiselect, routeNames, {
            items,
            value: ['kytarove-nastroje', 'elektricke-kytary'],
            onChange: function(value) {
                assert.deepEqual(value, ['kytarove-nastroje', 'akusticke-a-klasicke-kytary']);
                done();
            }
        });

        var selects = TestUtils.scryRenderedDOMComponentsWithTag(cm, 'select');
        TestUtils.Simulate.change(selects[1].getDOMNode(), { target: { value: 'akusticke-a-klasicke-kytary' } });

    });

});