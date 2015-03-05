"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var CategoryStore = require('../stores/category-store.js');
var cs = React.addons.classSet;

var CategoryMultiselect = React.createClass({

    propTypes: {
        items: React.PropTypes.array.isRequired,
        value: React.PropTypes.array,
        onChange: React.PropTypes.func
    },

    getInitialState: function() {
        return {
            value: this.props.value || []
        };
    },

    renderCategoryOption: function(item) {
        return (
            <option key={item.str_id} value={item.str_id}>{item.name}</option>
        );
    },

    handleSelectChange: function(index, event) {
        var value = this.state.value;
        value[index] = event.target.value;

        value = value.slice(0, index + 1).filter((v) => v.length);


        var self = this;
        var onChange = this.props.onChange;
        this.setState(value);
        if (onChange) {
            onChange(value);
        }
    },

    findItemChildren(strId) {
        if (!strId) {
            return [];
        }

        var createMap = function(items, map) {
            items.forEach(category => {
                map[category.str_id] = category;
                createMap(category.children || [], map);
            });
        };

        var map = {};
        createMap(this.props.items, map);
        return map[strId]
            ? map[strId].children || []
            : [];
    },

    render: function() {

        var value = this.state.value;

        var items = [
            this.props.items,
            this.findItemChildren(value[0]),
            this.findItemChildren(value[1])
        ];

        var disabled = [
            !(items[0] && items[0].length),
            !(value[0] && items[1] && items[1].length),
            !(value[1] && items[2] && items[2].length)
        ];

        return (
            <div>
                <div className="col-xs-12 col-sm-4 col-md-4">
                    <div className="form-group">
                        <label>Hlavní kategorie</label>
                        <select onChange={this.handleSelectChange.bind(this, 0)} value={value[0]} className="form-control">
                            <option key=""></option>
                            {items[0].map(this.renderCategoryOption)}
                        </select>
                    </div>
                </div>
                <div className="col-xs-12 col-sm-4 col-md-4">
                    <div className="form-group">
                        <label>Podkategorie</label>
                        <select onChange={this.handleSelectChange.bind(this, 1)} value={value[1]} className="form-control" disabled={disabled[1]}>
                            <option key=""></option>
                            {items[1].map(this.renderCategoryOption)}
                        </select>
                    </div>
                </div>
                <div className="col-xs-12 col-sm-4 col-md-4">
                    <div className="form-group">
                        <label>Přesné zařazení</label>{' '}
                        <span className="help-block-inline">(Volitelné)</span>
                        <select onChange={this.handleSelectChange.bind(this, 2)} value={value[2]} className="form-control" disabled={disabled[2]}>
                            <option key=""></option>
                            {items[2].map(this.renderCategoryOption)}
                        </select>

                    </div>
                </div>
            </div>
        );

    }
});

module.exports = CategoryMultiselect;