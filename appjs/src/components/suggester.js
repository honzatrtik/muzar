"use strict";

var _ = require('lodash');
var React = require('react/addons');
var Promise = require('es6-promise').Promise;
var Router = require('react-router');
var Link = Router.Link;
var keyCodes = require('../utils/event-key-codes.js');
var cs = React.addons.classSet;


var Suggester = React.createClass({

    mixins: [Router.Navigation],

    propTypes: {
        suggestionsFunction: React.PropTypes.func.isRequired
    },

    getInitialState: function() {
        return {
            query: '',
            ads: [],
            categories: [],
            activeTabIndex: 0
        }
    },

    handleSubmit: function(event) {
        event.preventDefault();

        var self = this;
        var query = this.state.query;

        this.setState({
            query: '',
            activeTabIndex: 0
        }, function() {
            self.transitionTo('search', {}, {
                query: query
            });
        });

    },

    handleSuggestionsBoxClick: function() {
        this.setState({
            query: '',
            activeTabIndex: 0
        });
    },

    handleInputChange: function(event) {

        var self = this;
        var query =  event.target.value;
        var state = {
            query: query,
            activeTabIndex: 0
        };

        if (query) {
            Promise.resolve(this.props.suggestionsFunction.call(null, query)).then(function(data) {
                self.setState({
                    ads: data.ads,
                    categories: data.categories
                });
            });
        }

        this.setState(state);

    },

    handleItemMouseOver: function(index, event) {
        this.setState({
            activeTabIndex: index
        });
    },

    handleKeyDown: function(event) {

        var activeTabIndex = this.state.activeTabIndex;
        var length = this.tabIndices.length;

        if (length) {
            switch (event.keyCode) {
                case keyCodes.UP:
                    event.preventDefault();
                    activeTabIndex = activeTabIndex > 0 ? activeTabIndex - 1 : activeTabIndex;
                    this.setState({
                        activeTabIndex: activeTabIndex
                    });
                    break;

                case keyCodes.DOWN:
                    event.preventDefault();
                    activeTabIndex = activeTabIndex < length ? activeTabIndex + 1 : activeTabIndex;
                    this.setState({
                        activeTabIndex: activeTabIndex
                    });
                    break;
            }
        }

        switch (event.keyCode) {
            case keyCodes.ESC:
                this.setState({
                    activeTabIndex: 0,
                    query: ''
                });
                break;

            case keyCodes.ENTER:
                if (activeTabIndex) {
                    event.preventDefault();
                    this.refs[activeTabIndex].getDOMNode().click()
                }
                break;
        }


    },

    renderQueryItem: function(query) {
        var index = this.tabIndices.length + 1;
        this.tabIndices.push(index);
        var classNames = cs({
            'suggester-suggestionsBox-item': true,
            'is-active': index === this.state.activeTabIndex
        });
        return (
            <li onMouseOver={this.handleItemMouseOver.bind(this, index)} className={classNames} key={'query:'+query}>
                <Link ref={index} tabIndex={index} onClick={this.handleSuggestionsBoxClick} to="search" query={{query: query}}>{query}</Link>
            </li>
        );
    },

    renderAdItem: function(ad) {
        var index = this.tabIndices.length + 1;
        this.tabIndices.push(index);
        var classNames = cs({
            'suggester-suggestionsBox-item': true,
            'is-active': index === this.state.activeTabIndex
        });
        return (
            <li onMouseOver={this.handleItemMouseOver.bind(this, index)} className={classNames} key={'ad:'+ad.id}>
                <Link ref={index} tabIndex={index} onClick={this.handleSuggestionsBoxClick} to="detail" params={{id: ad.id}}>{ad.name}</Link>
            </li>
        );
    },

    renderCategoryItem: function(category) {
        var index = this.tabIndices.length + 1;
        this.tabIndices.push(index);
        var classNames = cs({
            'suggester-suggestionsBox-item': true,
            'is-active': index === this.state.activeTabIndex
        });

        return (
            <li onMouseOver={this.handleItemMouseOver.bind(this, index)} className={classNames} key={'category:'+category.strId}>
                <Link ref={index} tabIndex={index} onClick={this.handleSuggestionsBoxClick} to="list" params={{category: category.strId}}>{category.path.join(' > ')}</Link>
            </li>
        );
    },

    render: function() {

        this.tabIndices = [];

        if (this.state.query) {
            var box = (
                <div className="suggester-suggestionsBox">
                    <small>Hledat všude</small>
                    <ul className="suggester-suggestionsBox-group">
                        {this.renderQueryItem(this.state.query)}
                    </ul>
                    <small>Nalezeno v inzerátech</small>
                    <ul className="suggester-suggestionsBox-group">
                        {this.state.ads.map(this.renderAdItem)}
                    </ul>
                    <small>Nalezeny kategorie</small>
                    <ul className="suggester-suggestionsBox-group">
                        {this.state.categories.map(this.renderCategoryItem)}
                    </ul>
                </div>
            );
        }

        var formAction = this.makeHref('search');

        return (
            <form onSubmit={this.handleSubmit} className="suggester navbar-form navbar-left" method="get" action={formAction}>
                <div className="form-group">
                    <input onKeyDown={this.handleKeyDown} autoComplete="off" type="search" name="query" className="form-control suggester-input" value={this.state.query} onChange={this.handleInputChange} />
                    {this.state.query ? box : null}
                    <button type="submit" className="btn btn-default suggester-button"><span className="glyphicon glyphicon-search" />
                        Vyhledat
                    </button>
                </div>
            </form>
        );

    }
});

module.exports = Suggester;