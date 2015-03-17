"use strict";

import debugFactory from 'debug';
import Promise from '../promise.js';
import HttpError from '../errors/http-error.js';
import { normalizePlace } from './google-place-utils.js';

var debug = debugFactory('GetGooglePlace');

module.exports = function(mapsApiKey) {

    return function(lat, lng) {
        return new Promise(function(resolve, reject) {

            let latLng = new google.maps.LatLng(lat, lng);
            let request = {
                latLng
            };

            debug('geocoding request', request);

            let service = new google.maps.Geocoder();
            service.geocode(request, function(result, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    return resolve(normalizePlace(result[0]));
                } else {
                    reject(status);
                }
            });

        });
    };

};