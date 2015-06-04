"use strict";

var React = require('react/addons');
var cs = React.addons.classSet;

var PriceFilter = React.createClass({

    propTypes: {
        onSubmit: React.PropTypes.func
    },

    getInitialState: function() {
        return {
            priceFrom: (this.isPositiveIntegerOrEmpty(this.props.priceFrom) && this.props.priceFrom) || '',
            priceTo: (this.isPositiveIntegerOrEmpty(this.props.priceTo) && this.props.priceTo) || ''
        };
    },

    componentWillReceiveProps: function(nextProps) {
        this.setState({
            priceFrom: (this.isPositiveIntegerOrEmpty(nextProps.priceFrom) && nextProps.priceFrom) || '',
            priceTo: (this.isPositiveIntegerOrEmpty(nextProps.priceTo) && nextProps.priceTo) || ''
        });
    },

    isPositiveIntegerOrEmpty: function(value) {
        var n = parseInt(value);
        return (String(n) === value && n >= 0)
            || value === '';
    },

    handleSubmit: function(event) {
        event.preventDefault();
        var handler = this.props.onSubmit;
        if (handler) {
            handler(this.state);
        }
    },


    handleInputChange: function(name, event) {

        var value = event.target.value;
        if (this.isPositiveIntegerOrEmpty(value)) {
            var state = {};
            state[name] = value;
            this.setState(state);
        }
    },

    render: function () {

        return (
            <form onSubmit={this.handleSubmit} className="form-inline">
                <div className="form-group">
                    <label htmlFor="priceFrom">od</label>
                    <input onChange={this.handleInputChange.bind(this, 'priceFrom')} value={this.state.priceFrom} type="input" className="form-control" id="priceFrom" />
                </div>
                <div className="form-group">
                    <label htmlFor="priceTo">do</label>
                    <input onChange={this.handleInputChange.bind(this, 'priceTo')} value={this.state.priceTo} type="input" className="form-control" id="priceTo" />
                </div>
                <button type="submit" className="btn btn-default">použít</button>
            </form>
        );
    }

});

module.exports = PriceFilter;
