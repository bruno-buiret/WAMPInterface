// Require dependencies
// https://www.npmjs.com/package/gulp
const gulp = require('gulp');
// https://www.npmjs.com/package/gulp-sass
const sass = require('gulp-sass');
// https://www.npmjs.com/package/gulp-sourcemaps
const sourceMaps = require('gulp-sourcemaps');
// https://www.npmjs.com/package/gulp-autoprefixer
const autoprefixer = require('gulp-autoprefixer');
// https://www.npmjs.com/package/gulp-clean-css
const cleanCSS = require('gulp-clean-css');
// https://www.npmjs.com/package/gulp-rename
const rename = require('gulp-rename');
// https://github.com/gulpjs/vinyl-fs
const vfs = require('vinyl-fs');

// Initialize vars
const publicPath = './public/assets/';
const assetsPath = './assets/';
const browsers = [
    // Extracted from Bootstrap v4.0.0-beta-2
    'Chrome >= 45',
    'Firefox ESR',
    'Edge >= 12',
    'Explorer >= 10',
    'iOS >= 9',
    'Safari >= 9',
    'Android >= 4.4',
    'Opera >= 30'
];

// Stylesheets
gulp.task(
    'stylesheets:build',
    () => gulp
        .src(assetsPath + 'stylesheets/wamp-interface.scss')
        .pipe(sourceMaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({browsers: browsers}))
        .pipe(cleanCSS(
            {
                compatibility: 'ie9',
                level: 2,
                debug: true
            },
            (details) => {
                console.log(details.name + ': ' + details.stats.originalSize + ' -> ' + details.stats.minifiedSize);
            }
        ))
        .pipe(rename({suffix: '.min'}))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest(assetsPath + 'build/stylesheets/'))
);

gulp.task(
    'stylesheets:watch',
    (done) => {
        gulp.watch(
            assetsPath + 'stylesheets/wamp-interface.scss',
            gulp.series('stylesheets:build')
        );
        done();
    }
);

// Scripts
gulp.task(
    'scripts:build',
    (done) => {
        done();
    }
);

gulp.task(
    'scripts:watch',
    (done) => {
        done();
    }
);

// Symlinks
gulp.task(
    'assets:symlink',
    () => gulp
        .src([
            assetsPath + 'build/stylesheets/*.css',
            assetsPath + 'build/stylesheets/*.css.map'
        ])
        .pipe(vfs.symlink(publicPath + 'stylesheets/'))
);

//
gulp.task(
    'build',
    gulp.parallel('stylesheets:build', 'scripts:build')
);

gulp.task(
    'watch',
    gulp.parallel('stylesheets:watch', 'scripts:watch')
);

gulp.task(
    'default',
    gulp.series('build', 'watch')
);