"use strict";

var assert = require('assert');
var transform = require('../symfony-form-error-transformer.js');

describe('Symfony error transformer', function () {


    it('transforms well :)', function (done) {

        var errors = {
            "children": {
                "name": {"errors": ["Tato hodnota nesmí být prázdná."]},
                "description": [],
                "price": {"errors": ["Cena musí být kladné celé číslo nebo dohodou."]},
                "negotiablePrice": [],
                "allowSendingByMail": [],
                "category": {"errors": ["Tato hodnota nesmí být prázdná."]},
                "contact": {
                    "children": {
                        "email": {"errors": ["Tato hodnota nesmí být prázdná."]},
                        "name": [],
                        "phone": [],
                        "lat": [],
                        "lng": [],
                        "place": [],
                        "reference": []
                    }
                }
            }
        };

        var expected = {
            name: ["Tato hodnota nesmí být prázdná."],
            price: ["Cena musí být kladné celé číslo nebo dohodou."],
            category: ["Tato hodnota nesmí být prázdná."],
            "contact.email": ["Tato hodnota nesmí být prázdná."]
        };


        assert.deepEqual(transform(errors), expected);
        done();

    });

});