"use strict";

import React from 'react/addons';
import DispatcherMixin from '../dispatcher-mixin.js';
import RouteStore from '../stores/route-store.js';
import Router from 'react-router';
import { Link } from 'react-router';
import Morearty from 'morearty';
import Imm from 'immutable';

var cs = React.addons.classSet;

export default React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    propTypes: {
        region: React.PropTypes.string,
        district: React.PropTypes.string,
        regions: React.PropTypes.instanceOf(Imm.List).isRequired,
        onLocationChange: React.PropTypes.func
    },


    handleClick: function(region, district, event) {
        event.preventDefault();
        if (this.props.onLocationChange) {

            var location = {
                region: region.get('name')
            };

            if (district) {
             location.district = district.get('name');
            }

            this.props.onLocationChange(location);
        }
    },

    renderRegion: function(region) {

        var districts = region.get('children', Imm.List());
        return (
            <li key={region.get('name')}>
                <h3><a onClick={this.handleClick.bind(this, region, null)}>{region.get('name')}</a></h3>

                <ul className="locationFilter-districtList">
                    {!!districts.size && districts.map(this.renderDistrict.bind(this, region)).toArray()}
                </ul>
            </li>
        );
    },

    renderDistrict: function(region, district) {

        return (
            <li key={district.get('name')}>
                <a onClick={this.handleClick.bind(this, region, district)}>{district.get('name')}</a>
            </li>
        );
    },


    render: function() {

        return (
            <div className="locationFilter">
                <ul className="locationFilter-regionList">
                    {this.props.regions.map(this.renderRegion).toArray()}
                </ul>
            </div>
        );
    }

});
