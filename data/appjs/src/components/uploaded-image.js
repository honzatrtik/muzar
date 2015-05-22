"use strict";

import React from 'react';
import Imm from 'immutable';

import Promise from '../promise.js';
import superagent from '../superagent.js';


var UploadedImage = React.createClass({

    propTypes: {
        id: React.PropTypes.string.isRequired,
        fetcher: React.PropTypes.func
    },

    getInitialState() {
        return {
            image: null,
            error: false,
            loading: false
        };
    },

    fetcher(id) {
        return superagent.get('/images/' + id).promise().then(function(data) {
            return data.data;
        });
    },

    load(id) {

        var self = this;
        this.setState({
            loading: true
        });

        return (this.props.fetcher || this.fetcher)(id)
            .then(function(data) {
                self.setState({
                    image: Imm.Map(data),
                    error: false,
                    loading: false
                });
            })
            .catch(function(error) {
                self.setState({
                    image: null,
                    error: true,
                    loading: false
                });
            });
    },

    componentWillMount() {
        this.load(this.props.id);
    },

    componentWillReceiveProps(nextProps) {
        if (nextProps.id !== this.props.id) {
            this.load(nextProps.id);
        }
    },

    render() {

        if (this.state.loading) {
            return <div className="uploaded-image is-loading">Loading</div>;
        } else if (this.state.error) {
            return <div className="uploaded-image is-error">Error</div>;
        } else {
            return (
                <div className="uploaded-image">
                    <img className="uploaded-image-image" src={this.state.image.get('image_url') + '?variant=thumbRect'} />
                </div>
            );
        }

    }

});


export default UploadedImage;
