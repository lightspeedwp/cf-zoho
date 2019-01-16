const gulp         = require('gulp');
const gettext      = require('gulp-gettext');
const sort         = require('gulp-sort');
const wppot        = require('gulp-wp-pot');

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp compile-js                to compile the js to min.js');
	console.log('gulp wordpress-lang            to compile the lsx-cf-zoho.pot, lsx-cf-zoho-en_EN.po and lsx-cf-zoho-en_EN.mo');
});

/*  LANGUAGE SETTINGS */
gulp.task('wordpress-pot', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx-cf-zoho',
			package: 'lsx-cf-zoho',
			bugReport: 'https://github.com/lightspeeddevelopment/cf-zoho/issues',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/lsx-cf-zoho.pot'))
});

gulp.task('wordpress-po', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx-cf-zoho',
			package: 'lsx-cf-zoho',
			bugReport: 'https://github.com/lightspeeddevelopment/cf-zoho/issues',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/lsx-cf-zoho-en_EN.po'))
});

gulp.task('wordpress-po-mo', ['wordpress-po'], function() {
	return gulp.src('languages/lsx-cf-zoho-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'))
});

gulp.task('wordpress-lang', (['wordpress-pot', 'wordpress-po-mo']));
