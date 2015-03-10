"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var LoginFormStore = require('../stores/login-form-store.js');
var cs = React.addons.classSet;
var { loginAction } = require('../actions/user-actions.js');


var LoginForm = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    handleSubmit: function(event) {
        var store = this.getStore(LoginFormStore);
        if (store.isLoading()) return;
        event.preventDefault();
        this.executeAction(loginAction, store.get());
    },

    renderErrors() {
        var store = this.getStore(LoginFormStore);
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

        this.observeBinding(this.getStoreBinding(LoginFormStore));
        var store = this.getStore(LoginFormStore);


        return (
            <form onSubmit={this.handleSubmit}>

                {this.renderErrors()}

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="form-group">

                        <div className="row">

                            <div className="col-xs-12 col-sm-6 col-md-6">
                                <div className="form-group">
                                    <label htmlFor="username">Přihlašovací mail</label>
                                    <Morearty.DOM.input id="username" value={store.get('username')} onChange={store.getHandler('username')}  className="form-control" type="text" />
                                </div>
                            </div>

                        </div>

                        <div className="row">

                            <div className="col-xs-12 col-sm-6 col-md-6">
                                <div className="form-group">
                                    <label htmlFor="password">Heslo</label>
                                    <Morearty.DOM.input id="password" value={store.get('password')} onChange={store.getHandler('password')}  className="form-control" type="password" />
                                </div>
                            </div>

                        </div>


                        {/*<p><a href="">Zapomnel jsi heslo?</a></p> */}

                    </div>
                </div>


                <div className="col-xs-12 col-sm-8 col-md-8">
                    <button disabled={store.isLoading()} type="submit" className="btn btn-primary btn-lg">Pokračovat</button>
                </div>


            </form>
        );

    }
});

module.exports = LoginForm;
