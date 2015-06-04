"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdForm = require('./ad-form.js');
var AdFormStore = require('../stores/ad-form-store.js');

var Create = React.createClass({

    render() {

        return (
            <div className="row">

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <h1>
                        Prodejte to u nás!
                    </h1>
                </div>

                <div className="col-xs-12 col-sm-12 col-md-12">
                    <AdForm />
                </div>
            </div>
        );
    }
});

module.exports = Create;