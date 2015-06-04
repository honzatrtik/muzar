import superagent  from '../superagent.js';
import HttpError from '../errors/http-error.js';
import Promise from '../promise.js';

var req;

export function getSuggestions(query) {
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

