var PROXY, PORT, ROOT;
var public = true;

// PROXY = "wordpress.lfx"; // MADAGASCAR
PROXY = "wordpress.dnt"; // DINONITE
PORT = 3000;

// ROOT = "/media/lfx/www/wordpress/wp-content/plugins/sk-tools";
ROOT = 'X:/cms/wordpress/wp-content/plugins/sk-tools';
SOURCEMAP_ROOT = "http://" + PROXY + "/wp-content/plugins/sk-tools/src/style-maps/";

function nthIndex(str, search, n) {
	var L = str.length, i = -1;
	while(n-- && i++ < L) {
		i = str.indexOf(search, i);
		if(i < 0) break;
	}

	return i;
}

// requires
var gulp = require('gulp'),
		sass = require('gulp-sass'),
		postcss = require('gulp-postcss'),
		autoprefixer = require('autoprefixer'),
		sourcemaps = require('gulp-sourcemaps'),
		wait = require('gulp-wait'),
		jshint = require('gulp-jshint'),
		concat = require('gulp-concat'),
		rename = require('gulp-rename'),
		uglify = require('gulp-uglify'),
		image = require('gulp-image'),
		newer = require('gulp-newer'),
		browserSync = require('browser-sync');

const server = browserSync.create();

const paths = {
	styles: {
		src:   `${ROOT}/src/styles/**/!(#)*.scss`,
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
	public: `${ROOT}/**/*.{html,php,js}`
}

console.log(paths.styles.src);

// SCSS => CSS task
gulp.task('styles', function() {
	var styles = gulp.src( paths.styles.src )
		//.pipe( wait(25) )
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
});

// Minify + Uglify scripts
gulp.task('scripts', function() {
	var scripts = gulp.src(paths.scripts.src)
			.pipe(jshint())
			.pipe(jshint.reporter('default'))
			.pipe(concat('app.js'))
			.pipe(gulp.dest(paths.scripts.dest))
				.pipe(rename('app.min.js'))
	    	.pipe(uglify())
	    	.pipe(gulp.dest(paths.scripts.dest));

	return scripts;
});

// compress images
gulp.task('images', function() {
	var images = gulp.src(paths.images.src)
			.pipe(newer(paths.images.dest))
			.pipe(image())
			.pipe(gulp.dest(paths.images.dest));

	return images;
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
	gulp.watch(paths.styles.src, gulp.series('styles'));
	gulp.watch(paths.scripts.src, gulp.series('scripts', reload));
	gulp.watch(paths.images.src, gulp.series('images', reload));
	gulp.watch(paths.public, gulp.series(reload));
});

// Set 'watch' as default task
gulp.task('default', gulp.series(serve, 'watch'));