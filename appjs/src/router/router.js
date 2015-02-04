var Susanin = require('_susanin');
var URL = require('url-parse');
var _ = require('lodash');


class Router {

    constructor(dispatcher) {
        this._susanin = new Susanin();
        this._dispatcher = dispatcher;
        this._routes = {};
    }

    addRoute(name, definition) {

        definition = typeof definition === 'string'
            ? { pattern: definition}
            : definition;

        definition['name'] = name;
        var route = this._susanin.addRoute(definition);
        this._routes[name] = route;
    }

    build(name, params) {
        var route = this._routes[name];
        if (route) {
            throw new Error('Route not found: ');
        }
        var url = route.build(params);
        if (!url)
    }

    navigateToRoute(name, params) {
        var route = this._routes[name];
        if (this._routes[name]) {
            this._dispatcher.dispatch('router.routeNotFound', {
                name: name,
                params: params
            });
            return;
        }

    }

    navigateTo(url) {

        url = new URL(url);
        url = url.pathname + url.query;

        var found = this._susanin.findFirst(url);

        if (!found) {
            this._dispatcher.dispatch('router.urlNotFound', url);
            return;
        }

        var name = found[0].getName();
        var params = found[1];

        function decodeParams(param) {
            if (_.isArray(param)) {
                return _.map(param, decodeParams);
            } else if (_.isObject(param)) {
                return _.mapValues(param, decodeParams);
            } else {
                return decodeURIComponent(param);
            }
        }

        params = _.mapValues(params, decodeParams);

        this._dispatcher.dispatch('router.routeChanged', {
            name: name,
            params: params
        });
    }
}