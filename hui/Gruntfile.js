module.exports = function(grunt) {
  
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
        all: ['js/*.js']
    },
    watch: {
      scss: {
        files: ['scss/**/*.scss'],
        tasks: ['compass'],
        options: {
          spawn: false,
        }
      }
    },
    qunit: {
      all: ['test/phantom/*.html']
    },
    jsdoc : {
      dist : {
        src: ['js/*.js'],
        options: {
          destination: 'doc'
        }
      }
    },
    compass: {
      full: {
        options: {
          sassDir: "scss",
          cssDir: "css",
			    noLineComments: true,
        }
      }
    },
    shell: {
      all : {
        command : 'tools/all.sh'
      }
    }
  });

  // Load plugins.
  //grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-symlink');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-shell');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-jsdoc');

  // Default task(s).
  grunt.registerTask('default', 'Watch', ['watch']);
  
  //grunt.registerTask('test', ['qunit']);
  grunt.registerTask('test', 'Run tests', function(testname) {
    if(!!testname) {
      grunt.config('qunit.all', ['test/phantom/' + testname + '.html']);
    }
    grunt.task.run('qunit:all');
  });
};