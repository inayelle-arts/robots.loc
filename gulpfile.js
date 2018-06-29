const
    gulp = require("gulp"),
    sass = require("gulp-sass"),
    tsc = require("gulp-typescript");

gulp.task("sass:watch", function () {
    gulp.watch("resources/sass/**/*.scss", ["sass:compile"]);
});

gulp.task("sass:compile", function () {
    return gulp
        .src("resources/sass/**/*.scss")
        .pipe(sass())
        .pipe(gulp.dest("public/css"));
});

gulp.task("tsc:watch", function () {
    gulp.watch("resources/ts/**/*.ts", ["tsc:compile"]);
});

gulp.task("tsc:compile", function () {
    return gulp
        .src("resources/ts/**/*.ts")
        .pipe(tsc())
        .pipe(gulp.dest("public/js"));
});

gulp.task("default", ["sass:watch", "tsc:watch"]);