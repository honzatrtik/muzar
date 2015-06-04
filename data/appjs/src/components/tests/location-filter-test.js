"use strict";

// This must be first!
var render = require('../../../test-render.js');

var React = require('react/addons');
var TestUtils = React.addons.TestUtils;
var assert = require('assert');
import Imm from 'immutable';


var regions = Imm.fromJS([
    {
        "name": "Středočeský kraj"
    },
    {
        "name": "Karlovarský kraj",
        "districts": [
            {
                "name": "Sokolov"
            },
            {
                "name": "Karlovy Vary"
            }
        ]
    }
]);



var routeNames = ['detail', 'list', 'search', 'listAll', 'home'];

describe('LocationFilter', function() {

    beforeEach(function(done){
        this.timeout(5000);
        done();
    });


    it('renders region list', function(done) {

        var LocationFilter = require('../location-filter.js');

        var locationFilter = render(LocationFilter, routeNames, {
            regions: regions,
            to: 'list',
            query: Imm.Map({}),
            params: Imm.Map({})
        });

        var regionList = TestUtils.scryRenderedDOMComponentsWithClass(locationFilter, 'location-filter-region-list');
        var districtList = TestUtils.scryRenderedDOMComponentsWithClass(locationFilter, 'location-filter-district-list');
        assert.equal(regionList.length, 1);
        assert.equal(districtList.length, 2);

        done();

    });


});