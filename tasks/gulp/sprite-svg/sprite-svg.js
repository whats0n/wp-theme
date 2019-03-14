var gulp        = require('gulp');
var plumber     = require('gulp-plumber');
var svgmin      = require('gulp-svgmin');
var svgStore    = require('gulp-svgstore');
var rename      = require('gulp-rename');
var gulpCheerio = require('gulp-cheerio');
var cheerio     = require('cheerio');
var through2    = require('through2');
var consolidate = require('gulp-consolidate');
var config      = require('../../config');

var sprites = [
  {
    task: 'sprite:svg',
    name: 'sprite',
    prefix: 'icon-',
    scss: '_svg-sprite',
    src: config.src.svgSprite,
    dest: config.build.images,
    min: true
  }
  // {
  //   task: 'sprite:svg:filled',
  //   name: 'sprite-filled',
  //   prefix: 'icon-filled-',
  //   scss: '_svg-sprite-filled',
  //   src: config.src.iconsFilled,
  //   dest: config.src.images,
  //   min: false
  // },
  // {
  //   task: 'sprite:svg:flags',
  //   name: 'sprite-flags',
  //   prefix: 'icon-flag-',
  //   scss: '_svg-sprite-flags',
  //   src: config.src.iconsFlags,
  //   dest: config.src.images,
  //   min: false,
  //   byWidth: true
  // }
];

sprites.forEach(function(sprite) {
  gulp.task(sprite.task, function() {
    var viewBoxes = [];
    var fileNames = [];
    var counter = 0;
    var stream = gulp
      .src(sprite.src + '/*.svg')
      .pipe(plumber({
        errorHandler: config.errorHandler
      }))
      .pipe(gulpCheerio({
        run: function($, file) {
          viewBoxes.push($('svg').attr('viewBox'))
        },
        parserOptions: { xmlMode: true }
      }))
      .pipe(svgmin({
        js2svg: {
          pretty: true
        },
        plugins: [{
          removeDesc: true
        }, {
          cleanupIDs: true
        }, {
          mergePaths: false
        }]
      }))
      .pipe(rename(function(path) {
        fileNames.push(sprite.prefix + path.basename)
      }))
      .pipe(gulpCheerio({
        run: function($, file) {
          $('svg').find('[id]').each(function(i) {
            var _this = $(this);
            var id = fileNames[counter] + '-' + _this.attr('id');
            _this.attr('id', id);
          });
          $('svg').find('use').each(function(i) {
            var _this = $(this);
            var href = _this.attr('xlink:href');
            if (fileNames[counter] === 'icon-flag-si') {
              console.log(fileNames[counter], href)
            }
            if (!href) return
            var hrefID = href.substr(1, href.length);
            var hrefNew = '#' + fileNames[counter] + '-' + hrefID;
            _this.attr('xlink:href', hrefNew);
          })
          $('svg').find('[clip-path]').each(function(i) {
            var _this = $(this);
            var url = _this.attr('clip-path');
            var urlID = url.substr('url(#'.length, url.length - 1);
            var urlNew = 'url(#' + fileNames[counter] + '-' + urlID + ')';
            _this.attr('clip-path', urlNew);
          })
          counter++;
        },
        parserOptions: { xmlMode: false }
      }))
      .pipe(rename({ prefix: sprite.prefix }))
      .pipe(svgStore({ inlineSvg: false }))
      .pipe(through2.obj(function(file, encoding, cb) {
        var $ = file.cheerio || cheerio.load(file.contents.toString(), {xmlMode:true});
        var data = $('symbol').map(function(index) {
          var $this  = $(this);
          var size   = viewBoxes[index].split(' ').splice(2);
          var name   = $this.attr('id');
          var ratio  = sprite.byWidth ? (size[1] / size[0]) : (size[0] / size[1]); // if it's horizontally icons ? height/width : width/height
          var fill   = $this.find('[fill]:not([fill="currentColor"])').attr('fill');
          var stroke = $this.find('[stroke]').attr('stroke');
          return {
            name: name,
            ratio: +ratio.toFixed(2),
            width: sprite.byWidth ? 1 : ratio.toFixed(2),
            height: sprite.byWidth ? ratio.toFixed(2) : 1,
            fill: fill || 'initial',
            stroke: stroke || 'initial'
          };
        }).get();
        this.push(file);
        gulp.src(__dirname + '/_sprite-svg.scss')
          .pipe(consolidate('lodash', {
            symbols: data
          }))
          .pipe(rename({ basename: sprite.scss }))
          .pipe(gulp.dest(config.src.stylesGen));
        cb();
      }))
      .pipe(gulpCheerio({
        run: function($, file) {
          $('symbol').each(function(index) {
            var $this  = $(this);
            $this.attr('viewBox', viewBoxes[index])
          })
          if (sprite.min) $('[fill]:not([fill="currentColor"])').removeAttr('fill');
          if (sprite.min) $('[stroke]').removeAttr('stroke');
        },
        parserOptions: { xmlMode: true }
      }))
      .pipe(rename({ basename: sprite.name }))
      .pipe(gulp.dest(sprite.dest));
    
    return stream;
  });
});

module.exports = {
  tasks: sprites.map(sprite => sprite.task),
  watch() {
    return sprites.map(sprite => gulp.watch(sprite.src + '*.svg', gulp.series(sprite.task)))
  }
}
