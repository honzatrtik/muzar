"use strict";

var assert = require('assert');
var {normalizeAddressComponents} = require('../google-place-utils.js');

describe('google place utils', function () {


    it('normalizeAddressComponents only administrative_area_level_1 - administrative_area_level_3, country and locality types are preserved', function (done) {

        let components = [
            {
                "long_name":"2",
                "short_name":"2",
                "types":[
                    "street_number"
                ]
            },
            {
                "long_name":"Bieblova",
                "short_name":"Bieblova",
                "types":[
                    "route"
                ]
            },
            {
                "long_name":"Praha 5",
                "short_name":"Praha 5",
                "types":[
                    "sublocality_level_1",
                    "sublocality",
                    "political"
                ]
            },
            {
                "long_name":"Praha",
                "short_name":"Praha",
                "types":[
                    "locality",
                    "political"
                ]
            },
            {
                "long_name":"Hlavní město Praha",
                "short_name":"Hlavní město Praha",
                "types":[
                    "administrative_area_level_2",
                    "political"
                ]
            },
            {
                "long_name":"Hlavní město Praha",
                "short_name":"Hlavní město Praha",
                "types":[
                    "administrative_area_level_1",
                    "political"
                ]
            },
            {
                "long_name":"Česká republika",
                "short_name":"CZ",
                "types":[
                    "country",
                    "political"
                ]
            },
            {
                "long_name":"150 00",
                "short_name":"150 00",
                "types":[
                    "postal_code"
                ]
            }
        ];

        var expected = {
            locality: "Praha",
            administrative_area_level_2: "Hlavní město Praha",
            administrative_area_level_1: "Hlavní město Praha",
            country: "Česká republika"
        };


        assert.deepEqual(normalizeAddressComponents(components).toJS(), expected);
        done();

    });

});