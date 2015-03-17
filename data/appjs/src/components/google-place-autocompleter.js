"use strict";

var debug = require('debug')('Google place autocompleter');
var event = require('dom-event');
var React = require('react/addons');
var Promise = require('../promise.js');
var keyCodes = require('../utils/event-key-codes.js');
var cs = React.addons.classSet;
var Imm = require('immutable');


module.exports = React.createClass({

    displayName: 'GooglePlaceAutocompleter',

    propTypes: {
        input: React.PropTypes.func,
        onChange: React.PropTypes.func
    },

    getInitialState: function() {
        return {
            place: this.props.value || {}
        };
    },

    componentDidMount: function() {

        var maps = google.maps;
        var input = this.refs.input.getDOMNode();

        var autocomplete = this.autocomplete = new maps.places.Autocomplete(input, {
            types: ['(cities)']
        });

        var self = this;
        maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            self.setState({
                place
            });
            if (self.props.onChange) {
                self.props.onChange(place);
            }
        });

        maps.event.addDomListener(input, 'keydown', function(event) {
            if (event.keyCode == keyCodes.ENTER) {
                event.preventDefault();
                google.maps.event.trigger(autocomplete, 'place_changed');
            }
        });
    },

    componentWillUnmount: function() {
        google.maps.event.clearInstanceListeners(this.autocomplete);
    },

    focus: function() {
        var self = this;
        setTimeout(function() {
            self.refs.input.getDOMNode().focus();
        }, 10);
    },

    clear: function() {
        var self = this;
        this.setState({
            place: {}
        }, function() {
            self.refs.input.getDOMNode().value = '';
        });
    },

    render: function() {
        var InputElement = this.props.input || React.DOM.input;
        var { onChange, input, ref, value, ...props } = this.props;
        return <InputElement ref="input" {...props} />;
    }


});
