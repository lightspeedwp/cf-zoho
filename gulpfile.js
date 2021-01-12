const gulp    = require( 'gulp' );
const gettext = require( 'gulp-gettext' );
const sort    = require( 'gulp-sort' );
const wppot   = require( 'gulp-wp-pot' );


gulp.task(
	'default',
	function() {
		console.log( 'Use the following commands' );
		console.log( '--------------------------' );
		console.log( 'gulp wordpress-lang            to compile the cf-zoho.pot, cf-zoho-en_EN.po and cf-zoho-en_EN.mo' );
	}
);


gulp.task(
	'wordpress-pot',
	function(done) {
		done();
		return gulp.src( '**/*.php' )
		.pipe( sort() )
		.pipe(
			wppot(
				{
					domain: 'cf-zoho',
					package: 'cf-zoho',
					bugReport: 'https://github.com/lightspeeddevelopment/cf-zoho/issues',
					team: 'LightSpeed <webmaster@lsdev.biz>'
				}
			)
		)
		.pipe( gulp.dest( 'languages/lsx-cf-zoho.pot' ) )
	}
);

gulp.task(
	'wordpress-po',
	function(done) {
		done();
		return gulp.src( '**/*.php' )
		.pipe( sort() )
		.pipe(
			wppot(
				{
					domain: 'cf-zoho',
					package: 'cf-zoho',
					bugReport: 'https://github.com/lightspeeddevelopment/cf-zoho/issues',
					team: 'LightSpeed <webmaster@lsdev.biz>'
				}
			)
		)
		.pipe( gulp.dest( 'languages/lsx-cf-zoho-en_EN.po' ) )
	}
);

gulp.task(
	'wordpress-po-mo',
	gulp.series(
		['wordpress-po'],
		function(done) {
			done();
			return gulp.src( 'languages/lsx-cf-zoho-en_EN.po' )
			.pipe( gettext() )
			.pipe( gulp.dest( 'languages' ) )
		}
	)
);

gulp.task(
	'wordpress-lang',
	gulp.series(
		['wordpress-pot', 'wordpress-po-mo'] ,
		function(done) {
			console.log( 'Done' );
			done();
		}
	)
);
