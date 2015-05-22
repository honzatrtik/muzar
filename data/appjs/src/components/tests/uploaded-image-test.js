"use strict";

// This must be first!
var render = require('../../../test-render.js');

var React = require('react/addons');
var TestUtils = React.addons.TestUtils;
var assert = require('assert');
var Promise = require('../../promise.js');


var routeNames = ['detail', 'list', 'search', 'listAll', 'home'];

import UploadedImage from '../uploaded-image.js';

describe('UploadedImage', function() {

    beforeEach(function(done){
        this.timeout(5000);
        done();
    });


    it('render image on fetcher promise resolve', function(done) {

        var fetcher = function(id) {
            return Promise.resolve({
                id: 'someid',
                image_url: 'https://www.google.cz/images/srpr/logo11w.png'
            });
        };


        var uploadedImage = render(UploadedImage, routeNames, {
            fetcher: fetcher,
            id: 'someid'
        });


        setTimeout(function() {

            var divs = TestUtils.scryRenderedDOMComponentsWithClass(uploadedImage, 'uploaded-image');
            assert.equal(divs.length, 1);
            var images = TestUtils.scryRenderedDOMComponentsWithTag(uploadedImage, 'img');
            assert.equal(images.length, 1);
            done();

        }, 10)
    });

    it('render image with is-error class on fetcher promise reject', function(done) {

        var fetcher = function(id) {
            return Promise.reject();
        };


        var uploadedImage = render(UploadedImage, routeNames, {
            fetcher: fetcher,
            id: 'someid'
        });


        setTimeout(function() {

            var divs = TestUtils.scryRenderedDOMComponentsWithClass(uploadedImage, 'is-error');
            assert.equal(divs.length, 1);
            done();

        }, 10)
    });

});