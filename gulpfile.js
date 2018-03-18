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
        .pipe(gulp.dest(projectDistDir + '/css'))
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
        .pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('bower-components', function () {
    return gulp.src([
            projectDir + '/bower-components/angular/angular.min.js',
            projectDir + '/bower-components/angular-sanitize/angular-sanitize.min.js'
        ])
        .pipe(concat('bower_components.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('client-js', function () {
    return gulp.src([
            projectDir + '/js/client/common.js',
            projectDir + '/js/client/app.js',
            projectDir + '/js/client/bonus-controller.js',
            projectDir + '/js/client/main-controller.js',
        ])
        .pipe(concat('client.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));
});

//gulp.task('watch', ['sass', 'js-libs', 'browser-sync'], function () {
//    gulp.watch(projectDir + '/sass/**/*.sass', ['sass']);
//    //gulp.watch(projectDir + '/*.html', browserSync.reload);
//    //gulp.watch(projectDir + '/js/*.js', browserSync.reload);
//});

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

gulp.task('simple-move-css', function () {
    return gulp.src([
            projectDir + '/css/admin/*'
        ])
        .pipe(concat('admin.css'))
        .pipe(gulp.dest(projectDistDir + '/css/admin'));
});

gulp.task('simple-move-fonts', function () {
    return gulp.src([
            projectDir + '/fonts/**/*'
        ])
        .pipe(gulp.dest(projectDistDir + '/fonts'));
});

gulp.task('build', ['removedist', 'imagemin', 'sass', 'simple-move-css', 'js-libs', 'bower-components', 'client-js', 'simple-move-fonts'], function () {
    runSequence('cleanDist', function () {});
});

gulp.task('cleanDist', function () {
    del.sync([projectDistDir + '/sass', projectDistDir + '/libs']);
});

gulp.task('removedist', function () { return del.sync(projectDistDir); });

gulp.task('clearcache', function () { return cache.clearAll(); });

gulp.task('default', ['watch']);
