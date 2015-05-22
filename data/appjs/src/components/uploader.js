"use strict";

import React from 'react';
import Imm from 'immutable';

import Promise from '../promise.js';
import superagent from '../superagent.js';
import UploadedImage from './uploaded-image.js';

var cs = React.addons.classSet;

var Uploader = React.createClass({

    displayName: 'Uploader',

    propTypes: {
        onChange: React.PropTypes.func,
        onUploadError: React.PropTypes.func
    },

    getInitialState() {
        return {
            value: this.props.value ? Imm.Set(this.props.value) : Imm.Set(),
            loading: false,
            counter: 0
        };
    },

    handleRemoveLinkClick(id, event) {
        var self = this;
        var value = this.state.value.remove(id);
        this.setState({
            value: value
        }, function() {
            self.props.onChange && self.props.onChange(value);
        });
    },

    handleInputChange(event) {
        var files = event.target.files;
        var self = this;
        for (var i = 0; i < files.length; i++) {
            this.setState({
                loading: true
            });
            this.upload(files[0])
                .then(function(data) {
                    var value = self.state.value.add(data.data.id);
                    self.setState({
                        value: value,
                        loading: false
                    }, function() {
                        self.props.onChange && self.props.onChange(value);
                    });
                })
                .catch(function(error) {
                    self.setState({
                        loading: false
                    });
                    self.props.onUploadError && self.props.onUploadError(error);
                });

        }

        this.setState({
            counter: this.state.counter + 1
        });
    },

    upload(file) {
        return superagent.post('/images').attach('file', file, file.name).promise();
    },

    renderImage(id) {
        return <div className="uploader-image" key={id}><a onClick={this.handleRemoveLinkClick.bind(this, id)} className="uploader-image-remove">odebrat</a><UploadedImage id={id} /></div>;
    },

    render() {

        var classNames = cs({
            'uploader': true,
            'is-loading': this.state.loading
        });

        return (
            <div className={classNames}>
                {this.state.value.map(this.renderImage).toArray()}
                <div key="add" className="uploader-add"><input key={this.state.counter} accept="image/*;capture=camera" id={this.props.htmlId} ref="input" onChange={this.handleInputChange} className="uploader-add-input" type="file" capture="camera" /></div>
            </div>
        )
    }

});


export default Uploader;
