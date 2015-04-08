module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      all: ['Grunfile.js', 'drupal-common/src/*']
    },
    watch: {
      humanise: {
        files: ['style/humanise/sass/**/*.scss'],
        tasks: ['compass:humanise'],
        options: {
          spawn: false,
        }
      },
      karenslyst: {
        files: ['style/karenslyst/sass/**/*.scss'],
        tasks: ['compass:karenslyst'],
        options: {
          spawn: false,
        }
      }
    },
    compass: {
      humanise: {
        options: {
          sassDir: "style/humanise/sass",
          cssDir: "style/humanise/css",
        }
      },
      karenslyst: {
        options: {
          sassDir: "style/karenslyst/sass",
          cssDir: "style/karenslyst/css",
        }
      }
    },
    clean: {
      transfer: ["transfer"],
    },
  });

  // Load plugins.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-symlink');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-shell');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-jsdoc');

  // Default task(s).
  grunt.registerTask('default', 'Standard tasks', ['watch']);

};