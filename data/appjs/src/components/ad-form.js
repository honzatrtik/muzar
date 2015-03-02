"use strict";

var _ = require('lodash');
var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var CategoryMultiselect = require('./category-multiselect.js');
var cs = React.addons.classSet;


var AdForm = React.createClass({

    mixins: [DispatcherMixin],

    getInitialState: function() {
        return {
            values: {
            }
        };
    },

    render: function () {

        var categoryStore = this.getStore(CategoryStore);
        var items = categoryStore.getItems();

        return (
            <form>
                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="form-group">
                        <label>Název prodávané věci</label>
                        <input type="email" className="form-control" placeholder="např. Gibson 1956 Les Paul Goldtop" />
                    </div>
                </div>

                <CategoryMultiselect items={items.toJS()}/>

                <div className="col-xs-12 col-sm-12 col-md-12">

                    <div className="row">
                        <div className="form-group">
                            <div className="col-xs-12 col-sm-6 col-md-6">
                                <label>Cena</label>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="form-group">
                            <div className="col-xs-12 col-sm-4 col-md-4">
                                <input type="text" className="form-control" placeholder="např. 1000" ref="price" />
                                <span className="pull-right">Kč</span>
                            </div>
                            <div className="col-xs-12 col-sm-3 col-md-3">
                                <label>
                                    <input type="checkbox" ref="negotiatedPrice" />{' '}
                                    Cena dohodou
                                </label>
                            </div>
                            <div className="col-xs-12 col-sm-5 col-md-5">
                                <label>
                                    <input type="checkbox" />{' '}
                                    Jsem ochoten zboží poslat poštou
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="form-group">
                        <label>Popis prodávané věci</label>
                        <textarea className="form-control" rows="3" placeholder="např. Stáří, opotřebení"></textarea>
                    </div>
                </div>

                <div className="col-xs-12 col-sm-8 col-md-8">
                    <button type="submit" className="btn btn-primary btn-lg">Pokračovat</button>
                </div>

            </form>
        );


        //return (
        //
        //    <form onSubmit={this.handleSubmit}>
        //        <Form onUpdate={this.handleUpdate} onChange={this.handleChange} ref="form" schema={ad} component="div" />
        //        <button type="submit">Submit</button>
        //    </form>
        //
        //);

    }
});

module.exports = AdForm;
