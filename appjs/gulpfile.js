var _ = require('lodash');
var gulp = require('gulp');
var rename = require('gulp-rename');
var browserify = require('browserify');
var deamdify = require('deamdify');
var reactify = require('reactify');
var util = require('gulp-util');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglifyjs');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var watchify = require('watchify');
var es6ify = require('es6ify');



var bundlesConfig = [{
    name: 'test',
    files: ['./test.js'],
    dest: './../web/build/'
}];


gulp.task('browserify', function(){
    _.each(bundlesConfig, function(config) {
        browserifyShare(config);
    });
});


gulp.task('watch', function() {
    _.each(bundlesConfig, function(config) {
        browserifyShare(config, true);
    });
});



function browserifyShare(config, watch){

    var b = browserify(es6ify.runtime, {
        cache: {},
        packageCache: {},
        fullPaths: true
    });

    _.each(config.files, function(file) {
        b.add(file);
    });

    if (watch) {
        b = watchify(b);
        b.on('update', function(){
            util.log('Updating...');
            bundleShare(b, config);
            util.log(util.colors.green('Done!'));
        });
    }

    b.transform(es6ify.configure(/^(?!.*node_modules)+.+\.js$/));
    b.transform(reactify);
    b.transform(deamdify);

    return bundleShare(b, config);
}


function bundleShare(b, config) {
    return b.bundle()
        .on('error', function(e) {
            util.log(util.colors.red('Browserify error:'), e.message);
        })
        .pipe(source(config.name))
        //.pipe(streamify(uglify()))
        .pipe(rename(function(path) {
            path.extname = '.min.js';
        }))
        .pipe(gulp.dest(config.dest));
}

