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

    beforeEach(function(done){
        this.timeout(10000);
        require('./../../../test-dom.js')();
        done();
    });


    it('renders correct number of ul & li', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = React.render(<CategoryMenuLevel items={items}/>, document.body);

        var uls = TestUtils.scryRenderedDOMComponentsWithTag(menu, 'ul');
        var lis = TestUtils.scryRenderedDOMComponentsWithTag(menu, 'li');

        assert.equal(uls.length, 2);
        assert.equal(lis.length, 5);
    });


    it('renders active trail if path param passed', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = React.render(
            <CategoryMenuLevel items={items} path={['kytarove-nastroje', 'akusticke-a-klasicke-kytary']}/>,
            document.body
        );

        var active = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'active');
        assert.equal(active.length, 2);
    });


    it('renders no active trail if path param is not passed', function() {

        var CategoryMenuLevel = require('../category-menu-level.js');

        var menu = React.render(
            <CategoryMenuLevel items={items} path={[]}/>,
            document.body
        );

        var active = TestUtils.scryRenderedDOMComponentsWithClass(menu, 'active');
        assert.equal(active.length, 0);
    });
});