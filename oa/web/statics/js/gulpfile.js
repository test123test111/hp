var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    watch = require('gulp-watch'),
    del = require('del'),
    copy = require('gulp-copy'),
    replace = require('gulp-replace'),
    handlebars = require('gulp-handlebars'),
    wrap = require('gulp-wrap'),
    declare = require('gulp-declare'),
    compass = require('gulp-compass');
  
var paths = {
  pubhtml: ['*.html','!template.html','!demo.html'],
  tmpl: ['templates/*.hds'],
  files: ['images/**/*','css/**/*','common/*.html','!css/demo.css','!css/tmpl.css'],
  sass: ['scss/*.scss','scss/modules/*.scss'],
  commonjs: ['js/zepto.js','js/lib/*.js','js/common.js'],
  scripts: ['js/**/*.js','!js/lib/*.js','!js/common.js','!js/template.js','!js/demo.js']
};

gulp.task('clean', function(cb) {
  del(['publish'], cb);
});

// sass
gulp.task('sass', function() {
  return gulp.src(paths.sass)
    .pipe(compass({
      config_file: 'config.rb',
      css:'css',
      sass: 'scss'
    }))
    .pipe(gulp.dest('css/'))
});

// Scripts
gulp.task('scripts',['commonjs'], function() {
  return gulp.src(paths.scripts)
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('publish/js'));
});
gulp.task('commonjs', function() {
  return gulp.src(paths.commonjs)
      .pipe(uglify())
      .pipe(concat('common.min.js'))
      .pipe(gulp.dest('publish/js'));
});

gulp.task('pubhtml', function(){
  var jslinkReg = /<!--\s*#include\s*file="common\/jslink.html"\s*-->/ig;
  var jsReg = /\.min\.js"|\.js"><\/script>/ig;
  return gulp.src(paths.pubhtml) 
    .pipe(replace(jsReg, '.min.js"></script>'))
    .pipe(replace(jslinkReg, '<script src="js/common.min.js"></script>'))
    .pipe(gulp.dest('publish'));
});

// Copy all static html & images
gulp.task('copy', function() {
  return gulp.src(paths.files)
    .pipe(copy('publish'));
});

//handlebars
gulp.task('templates', function(){
  gulp.src(paths.tmpl)
    .pipe(handlebars())
    .pipe(wrap('Handlebars.template(<%= contents %>)'))
    .pipe(declare({
      namespace: 'aimeilive',
      noRedeclare: true, // Avoid duplicate declarations
    }))
    .pipe(concat('handlebars.templates.js'))
    .pipe(gulp.dest('js/lib/'));
});

// Rerun the task when a file changes
gulp.task('watch', function() {
  gulp.watch(paths.tmpl, ['templates']);
  gulp.watch(paths.sass, ['sass']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['clean'], function() {
    gulp.start('pubhtml','templates','sass','scripts','copy');
});
