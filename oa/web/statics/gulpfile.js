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
  files: ['images/**/*','css/**/*','fis-conf.js','favicon.ico','common/*.html','!css/demo.css','!css/tmpl.css','json/*.json'],
  sass: ['scss/*.scss','scss/modules/*.scss'],
  commonjs: ['js/zepto.js','js/lib/*.js','js/common.js'],
  scripts: ['js/**/*.js','!js/lib/*.js','!js/common.js','!js/template.js','!js/demo.js']
};

gulp.task('clean', function(cb) {
  del(['publish','tmp'], cb);
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
  //var linkReg = /<link rel="stylesheet" href="/ig;
  //var scriptReg = /<script src="js/ig;
  //var publicUrl = 'http://www.easyfans.com/';
  
  return gulp.src(paths.pubhtml) 
    .pipe(replace(jsReg, '.min.js"></script>'))
    .pipe(replace(jslinkReg, '<script src="js/common.min.js"></script>'))
    //.pipe(replace(linkReg, '/<link rel="stylesheet" href="' + publicUrl))
    //.pipe(replace(scriptReg, '/<script src="js/' + publicUrl))
    .pipe(gulp.dest('publish'));
});

// Copy all static html & images
gulp.task('copy', function() {
  return gulp.src(paths.files)
    .pipe(copy('publish'));
});

//add cdn url 
gulp.task('staticurl', function() {
  var srcReg = /src="{/ig;
  var dataOriginalReg = /data-original="/ig;
  var backgroundReg = /background-image:url\({/ig; 

  return gulp.src(paths.tmpl)
    .pipe(replace(srcReg, 'src="{{staticPath}}{'))
    .pipe(replace(dataOriginalReg, 'data-original="{{staticPath}}'))
    .pipe(replace(backgroundReg, 'background-image:url({{staticPath}}{'))
    .pipe(gulp.dest('tmp/tmpl'));  
});

//handlebars
gulp.task('templates',['staticurl'], function(){
  
  gulp.src('tmp/tmpl/*.hds')
    .pipe(handlebars())
    .pipe(wrap('Handlebars.template(<%= contents %>)'))
    .pipe(declare({
      namespace: 'aimeilive',
      noRedeclare: true, // Avoid duplicate declarations
    }))
    .pipe(concat('handlebars.templates.js'))
    .pipe(gulp.dest('js/lib'));
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
