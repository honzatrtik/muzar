"use strict";

var assert = require('assert');
var transform = require('../symfony-form-error-transformer.js');

describe('Symfony error transformer', function () {


    it('transforms well :)', function (done) {

        var errors = [{
            "property_path": "price",
            "message": "Cena musí být kladné celé číslo nebo dohodou."
        }, {"property_path": "name", "message": "Tato hodnota nesmí být prázdná."}, {
            "property_path": "category",
            "message": "Tato hodnota nesmí být prázdná."
        }, {
            "property_path": "contact.email",
            "message": "Tato hodnota nesmí být prázdná."
        }, {
            "property_path": "contact.email",
            "message": "Tato hodnota musí být email."
        }, {
            "property_path": "[contact][email][0].test",
            "message": "Foobar"
        }, {"property_path": "contact.name", "message": "Tato hodnota nesmí být prázdná."}
        ];

        var expected = {
            price: ["Cena musí být kladné celé číslo nebo dohodou."],
            name: ["Tato hodnota nesmí být prázdná."],
            category: ["Tato hodnota nesmí být prázdná."],
            "contact.email": ["Tato hodnota nesmí být prázdná.", "Tato hodnota musí být email."],
            "contact.name": ["Tato hodnota nesmí být prázdná."],
            "contact.email.0.test": ["Foobar"]
        };

        assert.deepEqual(transform(errors), expected);
        done();

    });

});