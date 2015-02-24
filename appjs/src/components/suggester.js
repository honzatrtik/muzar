"use strict";

var _ = require('lodash');
var event = require('dom-event');
var React = require('react/addons');
var Promise = require('../promise.js');
var Router = require('react-router');
var Link = Router.Link;
var keyCodes = require('../utils/event-key-codes.js');
var cs = React.addons.classSet;
var Imm = require('immutable');


var Suggester = React.createClass({

    mixins: [Router.Navigation],

    propTypes: {
        suggestionsFunction: React.PropTypes.func.isRequired
    },

    getInitialState: function() {
        return {
            query: '',
            ads: Imm.List(),
            categories: Imm.List(),
            queries: Imm.List(),
            activeTabIndex: 0,
            show: false,
            loading: false
        }
    },

    closeSuggestionBox: function() {
        this.setState({
            show: false,
            query: '',
            activeTabIndex: 0
        });
    },

    bindBodyClickListener: function() {
        event.on(document.body, 'click', this.closeSuggestionBox);
    },

    unbindBodyClickListener: function() {
        event.off(document.body, 'click', this.closeSuggestionBox);
    },

    componentDidMount: function() {
        this.bindBodyClickListener();
    },

    componentWillUnmount: function() {
        this.unbindBodyClickListener();
    },

    loadSuggestions: function(query) {

        this.setState({
            loading: true
        });

        var self = this;
        Promise.resolve(this.props.suggestionsFunction.call(null, query)).then(function(data) {
            self.setState({
                queries: Imm.List(data.queries || []),
                ads: Imm.List(data.ads || []),
                categories: Imm.List(data.categories || []),
                loading: false
            });
        });
    },

    handleSubmit: function(event) {
        event.preventDefault();

        var self = this;
        var query = this.state.query;

        this.setState({
            show: false,
            activeTabIndex: 0
        }, function() {
            self.transitionTo('search', {}, {
                query: query
            });
        });

    },

    handleSuggestionsBoxClick: function() {
        this.setState({
            show: false,
            activeTabIndex: 0
        });
    },

    handleInputChange: function(event) {

        var self = this;
        var query =  event.target.value;
        var state = {
            query: query,
            show: true,
            activeTabIndex: 0
        };

        if (query) {
            this.loadSuggestions(query);
        }

        this.setState(state);

    },

    handleItemFocus: function(index, event) {
        this.setState({
            activeTabIndex: index
        });
    },

    handleInputBlur: function(event) {
    },


    handleInputClick: function(event) {
        event.stopPropagation();
    },

    handleButtonClick: function(event) {
        event.stopPropagation();
    },

    handleInputFocus: function(event) {
        this.setState({
            show: true,
            activeTabIndex: 0
        });
    },

    handleInputKeyDown: function(event) {

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
                event.preventDefault();
                this.refs['input'].getDOMNode().blur();
                this.closeSuggestionBox(); // Close suggestion box
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
            <li onMouseOver={this.handleItemFocus.bind(this, index)} className={classNames} key={'query:'+query}>
                <Link onFocus={this.handleItemFocus.bind(this, index)} ref={index} tabIndex={index} onClick={this.handleSuggestionsBoxClick} to="search" query={{query: query}}>{query}</Link>
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
            <li onMouseOver={this.handleItemFocus.bind(this, index)} className={classNames} key={'ad:'+ad.id}>
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
            <li onMouseOver={this.handleItemFocus.bind(this, index)} className={classNames} key={'category:'+category.str_id}>
                <Link ref={index} tabIndex={index} onClick={this.handleSuggestionsBoxClick} to="list" params={{category: category.str_id}}>{category.path.join(' > ')}</Link>
            </li>
        );
    },


    renderCategories: function() {
        if (this.state.categories.size) {
            return (
                <div>
                    <small>Nalezeny kategorie</small>
                    <ul className="suggester-suggestionsBox-group">
                        {this.state.categories.map(this.renderCategoryItem).toJS()}
                    </ul>
                </div>
            );
        } else {
            return null;
        }
    },

    renderQueries: function() {
        var queries = this.state.queries.push(this.state.query);
        return (
            <div>
                <small>Hledat</small>
                <ul className="suggester-suggestionsBox-group">
                    {queries.map(this.renderQueryItem).toJS()}
                </ul>
            </div>
        );
    },

    renderAds: function() {
        if (this.state.ads.size) {
            return (
                <div>
                    <small>Nalezeno v inzerátech</small>
                    <ul className="suggester-suggestionsBox-group">
                        {this.state.ads.map(this.renderAdItem).toJS()}
                    </ul>
                </div>
            );
        } else {
            return null;
        }
    },

    render: function() {

        this.tabIndices = [];

        var show = this.state.query && this.state.show;

        if (show) {
            var box = (
                <div className="suggester-suggestionsBox">
                    {this.renderQueries()}
                    {this.renderCategories()}
                    {this.renderAds()}
                </div>
            );
        }

        var formAction = this.makeHref('search');
        var query = this.state.query;

        return (
            <form onSubmit={this.handleSubmit} className="suggester navbar-form navbar-left" method="get" action={formAction}>
                <div className="form-group">
                    <input ref="input" onClick={this.handleInputClick} onFocus={this.handleInputFocus} onBlur={this.handleInputBlur} onKeyDown={this.handleInputKeyDown} autoComplete="off" type="search" name="query" className="form-control suggester-input" value={query} onChange={this.handleInputChange} />
                    {show ? box : null}
                    <button onClick={this.handleButtonClick} type="submit" className="btn btn-default suggester-button">
                        <span className="glyphicon glyphicon-search" />
                        Vyhledat
                    </button>
                </div>
            </form>
        );

    }
});

module.exports = Suggester;