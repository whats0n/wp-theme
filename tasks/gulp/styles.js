var gulp        = require('gulp');
var gulpSass    = require("gulp-sass");
var gulpCSSO    = require('gulp-csso');
var config      = require('../config');

const build = () => gulp.src(config.src.styles + 'app.scss')
  .pipe(gulpSass())
  .pipe(gulpCSSO())
  .pipe(gulp.dest(config.build.styles))

const watch = () => gulp.watch(config.src.styles + '**/*.scss', build)

module.exports = { build, watch }
