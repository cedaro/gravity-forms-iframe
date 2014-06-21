module.exports = function(grunt) {
	'use strict';

	require('matchdep').filterDev('grunt-*').forEach( grunt.loadNpmTasks );

	grunt.initConfig({
		/**
		 * Check JavaScript for errors and warnings.
		 */
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'assets/scripts/*.js',
				'!assets/scripts/*.min.js'
			]
		},

		/**
		 * Generate a POT file.
		 */
		makepot: {
			plugin: {
				options: {
					mainFile: 'gravity-forms-iframe.php',
					potHeaders: {
						'report-msgid-bugs-to': 'http://wordpress.org/support/plugin/gravity-forms-iframe',
						'language': 'en',
						'plural-forms': 'nplurals=2; plural=(n != 1);',
						'x-poedit-basepath': '../',
						'x-poedit-bookmarks': '',
						'x-poedit-country': 'United States',
						'x-poedit-keywordslist': '__;_e;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c;_nc:1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;',
						'x-poedit-searchpath-0': '.',
						'x-poedit-sourcecharset': 'utf-8',
						'x-textdomain-support': 'yes'
					},
					type: 'wp-plugin',
					updateTimestamp: false
				}
			}
		},

		/**
		 * Minify JavaScript source files.
		 */
		uglify: {
			dist: {
				files: [
					{ src: 'assets/scripts/gfembed.js', dest: 'assets/scripts/gfembed.min.js' }
				]
			}
		},

		/**
		 * Watch sources files and compile when they're changed.
		 */
		watch: {
			js: {
				files: ['<%= jshint.all %>'],
				tasks: ['jshint', 'uglify']
			}
		}

	});

	/**
	 * Default task.
	 */
	grunt.registerTask('default', ['jshint', 'uglify', 'watch']);

};
