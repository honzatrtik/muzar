var traceur = require('traceur');

module.exports = {
    process: function(src, path) {
        if (path.match(/^(?!.*node_modules)+.+\.js$/)) {
            var options = {};
            return traceur.compile(src, options);
        }
        return src;
    }
};