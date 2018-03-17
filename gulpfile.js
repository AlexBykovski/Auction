var gulp           = require('gulp'),
    gutil          = require('gulp-util' ),
    browserSync    = require('browser-sync'),
    htmlbuild      = require('gulp-htmlbuild'),
    sass           = require('gulp-sass'),
    concat         = require('gulp-concat'),
    uglify         = require('gulp-uglify'),
    cleanCSS       = require('gulp-clean-css'),
    rename         = require('gulp-rename'),
    del            = require('del'),
    imagemin       = require('gulp-imagemin'),
    pngquant       = require('imagemin-pngquant'),
    cache          = require('gulp-cache'),
    autoprefixer   = require('gulp-autoprefixer'),
    bourbon        = require('node-bourbon'),
    runSequence    = require('run-sequence');

var projectDir = 'assets';
var projectDistDir = 'public/build';
var projectDistImageDir = 'public/images/default';

//gulp.task('browser-sync', function () {
//    browserSync({
//        server: {
//            baseDir: projectDir
//        },
//        notify: false
//    });
//});

gulp.task('sass', function () {
    return gulp.src(projectDir + '/sass/*.sass')
        .pipe(sass({
            includePaths: bourbon.includePaths
        }).on('error', sass.logError))
        .pipe(rename({suffix: '.min', prefix : ''}))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(cleanCSS())
        .pipe(gulp.dest(projectDir + '/css'))
        //.pipe(browserSync.reload({stream: true}))
});

gulp.task('js-libs', function () {
    return gulp.src([
            projectDir + '/js/vendor/jquery-3.3.1.min.js',
            projectDir + '/js/vendor/equalHeights.js',
            projectDir + '/js/vendor/owl.carousel.min.js',
            projectDir + '/js/vendor/magnific-popup.min.js'
        ])
        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDir + '/js'));
});

gulp.task('watch', ['sass', 'js-libs', 'browser-sync'], function () {
    gulp.watch(projectDir + '/sass/**/*.sass', ['sass']);
    //gulp.watch(projectDir + '/*.html', browserSync.reload);
    //gulp.watch(projectDir + '/js/*.js', browserSync.reload);
});

gulp.task('imagemin', function () {
    return gulp.src(projectDir + '/img/**/*')
        .pipe(cache(imagemin({
            interlaced: true,
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        })))
        .pipe(gulp.dest(projectDistImageDir));
});

gulp.task('buildFiles', function () {
    var buildHTML = gulp.src(projectDir + '/*.html')
        .pipe(htmlbuild({
            js: htmlbuild.preprocess.js(function (block) {
                block.write('./js/scripts.min.js');
                block.end();
            })
        }))
        .pipe(gulp.dest(projectDistDir));

    var buildJs = gulp.src([
            projectDir + '/js/libs.min.js',
            projectDir + '/js/common.js'
        ])
        .pipe(concat('scripts.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));

    var buildRest = gulp.src([
            projectDir + '/**/*',
            '!' + projectDir + '/*.html',
            '!' + projectDir + '/js/**/*',
            '!' + projectDir + '/sass/**/*',
            '!' + projectDir + '/libs/**/*'
        ])
        .pipe(gulp.dest(projectDistDir));
});

gulp.task('build', ['removedist', 'imagemin', 'sass', 'js-libs', 'buildFiles'], function () {
    runSequence('cleanDist', function () {});
});

gulp.task('cleanDist', function () {
    del.sync([projectDistDir + '/sass', projectDistDir + '/libs']);
});

gulp.task('removedist', function () { return del.sync(projectDistDir); });

gulp.task('clearcache', function () { return cache.clearAll(); });

gulp.task('default', ['watch']);
