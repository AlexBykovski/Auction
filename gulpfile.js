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
            projectDir + '/js/vendor/magnific-popup.min.js',
            projectDir + '/js/vendor/countdown.min.js'
        ])
        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('bower-components', function () {
    return gulp.src([
            projectDir + '/bower-components/angular/angular.min.js',
            projectDir + '/bower-components/angular-sanitize/angular-sanitize.min.js',
            projectDir + '/bower-components/jquery.countdown/dist/jquery.countdown.min.js',
            projectDir + '/bower-components/bootstrap/dist/js/bootstrap.min.js',
            projectDir + '/bower-components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js'
        ])
        .pipe(concat('bower_components.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('client-js', function () {
    return gulp.src([
            projectDir + '/js/client/common.js',
            projectDir + '/js/client/app.js',
            projectDir + '/js/client/service/current-user-service.js',
            projectDir + '/js/client/service/stake-service.js',
            projectDir + '/js/client/app-controller.js',
            projectDir + '/js/client/bonus-controller.js',
            projectDir + '/js/client/main-controller.js',
            projectDir + '/js/client/security-controller.js',
            projectDir + '/js/client/login-controller.js',
            projectDir + '/js/client/registration-controller.js',
            projectDir + '/js/client/service/update-service.js',
            projectDir + '/js/client/recommend-auctions-controller.js',
            projectDir + '/js/client/my-auctions-controller.js',
            projectDir + '/js/client/directive/notify-directive.js',
            projectDir + '/js/client/directive/file-upload-cancel-directive.js',
            //projectDir + '/js/client/service/web-socket-service.js'
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

gulp.task('watch', ['client-js'], function () {
   gulp.watch(projectDir + '/js/client/*.js', ['client-js']);
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

gulp.task('simple-move-css', function () {
    return gulp.src([
            projectDir + '/css/admin/*'
        ])
        .pipe(concat('admin.css'))
        .pipe(gulp.dest(projectDistDir + '/css/admin'));
});

gulp.task('bower-components-css', function () {
    return gulp.src([
            projectDir + '/bower-components/bootstrap/dist/css/bootstrap.min.css'
        ])
        .pipe(concat('bower_components.min.css'))
        .pipe(gulp.dest(projectDistDir + '/css'));
});

gulp.task('simple-move-fonts', function () {
    return gulp.src([
            projectDir + '/fonts/**/*'
        ])
        .pipe(gulp.dest(projectDistDir + '/fonts'));
});

gulp.task('build', ['removedist', 'imagemin', 'sass', 'simple-move-css', 'js-libs', 'bower-components', 'client-js', 'simple-move-fonts', 'bower-components-css'], function () {
    runSequence('cleanDist', function () {});
});

gulp.task('cleanDist', function () {
    del.sync([projectDistDir + '/sass', projectDistDir + '/libs']);
});

gulp.task('removedist', function () { return del.sync(projectDistDir); });

gulp.task('clearcache', function () { return cache.clearAll(); });

gulp.task('default', ['watch']);
