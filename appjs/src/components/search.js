"use strict";

var _ = require('lodash');
var React = require('react');
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;
var AdPreview = require('./ad-preview.js');
var CategoryMenu = require('./category-menu.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');

var Search = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        return (

            <div className="row">
                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="col-xs-12 col-sm-12 col-md-12 boxik">
                        <h1 className>Výsledky hledání <i>"Marshall"</i></h1>
                    </div>
                    <div className="col-xs-12 col-sm-12 col-md-12 boxik">
                        <form className="form-inline" role="form">
                            V kategorii
                            <div className="btn-group">
                                <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    vsechny <span className="caret" />
                                </button>
                                <ul className="dropdown-menu" role="menu">
                                    <li><a href="#">Kytarove nastroje</a></li>
                                    <li><a href="#">Basove nastroje</a></li>
                                    <li><a href="#">Bici</a></li>
                                </ul>
                            </div>
                            v okoli
                            <div className="btn-group">
                                <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Ceska Republika <span className="caret" />
                                </button>
                                <ul className="dropdown-menu" role="menu">
                                    <input type="text" className="form-control" />
                                    <li><a href="#">Basove nastroje</a></li>
                                    <li><a href="#">Bici</a></li>
                                </ul>
                            </div>
                            V cene
                            <div className="btn-group">
                                <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    nerozhoduje <span className="caret" />
                                </button>
                                <ul className="dropdown-menu" role="menu">
                                    <li><a href="#">Od</a></li>
                                    <li><a href="#">Do</a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div className="col-xs-12 col-sm-12 col-md-12 well">
                        <form className="form-inline" role="form">
                            Chceš poslat upozornění na email, když se objeví nový inzerát odpovidajici tomuto filtru? <button type="submit" className="btn btn-primary pull-right"><i className="glyphicon glyphicon-envelope" /> Upozornnit mě emailem</button>
                        </form>
                    </div>
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <p>
                            Našli jsme ti <strong>10 inzerátů</strong>
                        </p>
                    </div>
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <div className="row">
                        </div>
                        <div className="row">
                            <div className="text-center col-xs-12 col-sm-12 col-md-12">
                                <button type="submit" className="btn btn-primary btn-lg">Zobrazit dalsi inzeraty</button>
                                <div className="pull-right">50/651</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

module.exports = Search;