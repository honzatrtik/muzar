var FluxDispatcher = require('flux').Dispatcher;
var Promise = require('es6-promise').Promise;

class Dispatcher extends FluxDispatcher {

    dispatch(type, data) {
        super.dispatch({
            type: type,
            data: data
        });
    }

}

module.exports = Dispatcher;


