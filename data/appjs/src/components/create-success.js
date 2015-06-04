"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdPreview = require('./ad-preview.js');
var AdFormStore = require('../stores/ad-form-store.js');

import HttpError from '../errors/http-error.js';

var Create = React.createClass({

    mixins: [DispatcherMixin],

    render() {

        var store = this.getStore(AdFormStore);
        var item = store.getCreatedAd();
        if (!item) {
            throw new HttpError(404);
        }

        return (
            <div className="row">

                <div className="col-xs-12 col-sm-12 col-md-8">
                    <div className="col-xs-12 col-sm-6 col-md-8">
                        <h1>Inzerát přidán!</h1>
                        <p>
                            Podpořte ho sdílením mezi své přátele
                        </p>
                        <p>
                            <a href className="btn btn-default btn-lg">Sdilet na FB</a> <a href className="btn btn-default btn-lg">Sdilet emailem</a>
                        </p>
                    </div>
                </div>

                <AdPreview item={item}/>

            </div>
        );
    }
});

module.exports = Create;