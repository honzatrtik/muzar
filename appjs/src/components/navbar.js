"use strict";

var React = require('react');
var Suggester = require('./suggester');


// Move!
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');

var req;
function getSuggestions(query) {
    req && req.abort();
    req = superagent.get('/suggestions');
    req.query({
        query: query
    });

    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                resolve(res.body.data);
            } else {
                reject(new HttpError(res.error.status, res.error.text));
            }
        });
    });
}


var Navbar = React.createClass({
    render: function() {
        return (

            <div className="navbar navbar-default navbar-fixed-top" role="navigation">
                <div className="container">
                    <div className="navbar-header">
                        <button type="button" className="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span className="sr-only">Toggle navigation</span>
                            <span className="icon-bar" />
                            <span className="icon-bar" />
                            <span className="icon-bar" />
                        </button>
                        <a className="navbar-brand" href="#">Muzar</a>
                    </div>
                    <div className="collapse navbar-collapse">
                        <ul className="nav navbar-nav">
                            <form className="navbar-form navbar-left" role="search">
                                <div className="form-group">
                                    <Suggester suggestionsFunction={getSuggestions} />
                                </div>
                                <button type="submit" className="btn btn-default"><span className="glyphicon glyphicon-search" />
                                    Vyhledat
                                </button>
                            </form>
                            <form className="navbar-btn navbar-right">
                                <a className="btn btn-default" href="#"><span className="glyphicon glyphicon-plus" />
                                    Přidat inzerát</a>
                                <a className="btn btn-default" href="#"><span className="glyphicon glyphicon-lock" />
                                    Přihlásit</a>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
});


module.exports = Navbar;