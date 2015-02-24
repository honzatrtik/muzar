var _ = require('lodash');
var gulp = require('gulp');
var rename = require('gulp-rename');
var browserify = require('browserify');
var deamdify = require('deamdify');
var reactify = require('reactify');
var util = require('gulp-util');
var less = require('gulp-less');
var watchLess = require('gulp-watch-less');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglifyjs');
var plumber = require('gulp-plumber');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var watchify = require('watchify');
var es6ify = require('es6ify');


var bundlesConfig = [{
    name: 'client',
    files: ['./client.js'],
    dest: './build/'
}];


var lessConfig = {
    src: ['./less/all.less'],
    dest: './build/'
};

gulp.task('less', function(){
    gulp.src(lessConfig.src)
        .pipe(plumber())
        .pipe(less())
        .pipe(gulp.dest(lessConfig.dest));
});

gulp.task('watch-less', function(){
    watchLess(lessConfig.src, function() {
        gulp.src(lessConfig.src)
            .pipe(plumber())
            .pipe(less())
            .pipe(gulp.dest(lessConfig.dest));
    });

});

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

    var b = browserify({
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
            util.log('Changed...');
            bundleShare(b, config);
            util.log(util.colors.green('Done!'));
        });
    }

    b.transform(function(filename, options) {
        options = options || {};
        options.target = 'es5';
        options.es6 = true;
        options.stripTypes = true;
        return reactify(filename, options);

    });
    b.transform(deamdify);

    return bundleShare(b, config);
}


function bundleShare(b, config) {
    return b.bundle()
        .on('error', function(e) {
            util.log(util.colors.red('Browserify error:'), e.message);
            this.end(); // http://latviancoder.com/story/error-handling-browserify-gulp
        })
        .pipe(source(config.name))
        //.pipe(streamify(uglify()))
        .pipe(rename(function(path) {
            path.extname = '.js';
        }))
        .pipe(gulp.dest(config.dest));
}

