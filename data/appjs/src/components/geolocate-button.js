"use strict";

var React = require('react');

var GeolocateButton = React.createClass({

    propTypes: {
        onGeolocate: React.PropTypes.func.isRequired,
        onGeolocateError: React.PropTypes.func
    },

    getInitialState: function() {
        return {
            enabled: true,
            loading: false
        }
    },

    componentDidMount: function() {
        this.setState({
            enabled: navigator.geolocation
        });
    },

    handleClick: function(event) {

        var self = this;
        this.setState({
            loading: true
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                self.props.onGeolocate && self.props.onGeolocate(position);
                self.setState({
                    loading: false
                });
            }, function(error) {
                self.props.onGeolocateError && self.props.onGeolocateError(error);
                self.setState({
                    loading: false
                });
            });
        }

        if (this.props.onClick) {
            this.props.onClick(event);
        } else {
            event.preventDefault();
        }

    },

    render: function() {
        let { children, onClick, ...props } = this.props;
        return this.state.loading
            ? <span>Loading....</span>
            : <button disabled={!this.state.enabled} onClick={this.handleClick} {...props}>{children}</button>

    }

});

module.exports = GeolocateButton;