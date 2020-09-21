var PROXY, PORT, ROOT;
var public = true;
var parent_dir = 'plugins';
var dir = 'sk-tools';
var separate_admin = true;

// PROXY = "wordpress.lfx"; // MADAGASCAR
PROXY = "wordpress.dnt"; // DINONITE
PORT = 3000;

// ROOT = `/media/lfx/www/wordpress/wp-content/${parent_dir}/${dir}`;
ROOT = `X:/cms/wordpress/wp-content/${parent_dir}/${dir}`;
SOURCEMAP_ROOT = "http://" + PROXY + ROOT + "/src/style-maps/";

function nthIndex(str, search, n) {
	var L = str.length, i = -1;
	while(n-- && i++ < L) {
		i = str.indexOf(search, i);
		if(i < 0) break;
	}

	return i;
}

// requires
var gulp 					= require('gulp'),
		cached 				= require('gulp-cached'),
		sass 					= require('gulp-sass'),
		postcss 			= require('gulp-postcss'),
		autoprefixer 	= require('autoprefixer'),
		sourcemaps 		= require('gulp-sourcemaps'),
		wait 					= require('gulp-wait'),
		jshint 				= require('gulp-jshint'),
		concat 				= require('gulp-concat'),
		rename 				= require('gulp-rename'),
		uglify 				= require('gulp-uglify'),
		image 				= require('gulp-image'),
		newer 				= require('gulp-newer'),
		browserSync 	= require('browser-sync');

const server 			= browserSync.create();

let paths = null;
let general_files = null;

// REF: https://github.com/micromatch/micromatch#extended-globbing
// test the globbing @ https://globster.xyz/ (add new paths as necessary)
// NOTE: do NOT include the _ for scss files, or they won't be compiled properly
/**
 * DEMO SHOWING HOW THE BELOW GLOBS MATCH
 * https://globster.xyz/?q=%2Fmyapp%2Fsrc%2F**%2F!(%23)*%2B(admin%7Csettings)*.scss&f=%2Fmyapp%2Fsrc%2Fstyles%2Fcolor%2Fcolor.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fcolor%2F_functions.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fcolor%2F_mixins.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fglobal%2Fglobal.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fglobal%2F_functions.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2F_settings.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2F%23sk-admin-test.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2F%23sk-public-styles.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2F_sk-admin-none.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fsk-tools-admin.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fsk-admin-test.scss%2C%2Fmyapp%2Fsrc%2Fstyles%2Fsk.addressbook.styles.scss%2C%2Fmyapp%2Fadmin%2Fcss%2Ftest.css
 */
if(separate_admin) {
	paths = {
		admin: {
			styles: { 
				src: 	`${ROOT}/src/styles/**/!(#)*+(admin|settings)*.scss`,
				dest:	`${ROOT}/admin/css`
			},
			scripts: { 
				src: 	`${ROOT}/src/scripts/**/!(_)*+(admin)*.js`,
				dest:	`${ROOT}/admin/js`
			},
			images: { 
				src: 	`${ROOT}/src/images/**/!(_)*+(admin)*.{jpg,JPG,jpeg,JPEG,png,PNG}`,
				dest:	`${ROOT}/admin/images`
			}
		},
		public: {
			styles: {
				src:   `${ROOT}/src/styles/**/!(#|*admin)*.scss`,
				dest:  `${ROOT}/includes/css`
			},
			scripts: {
				src:  `${ROOT}/src/scripts/**/!(#|_|*admin)*.js`,
				dest: `${ROOT}/includes/js`
			},
			images: {
				src:  `${ROOT}/src/images/**/!(#|_|*admin)*.{jpg,JPG,jpeg,JPEG,png,PNG}`,
				dest: `${ROOT}/includes/images`
			}
		}
	}

	console.log(paths.admin.styles);

	general_files = `${ROOT}/admin/**/*.{html,php,js}`;

} else {
	paths = {
		styles: {
			src:   `${ROOT}/src/styles/**/!(#|_)*.scss`,
			dest:  `${ROOT}/includes/css`
		},
		scripts: {
			src:  `${ROOT}/src/scripts/**/!(_)*.js`,
			dest: `${ROOT}/includes/js`
		},
		images: {
			src:  `${ROOT}/src/images/**/!(_)*.{jpg,JPG,jpeg,JPEG,png,PNG}`,
			dest: `${ROOT}/includes/images`
		},
		general: `${ROOT}/**/*.{html,php,js}`
	}

	console.log(paths.styles);

}

// SCSS => CSS task
gulp.task('styles', function(done) {
	if(separate_admin) {
		// https://stackoverflow.com/a/53102945
		Object.keys(paths).forEach(val => {
			//console.log(val + " : " + paths[val].styles.src + " => " + paths[val].styles.dest);

			// 'val' will go through twice, for admin and public
			return gulp.src( paths[val].styles.src )
				.pipe(cached('cached files')) // exclude files that haven't changed
				.pipe( sourcemaps.init() )
				.pipe( sourcemaps.identityMap() )
				.pipe( sass({
					outputStyle: 'condensed',
					//indentType: 'tab',
					//indentWidth: '1'
				}).on('error', sass.logError) )
				.pipe( postcss([
					autoprefixer('last 2 versions', '> 1%')
				]) )
				.pipe( sourcemaps.write() )
				.pipe( gulp.dest(paths[val].styles.dest) )
				.pipe( server.reload({stream: true}) );
		});
		done(); // to avoid async problems using Gulp 4

	} else {
		var styles = gulp.src( paths.styles.src )
			.pipe(cached('cached files')) // exclude files that haven't changed
			.pipe( sourcemaps.init() )
			.pipe( sourcemaps.identityMap() )
			.pipe( sass({
				outputStyle: 'condensed',
				//indentType: 'tab',
				//indentWidth: '1'
			}).on('error', sass.logError) )
			.pipe( postcss([
				autoprefixer('last 2 versions', '> 1%')
			]) )
			.pipe( sourcemaps.write() )
			.pipe( gulp.dest(paths.styles.dest) )
			.pipe( server.reload({stream: true}) );

			return styles;
	}

});

// Minify + Uglify scripts
gulp.task('scripts', function(done) {
	if(separate_admin) {
		Object.keys(paths).forEach(val => {
			// 'val' will go through twice, for admin and public
			return gulp.src( paths[val].scripts.src )
				.pipe(jshint())
				.pipe(jshint.reporter('default'))
				.pipe(concat('app.js'))
				.pipe(gulp.dest(paths[val].scripts.dest))
				.pipe(rename('app.min.js'))
	    	.pipe(uglify())
				.pipe( gulp.dest(paths[val].scripts.dest) );
		});
		done(); // to avoid async problems using Gulp 4

	} else {
		var scripts = gulp.src(paths.scripts.src)
			.pipe(jshint())
			.pipe(jshint.reporter('default'))
			.pipe(concat('app.js'))
			.pipe(gulp.dest(paths.scripts.dest))
				.pipe(rename('app.min.js'))
	    	.pipe(uglify())
	    	.pipe(gulp.dest(paths.scripts.dest));

		return scripts;

	}

	
});

// compress images
gulp.task('images', function(done) {
	if(separate_admin) {
		Object.keys(paths).forEach(val => {
			// 'val' will go through twice, for admin and public
			return gulp.src( paths[val].images.src )
				.pipe(newer(paths.images.dest))
				.pipe(image())
				.pipe(gulp.dest(paths.images.dest));
		});
		done(); // to avoid async problems using Gulp 4

	} else {
		var images = gulp.src(paths.images.src)
			.pipe(newer(paths.images.dest))
			.pipe(image())
			.pipe(gulp.dest(paths.images.dest));

		return images;

	}
	
});

function reload(done) {
	server.reload();
	done();
}

function serve(done) {
	server.init({
		open: 'external',
		port: PORT,
		proxy: PROXY,
		files: [ROOT + '**/*'],
		injectChanges: true,
		notify: false
	});

	console.log('Watching '+ PROXY +' on port ' + PORT);
	done();
}

// Watch task
gulp.task('watch', function() {
	if(separate_admin) {
		// 'val' loops through twice, for 'admin' and 'public'
		Object.keys(paths).forEach(val => {
			gulp.watch(paths[val].styles.src, gulp.series('styles'));
			gulp.watch(paths[val].scripts.src, gulp.series('scripts', reload));
			gulp.watch(paths[val].images.src, gulp.series('images', reload));
		});

		// 'general' is outside the 'admin' and 'public' arrays
		gulp.watch(general_files, gulp.series(reload));

	} else {
		gulp.watch(paths.styles.src, gulp.series('styles'));
		gulp.watch(paths.scripts.src, gulp.series('scripts', reload));
		gulp.watch(paths.images.src, gulp.series('images', reload));
		gulp.watch(paths.general, gulp.series(reload));
	}
});

// Set 'watch' as default task
gulp.task('default', gulp.series(serve, 'watch'));