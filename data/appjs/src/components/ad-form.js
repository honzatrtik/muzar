"use strict";

var _ = require('lodash');
var Morearty = require('morearty');
var React = require('react/addons');
//var ReactForms = require('react-forms');
//
//var Mapping = ReactForms.schema.Mapping;
//var Scalar = ReactForms.schema.Scalar;
//var List = ReactForms.schema.List;
//
//var Form = ReactForms.Form;
//
//var ad = Mapping({}, {
//    name: Scalar({
//        label: 'Název',
//        required: true
//    }),
//    price: Scalar({
//        type: 'number',
//        label: 'Cena',
//        validate: function(schema, value) {
//            if (value <= 0) {
//                return new Error('Cena musí být vetší nž nula.');
//            }
//        }
//    }),
//    category: Scalar({
//        label: 'Kategorie'
//    }),
//    negotiatedPrice: Scalar({
//        type: 'bool',
//        label: 'Cena dohodou',
//        input: <input type="checkbox" />
//    }),
//    description: Scalar({
//        label: 'Popis',
//        input: <textarea />
//    })
//});

/*


var AdForm = React.createClass({

    getInitialState: function() {
        return {
            values: {},
            props: {}
        };
    },


    createFormElement: function() {



        React.createClass({
            propTypes: {
                path: React.PropTypes.string.isRequired
            },
            render: function () {
                var pathSplit = this.props.path.split('.');
                var {component, value, onChange,...props} = this.props;
                component = component || React.DOM.input;
                return React.createElement(component, props);
                return <input {...props['price']} ref="price" onChange={self.handleChange.bind(self, path)} value={values.price}/>
            }
        });
    }


    handleChange: function(path, event) {

        var values = this.state.values;
        values[name] = event.target.value;
        console.log(path);
    },


    render: function () {

        var cs = React.addons.classSet;

        var values = this.state.values;
        var props = this.state.props;

        return (
            <form>
                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="form-group">
                        <label>Název prodávané věci</label>
                        <input type="email" className="form-control" placeholder="např. Gibson 1956 Les Paul Goldtop" />
                    </div>
                </div>

                <div className="row no-margin">
                    <div className="col-xs-12 col-sm-4 col-md-4">
                        <div className="form-group">
                            <label>Hlavní kategorie</label>
                            <select className="form-control">
                                <option>-</option>
                                <option>Basy</option>
                                <option>Bici</option>
                            </select>
                        </div>
                    </div>
                    <div className="col-xs-12 col-sm-4 col-md-4">
                        <div className="form-group">
                            <label>Podkategorie</label>
                            <select className="form-control" disabled="disabled">
                                <option>-</option>
                                <option>Akusticke kytary</option>
                                <option>Elektro-akustiky</option>
                            </select>
                        </div>
                    </div>
                    <div className="col-xs-12 col-sm-4 col-md-4">
                        <div className="form-group">
                            <label>Přesné zařazení</label>
                            <span className="help-block-inline">(Volitelné)</span>
                            <select className="form-control" disabled="disabled">
                                <option>-</option>
                                <option>Akusticke kytary</option>
                                <option>Elektro-akustiky</option>
                            </select>

                        </div>
                    </div>
                </div>

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
                                <input {...props['price']} type="text" className="form-control" placeholder="např. 1000" ref="price" onChange={this.handleChange.bind(this, 'price')} value={values.price}/>
                                <span className="pull-right">Kč</span>
                            </div>
                            <div className="col-xs-12 col-sm-3 col-md-3">
                                <input type="checkbox" ref="negotiatedPrice" onChange={this.handleChange.bind(this, 'negotiatedPrice')} value={values.negotiatedPrice} />
                                Cena dohodou
                            </div>
                            <div className="col-xs-12 col-sm-5 col-md-5">
                                <input type="checkbox" />
                                Jsem ochoten zboží poslat poštou
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
    */