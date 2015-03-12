var _ = require('lodash');
var gulp = require('gulp');
var rename = require('gulp-rename');
var browserify = require('browserify');
var deamdify = require('deamdify');
var util = require('gulp-util');
var less = require('gulp-less');
var watchLess = require('gulp-watch-less');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglifyjs');
var streamify = require('gulp-streamify');
var plumber = require('gulp-plumber');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var watchify = require('watchify');
var babelify = require('babelify');

var uglifyConfig = {
    compress: {
        sequences: true,  // join consecutive statemets with the “comma operator”
        properties: true,  // optimize property access: a["foo"] → a.foo
        dead_code: true,  // discard unreachable code
        drop_debugger: true,  // discard “debugger” statements
        unsafe: false, // some unsafe optimizations (see below)
        conditionals: true,  // optimize if-s and conditional expressions
        comparisons: true,  // optimize comparisons
        evaluate: true,  // evaluate constant expressions
        booleans: true,  // optimize boolean expressions
        loops: true,  // optimize loops
        unused: true,  // drop unused variables/functions
        hoist_funs: true,  // hoist function declarations
        hoist_vars: false, // hoist variable declarations
        if_return: true,  // optimize if-s followed by return/continue
        join_vars: true,  // join var declarations
        cascade: true,  // try to cascade `right` into `left` in sequences
        side_effects: false,  // drop side-effect-free statements
        warnings: false  // warn about potentially dangerous optimizations/code
    }
};


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

    b.transform(babelify.configure({
        experimental: true
    }));
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
        //.pipe(streamify(uglify(uglifyConfig)))
        .pipe(rename(function(path) {
            path.extname = '.js';
        }))
        .pipe(gulp.dest(config.dest));
}
