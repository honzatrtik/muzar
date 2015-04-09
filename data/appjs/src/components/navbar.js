"use strict";

var React = require('react');
var Morearty = require('morearty');
var Suggester = require('./suggester');
var Router = require('react-router');
var Link = Router.Link;
var LoginForm = require('./login-form.js');
var { Modal } = require('react-bootstrap');
var DispatcherMixin = require('../dispatcher-mixin.js');
var SessionStore = require('../stores/session-store.js');
var LoginFormStore = require('../stores/login-form-store.js');
var RouteStore = require('../stores/route-store.js');
var { loginFormToggleAction } = require('../actions/user-actions.js');

import { getSuggestions } from '../utils/api-utils.js';


var Navbar = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    getInitialState: function() {
        return {
            login: false
        }
    },

    handleLoginButtonClick: function(event) {
        var store = this.getStore(SessionStore);
        this.executeAction(loginFormToggleAction, !store.isLoginForm());

    },

    renderLoginModal: function() {
        return (
            <Modal onRequestHide={this.handleLoginButtonClick} title="Login" bsStyle="primary" animation>
                <div className="modal-body">
                    <LoginForm />
                </div>
                <div className="modal-footer">
                </div>
            </Modal>
        );
    },

    renderUserBox: function() {
        var store = this.getStore(SessionStore);
        if (store.getAccessToken()) {
            return (
                <span className="glyphicon glyphicon-user" />
            );
        } else {
            return (
                <button onClick={this.handleLoginButtonClick} className="btn btn-default">
                    <span className="glyphicon glyphicon-lock" />
                    Přihlásit
                </button>
            );
        }
    },

    render: function() {

        this.observeBinding(this.getStoreBinding(SessionStore));
        this.observeBinding(this.getStoreBinding(LoginFormStore));
        this.observeBinding(this.getStoreBinding(RouteStore));

        var store = this.getStore(SessionStore);

        return (

            <div className="navbar navbar-default navbar-fixed-top" role="navigation">
                <div className="container">
                    <div className="navbar-header">
                        <button type="button" className="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span className="sr-only">Toggle navigation</span>
                            <span className="icon-bar" />
                            <span className="icon-bar" />
                            <span className="icon-bar" />
                        </button>
                        <Link className="navbar-brand" to="home">Muzar</Link>
                    </div>
                    <div className="collapse navbar-collapse">
                        <ul className="nav navbar-nav">
                            <Suggester suggestionsFunction={getSuggestions} />
                            <div className="navbar-btn navbar-right">
                                <Link className="btn btn-default" to="create">
                                    <span className="glyphicon glyphicon-plus" />{' '}Přidat inzerát
                                </Link>

                                {this.renderUserBox()}

                            </div>
                        </ul>
                    </div>
                </div>

                {store.isLoginForm() && this.renderLoginModal()}

            </div>
        );
    }
});


module.exports = Navbar;