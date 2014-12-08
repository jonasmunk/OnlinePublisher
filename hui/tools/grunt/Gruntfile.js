module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            all: ['../../js/hui.js']
        },
        qunit: {
            all: ['../../test/phantom/*.html']
            //urls : ['http://localhost/~jonasmunk/onlinepublisher/hui/test/phantom/test.html']
        },
        watch: {
          files: ['<%= jshint.all %>'],
          tasks: ['jshint']
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    
    // Default task(s).
    grunt.registerTask('default', ['jshint']);
    grunt.registerTask('test', ['qunit']);
    //grunt.registerTask('watch', ['watch']);
    

};
