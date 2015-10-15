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
						'poedit': true,
						'report-msgid-bugs-to': 'https://github.com/bradyvercher/gravity-forms-iframe/issues'
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
