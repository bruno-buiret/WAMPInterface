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

// Tasks
gulp.task(
    'stylesheets:build',
    () => {
        vfs
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
            .pipe(vfs.dest(assetsPath + 'build/stylesheets/'))
        ;
    }
);

gulp.task(
    'stylesheets:watch',
    () => {
        gulp.watch(
            assetsPath + 'stylesheets/wamp-interface.scss',
            ['stylesheets:build']
        );
    }
);

gulp.task(
    'scripts:build',
    () => {

    }
);

gulp.task(
    'scripts:watch',
    () => {

    }
);

gulp.task(
    'assets:symlink',
    () => {
        vfs
            .src([
                assetsPath + 'build/stylesheets/*.css',
                assetsPath + 'build/stylesheets/*.css.map'
            ])
            .pipe(vfs.symlink(publicPath + 'stylesheets/'))
        ;
    }
);

gulp.task(
    'build',
    ['stylesheets:build', 'scripts:build']
);

gulp.task(
    'watch',
    ['stylesheets:watch', 'scripts:watch']
);