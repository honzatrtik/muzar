"use strict";

var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var cs = React.addons.classSet;
var { Tooltip } = require('react-bootstrap');

import Dispatcher from '../bootstrap-dispatcher.js';


export default function formErrorWrapperFactory(StoreClass) {

    return React.createClass({

        displayName: 'FormErrorWrapper' + Dispatcher.getStoreName(StoreClass),

        mixins: [DispatcherMixin],

        propTypes: {
            element: React.PropTypes.element,
            name: React.PropTypes.string.isRequired
        },

        render: function() {

            var store = this.getStore(StoreClass);
            var { element, name, className, children, ...props } = this.props;

            element = element || 'div';
            var errors = store.getErrors(this.props.name);


            var classNames = {};
            if (className) {
                classNames[className] = true;
            }
            classNames['has-error'] = errors.size;
            props['className'] = cs(classNames);

            var result = [];
            if (errors.size) {
                result.unshift(
                    <Tooltip key="tooltip" placement="top" positionLeft={0} positionTop={0}>
                        {errors.map(error => <div key={error}>{error}</div>).toJS()}
                    </Tooltip>
                );
            }
            result.push(<div key="wrapper">{children}</div>);

            return React.createElement(element, props, result);
        }
    });

};
