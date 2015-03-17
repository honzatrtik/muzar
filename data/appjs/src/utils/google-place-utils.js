"use strict";

import Imm from 'immutable';

var allowedTypes = [
    'administrative_area_level_1',
    'administrative_area_level_2',
    'administrative_area_level_3',
    'locality',
    'country'
];

function normalizeAddressComponents(addressComponents) {

    var result = {};
    addressComponents.forEach(function(component) {
        let value = component['long_name'];
        let type = component.types[0];

        if (allowedTypes.indexOf(type) !== -1) {
            result[type] = value;
        }
    });
    return Imm.Map(result);
}


function normalizePlace(place) {

    var normalized = normalizeAddressComponents(place.address_components);
    var formatted = normalized.toOrderedSet().join(', ');

    return Imm.Map({
        address_components: normalized,
        formatted_address: formatted,
        place_id: place.place_id,
        lat: place.geometry.location.lat(),
        lng: place.geometry.location.lng()
    });

}

module.exports = {
    normalizeAddressComponents: normalizeAddressComponents,
    normalizePlace: normalizePlace

};