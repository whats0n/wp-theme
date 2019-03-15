const gulp = require('gulp')
const scripts = require('./webpack').scripts
const server = require('./server')
const sprites = require('./gulp/sprite-svg/sprite-svg');
const styles = require('./gulp/styles');
const copy = require('./gulp/copy');

// begin tasks
/*
  1. run svg sprites.
  2. run styles.
  3. run css.
  4. run js.
*/
gulp.task('svg-sprite', gulp.series(sprites.tasks));
gulp.task('css', gulp.series('svg-sprite', styles.build))
gulp.task('js', gulp.series('css', scripts))
// end tasks

// begin build tasks
gulp.task('build', gulp.parallel('js', 'copy'))
// end build tasks

// begin watch
/*
  1. run build svg sprites, styles, scripts.
  2. run server.
  3. run watch svg sprites, styles, scripts.
*/
const watch = () => {
  const browser = server()
  sprites.watch().forEach(watch => watch.on('change', browser.reload))
  styles.watch().on('change', browser.reload)
  gulp.watch('site/js/*.js').on('change', browser.reload)
}
gulp.task('default', gulp.series('build', watch))
// end watch
