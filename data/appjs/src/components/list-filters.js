"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var cs = React.addons.classSet;
var { DropdownButton, ButtonGroup, Glyphicon, Button } = require('react-bootstrap');
var AdStore = require('../stores/ad-store.js');
var RouteStore = require('../stores/route-store.js');
var PriceFilter = require('./price-filter.js');
var LocationFilter = require('./location-filter.js');
var Router = require('react-router');
var Imm = require('immutable');


var ListFilters = React.createClass({

    mixins: [Morearty.Mixin, Router.Navigation, DispatcherMixin],

    handlePriceFilterSubmit: function(values) {
        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery()
            .delete('priceFrom')
            .delete('priceTo')
            .merge(Imm.Map(values).filter(v => v)).toJS();
        this.transitionTo(routeStore.getRoute(), routeStore.getParam().toJS(), query);

        this.refs.priceFilterDropdown.setDropdownState(false);
    },

    handleLocationChange: function(values) {
        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery()
            .delete('district')
            .delete('region')
            .merge(Imm.Map(values).filter(v => v)).toJS();
        this.transitionTo(routeStore.getRoute(), routeStore.getParam().toJS(), query);

        this.refs.locationFilterDropdown.setDropdownState(false);
    },

    handlePriceFilterResetClick: function(event) {
        event.stopPropagation();

        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery().delete('priceFrom').delete('priceTo').toJS();
        this.transitionTo(routeStore.getRoute(), routeStore.getParam().toJS(), query);
    },

    handleLocationResetClick: function(event) {
        event.stopPropagation();

        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery().delete('district').delete('region').toJS();
        this.transitionTo(routeStore.getRoute(), routeStore.getParam().toJS(), query);
    },

    render: function () {

        this.observeBinding(this.getStoreBinding(RouteStore));

        var routeStore = this.getStore(RouteStore);

        var priceFrom = routeStore.getQuery('priceFrom');
        var priceTo = routeStore.getQuery('priceTo');

        
        var priceTitle = [];
        if (priceFrom) {
            priceTitle = priceTitle.concat(['od', priceFrom]);
        }
        if (priceTo) {
            priceTitle = priceTitle.concat(['do', priceTo]);
        }
        if (!priceTitle.length) {
            priceTitle.push('nezáleží');
        }

        var regions = Imm.fromJS([
            {
                "name": "Středočeský kraj"
            },
            {
                "name": "Karlovarský kraj",
                "children": [
                    {
                        "name": "Sokolov"
                    },
                    {
                        "name": "Karlovy Vary"
                    }
                ]
            }
        ]);

        var region = routeStore.getQuery('region');
        var district = routeStore.getQuery('district');

        var locationTitle = [];
        if (region) {
            locationTitle = locationTitle.concat([region]);
        }
        if (district) {
            locationTitle = locationTitle.concat(['okres', district]);
        }
        if (!locationTitle.length) {
            locationTitle.push('nezáleží');
        }
        
        
        return (
            <div>

                Cena:
                <ButtonGroup>
                    <DropdownButton ref="priceFilterDropdown" title={priceTitle.join(' ')}>
                        <li><PriceFilter priceFrom={priceFrom} priceTo={priceTo} onSubmit={this.handlePriceFilterSubmit} /></li>
                    </DropdownButton>

                    <Button onClick={this.handlePriceFilterResetClick}><Glyphicon glyph="remove" /></Button>
                </ButtonGroup>

                Místo:
                <ButtonGroup>
                    <DropdownButton ref="locationFilterDropdown" title={locationTitle.join(' ')}>
                        <li><LocationFilter regions={regions} region={region} district={district} onLocationChange={this.handleLocationChange} /></li>
                    </DropdownButton>
                    <Button onClick={this.handleLocationResetClick}><Glyphicon glyph="remove" /></Button>
                </ButtonGroup>

            </div>
        );
    }

});

module.exports = ListFilters;
