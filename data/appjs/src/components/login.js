"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');

import HttpError from '../errors/http-error.js';
import LoginForm from './login-form.js';

var Login = React.createClass({

    mixins: [DispatcherMixin],

    render() {

        return (
            <div className="row">

                <h1>Přihlášení</h1>

                <div className="col-xs-6 col-sm-6 col-md-6">
                    <LoginForm />
                </div>

                <div className="col-xs-6 col-sm-6 col-md-6">
                    <p>
                    </p>
                </div>

            </div>
        );
    }
});

module.exports = Login;