"use strict";

var event = require('dom-event');
var React = require('react/addons');
var cs = React.addons.classSet;

import * as domUtils from '../utils/dom-utils.js';

var ScrollToTopLink = React.createClass({

    getInitialState: function() {
        return {
            visible: false
        };
    },

    getScrollTop: function() {
        return domUtils.getScrollTop();
    },

    getInnerHeight: function() {
        return domUtils.getInnerHeight();
    },

    toggleVisibility: function() {
        this.setState({
            visible: this.getScrollTop() > this.getInnerHeight()
        })
    },

    bindScrollListener: function() {
        event.on(document, 'scroll', this.toggleVisibility);
    },

    unbindScrollListener: function() {
        event.off(document, 'scroll', this.toggleVisibility);
    },

    componentDidMount: function() {
        this.bindScrollListener();
    },

    componentWillUnmount: function() {
        this.unbindScrollListener();
    },

    handleButtonClick: function() {
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    },

    render: function() {
        return this.state.visible
            ? <button onClick={this.handleButtonClick} className="btn btn-default btn-lg scrollToTopLink">Skoč nahoru{'  '}<span className="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></button>
            : null;
    }
});

module.exports = ScrollToTopLink;