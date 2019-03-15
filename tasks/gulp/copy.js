var gulp = require('gulp');
var config = require('../config.js');

gulp.task('copy:images', function () {
    return gulp
        .src(config.src.images + '**/*.*')
        .pipe(gulp.dest(config.build.images));
});

gulp.task('copy', gulp.parallel('copy:images'))
