'use strict';

var Imm = require('immutable');
var Transit = require('transit-js');

var reader = Transit.reader('json', {
    arrayBuilder: {
        init: function () { return Imm.List.of().asMutable(); },
        add: function (ret, val) { return ret.push(val); },
        finalize: function (ret) { return ret.asImmutable(); },
        fromArray: function (arr) { return Imm.List(arr); }
    },
    mapBuilder: {
        init: function () { return Imm.Map().asMutable(); },
        add: function (ret, key, val) { return ret.set(key, val);  },
        finalize: function (ret) { return ret.asImmutable(); }
    },
    handlers: {
        set: function (arr) {
            return Imm.Set(arr);
        },
        orderedMap: function (arr) {
            return Imm.OrderedMap(arr);
        }
    }
});

var writer = Transit.writer("json-verbose", {
    handlers: Transit.map([
        Imm.List, Transit.makeWriteHandler({
            tag: function () { return 'array'; },
            rep: function (v) { return v; },
            stringRep: function () { return null; }
        }),
        Imm.Map, Transit.makeWriteHandler({
            tag: function () { return 'map'; },
            rep: function (v) { return v; },
            stringRep: function () { return null; }
        }),
        Imm.Set, Transit.makeWriteHandler({
            tag: function () { return 'set'; },
            rep: function (v) { return v.toArray(); },
            stringRep: function () { return null; }
        }),
        Imm.OrderedMap, Transit.makeWriteHandler({
            tag: function () { return 'orderedMap'; },
            rep: function (v) { return v.toArray().filter(function (x) { return x; }); },
            stringRep: function () { return null; }
        })
    ])
});

module.exports = {

    serialize: function (value) {
        return writer.write(value);
    },

    unserialize: function (serialized) {
        return reader.read(serialized);
    }

};

