const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const purge = require('gulp-css-purge');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const browserify = require('browserify');
const tsify = require('tsify');
const es = require('event-stream');
const sourcemaps = require('gulp-sourcemaps');

const styles = function () {
  return gulp
    .src('./styles/*.css')
    .pipe(autoprefixer({
      cascade: false,
    }))
    .pipe(purge({
      trim: true,
      shorten: true,
      verbose: true,
    }))
    .pipe(rename(function(path){
      path.basename += ".min"
      path.extname = ".css"
    }))
    .pipe(gulp.dest('./build/'));
}

const scripts = function () {
  const entryFiles = ['scripts/submission.ts'];

  const tasks = entryFiles.map((entryFile) => {
    return browserify({
      basedir: '.',
      debug: true,
      entries: [entryFile],
      cache: {},
      packageCache: {}
    })
    .plugin(tsify)
    .bundle()
    .pipe(source(entryFile))
    .pipe(rename({
        extname: '.bundle.js'
    }))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
  });

  return es.merge.apply(null, tasks).pipe(gulp.dest('build'));
}

const watchFiles = function () {
  gulp.watch(['./scripts/*.ts', './scripts/*/*.ts'], gulp.series(scripts));
  gulp.watch('./styles/*.css', gulp.series(styles));
}

const watch = gulp.parallel(scripts, styles, watchFiles);

exports.watch = watch;
