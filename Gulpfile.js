/**
 * Tasks automation
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
/* globals require */

// Require dependencies
// https://www.npmjs.com/package/gulp
const gulp = require('gulp');
// https://www.npmjs.com/package/gulp-sass
const sass = require('gulp-sass');
// https://www.npmjs.com/package/gulp-sourcemaps
const sourceMaps = require('gulp-sourcemaps');
// https://www.npmjs.com/package/gulp-postcss
const postCss = require('gulp-postcss');
// https://www.npmjs.com/package/autoprefixer
const autoprefixer = require('autoprefixer');
// https://www.npmjs.com/package/cssnano
const cssNano = require('cssnano');
// https://www.npmjs.com/package/gulp-rename
const rename = require('gulp-rename');

// Initialize vars
const assetsPath = 'assets/';
const buildPath = 'assets/build/';
const publicPath = 'public/assets/';
const postCssPlugins = [autoprefixer(), cssNano()];

// Stylesheets
gulp.task(
    'build:stylesheets',
    () => gulp
        .src(assetsPath + 'stylesheets/wamp-interface.scss')
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(postCss(postCssPlugins))
        .pipe(rename({suffix: '.min'}))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest(buildPath + 'stylesheets/'))
);
gulp.task(
    'watch:stylesheets',
    (done) => {
        gulp.watch(
            assetsPath + 'stylesheets/**/*.scss',
            gulp.series('build:stylesheets')
        );
        done();
    }
);

gulp.task(
    'assets:symlink',
    (done) => {
        gulp
            .src([
                buildPath + 'stylesheets/**/*.css',
                buildPath + 'stylesheets/**/*.css.map',
            ])
            .pipe(gulp.symlink(publicPath + 'stylesheets/'))
        ;
        done();
    }
);

gulp.task('build', gulp.series('build:stylesheets'));

gulp.task('watch', gulp.series('watch:stylesheets'));

gulp.task('default', gulp.series('build', 'watch'));