"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var ReplyFormStore = require('../stores/reply-form-store.js');
var cs = React.addons.classSet;
var replyAction = require('../actions/reply-action.js');
var Wrapper = require('./form-error-wrapper.js')(ReplyFormStore);

import Imm from 'immutable';


var ReplyForm = React.createClass({

    propTypes: {
        id: React.PropTypes.number.isRequired
    },

    mixins: [Morearty.Mixin, DispatcherMixin],

    handleSubmit: function(event) {
        event.preventDefault();
        this.executeAction(replyAction, {
            id: this.props.id,
            payload: this.getStore(ReplyFormStore).get()
        });
    },

    renderErrors() {
        var store = this.getStore(ReplyFormStore);
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


    render: function () {

        this.observeBinding(this.getStoreBinding(ReplyFormStore));

        var store = this.getStore(ReplyFormStore);

        return (
            <form onSubmit={this.handleSubmit}>


                <div className="row">
                    <div className="col-xs-12 col-sm-6 col-md-6">
                        <Wrapper name="email" className="form-group">
                            <label>Email</label>
                            <Morearty.DOM.input value={store.get('email')} onChange={store.getHandler('email')} className="form-control" type="text" placeholder="Na ten vám přijde odpověd" />
                        </Wrapper>
                    </div>
                </div>
                <div className="row">
                    <div className="col-xs-12 col-sm-6 col-md-6">
                        <Wrapper name="name" className="form-group">
                            <label>Jmeno nebo přezívka</label><span className="help-block-inline">(Volitelné)</span>
                            <Morearty.DOM.input value={store.get('name')} onChange={store.getHandler('name')}className="form-control" type="text" placeholder="Ať prodávající ví, s kým má tu čest" />
                        </Wrapper>
                    </div>
                    <div className="col-xs-12 col-sm-6 col-md-6">
                        <Wrapper name="phone" className="form-group">
                            <label>Telefon</label><span className="help-block-inline">(Volitelné)</span>
                            <Morearty.DOM.input value={store.get('phone')} onChange={store.getHandler('phone')} className="form-control" type="text" placeholder="Pro rychlejší komunikaci" />
                        </Wrapper>
                    </div>
                </div>
                <div className="col-xs-12 col-sm-12 col-md-12">
                    <Wrapper name="message" className="form-group">
                        <label>Text vaší opovědi</label>
                        <Morearty.DOM.textarea value={store.get('message')} onChange={store.getHandler('message')} className="form-control" rows={5} placeholder="Dobrý den, reaguji na váš inzerat &lt;a&gt;Kytarová hlava JCM 800&lt;/a&gt;." />
                    </Wrapper>
                </div>
                <div className="row">
                    <div className="col-xs-12 col-sm-6 col-md-6">
                        <div className="form-group">
                            <img data-src="holder.js/300x50/text:Capcha" alt="..." />
                        </div>
                    </div>
                </div>
                <div className="col-xs-12 col-sm-8 col-md-8">
                    <button disabled={store.isLoading()} type="submit" className="btn btn-primary btn-lg">Odeslat odpověď</button>
                </div>
                    

            </form>
        );

    }
});

module.exports = ReplyForm;
