module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            all: ['../../js/*.js']
        },
        qunit: {
            all: ['../../test/phantom/*.html']
            //urls : ['http://localhost/~jonasmunk/onlinepublisher/hui/test/phantom/test.html']
        },
        watch: {
          files: ['../../js/hui.js'],//['<%= jshint.all %>'],
          tasks: ['jshint']
        }
    });

    // Load
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    
    // Default task(s).
    grunt.registerTask('default', ['jshint']);
    grunt.registerTask('test', ['qunit']);
    grunt.registerTask('watch', ['watch']);
    

};
