"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var AdFormStore = require('../stores/ad-form-store.js');
var CategoryMultiselect = require('./category-multiselect.js');
var GooglePlaceAutocompleter = require('./google-place-autocompleter.js');
var cs = React.addons.classSet;
var { adCreateAction } = require('../actions/ad-actions.js');
var Wrapper = require('./form-error-wrapper.js')(AdFormStore);

var AdForm = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    handleSubmit: function(event) {
        event.preventDefault();
        this.executeAction(adCreateAction, this.getStore(AdFormStore).get());
    },

    renderErrors() {
        var store = this.getStore(AdFormStore);
        var errors = store.getErrors();

        if (errors.size) {
            return (
                <ul>
                    {errors.map((error, key) => <li key={key}>{error}</li>).toJS()}
                </ul>
            );
        } else {
            return null;
        }
    },


    handleResetButtonClick: function() {
        var self = this;
        this.executeAction(function(dispatcher) {
            return dispatcher.dispatch('AD_FORM_RESET_PLACE');
        }).then(function() {
            self.refs.autocompleter.clear();
            self.refs.autocompleter.focus();
        });
    },


    renderAutocompleter: function() {

        var store = this.getStore(AdFormStore);
        var value = store.get('contact.place');

        var result = [];

        if (value) {
            result.push(
                <div key="display" className="form-control">
                    <span dangerouslySetInnerHTML={{__html: value.adr_address}}></span>{' '}<a onClick={this.handleResetButtonClick}>změnit</a>
                </div>
            );
        }

        var styles = value
            ? { display: 'none' }
            : {};

        result.push(
            <GooglePlaceAutocompleter
                style={styles}
                key="autocompleter"
                ref="autocompleter"
                id="contact.place"
                value={value}
                onChange={store.getHandler('contact.place')}
                input={Morearty.DOM.input}
                type="text"
                className="form-control"
                placeholder="staci zdata par prvnich pismen do naseptavace" />
        );

        return result;

    },

    render: function () {

        this.observeBinding(this.getStoreBinding(AdFormStore));

        var store = this.getStore(AdFormStore);
        var items = this.getStore(CategoryStore).getItems();

        return (
            <form onSubmit={this.handleSubmit}>

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <Wrapper name="name" className="form-group">
                        <label className="control-label" htmlFor="name">Název prodávané věci</label>
                        <Morearty.DOM.input id="name" value={store.get('name')} onChange={store.getHandler('name')} className="form-control" placeholder="např. Gibson 1956 Les Paul Goldtop" />
                    </Wrapper>
                </div>

                <Wrapper name="category">
                    <CategoryMultiselect value={store.get('category') || []} onChange={store.getHandler('category')}  items={items.toJS()}/>
                </Wrapper>

                <div className="col-xs-12 col-sm-12 col-md-12">

                    <div className="row">
                        <div className="col-xs-12 col-sm-4 col-md-4">
                            <Wrapper name="price" className="form-group">
                                <label className="control-label" id="price">Cena</label>
                                <Morearty.DOM.input id="price" value={store.get('price')} onChange={store.getHandler('price')}  type="text" className="form-control" placeholder="např. 1000" disabled={store.get('negotiatedPrice')} />
                                <span className="pull-right">Kč</span>
                            </Wrapper>
                        </div>
                        <div className="col-xs-12 col-sm-3 col-md-3">
                            <Wrapper name="negotiatedPrice" className="form-group">
                                <br />
                                <label className="control-label">
                                    <Morearty.DOM.input value={store.get('negotiatedPrice')} onChange={store.getHandler('negotiatedPrice')}  type="checkbox"/>{' '}
                                    Cena dohodou
                                </label>
                            </Wrapper>
                        </div>
                        <div className="col-xs-12 col-sm-5 col-md-5">
                            <Wrapper name="allowSendingByMail" className="form-group">
                                <br />
                                <label className="control-label">
                                    <Morearty.DOM.input value={store.get('allowSendingByMail')} onChange={store.getHandler('allowSendingByMail')}  type="checkbox" />{' '}
                                    Jsem ochoten zboží poslat poštou
                                </label>
                            </Wrapper>
                        </div>
                    </div>

                </div>

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <Wrapper name="description" className="form-group">
                        <label className="control-label" id="description">Popis prodávané věci</label>
                        <Morearty.DOM.textarea id="description" value={store.get('description')} onChange={store.getHandler('description')}  className="form-control" rows="3" placeholder="např. Stáří, opotřebení" />
                    </Wrapper>
                </div>



                <div className="col-xs-12 col-sm-12 col-md-12">
                    <h3>
                        Kontakt
                    </h3>

                    <div className="row">
                        <Wrapper name="contact.email" className="col-xs-12 col-sm-6 col-md-6">
                            <div className="form-group">
                                <label className="control-label" htmlFor="contact.email">Email</label>{' '}<span className="help-block-inline">(pro odpovědi)</span>
                                <Morearty.DOM.input id="contact.email" value={store.get('contact.email')} onChange={store.getHandler('contact.email')} className="form-control" placeholder="Na ten vám budou chodit všechny odpovědi" />
                            </div>
                        </Wrapper>
                    </div>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12">
                            <Wrapper name="contact.place" className="form-group">
                                <label className="control-label" htmlFor="contact.place">Město nebo obec</label>
                                {this.renderAutocompleter()}
                                <button className="btn btn-default">Moje poloha</button>
                            </Wrapper>
                        </div>
                    </div>


                    <div className="row">
                        <Wrapper name="contact.name" className="col-xs-12 col-sm-6 col-md-6">
                            <div value={store.get('contact.name')} onChange={store.getHandler('contact.name')} className="form-group">
                                <label className="control-label" htmlFor="contact.name">Jmeno a příjmení nebo přezívka</label>
                                <Morearty.DOM.input id="contact.name" className="form-control" type="text" placeholder="Ať kupujicí ví, s kým má tu čest" />
                            </div>
                        </Wrapper>
                        <div className="col-xs-12 col-sm-6 col-md-6">
                            <Wrapper name="contact.phone" className="form-group">
                                <label className="control-label" htmlFor="contact.phone">Telefon</label>{' '}<span className="help-block-inline">(Volitelné)</span>
                                <Morearty.DOM.input id="contact.phone" value={store.get('contact.phone')} onChange={store.getHandler('contact.phone')} className="form-control" type="text" placeholder="Pro rychlejší komunikaci" />
                            </Wrapper>
                        </div>
                    </div>

                </div>


                <div className="col-xs-12 col-sm-8 col-md-8">
                    <button disabled={store.isLoading()} type="submit" className="btn btn-primary btn-lg">Pokračovat</button>
                </div>


            </form>
        );

    }
});

module.exports = AdForm;
