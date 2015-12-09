module.exports = function(grunt) {

  var clients = {};
  
  (function() {
    var dev = grunt.file.readJSON('Config/dev.json');
    clients = dev.clients;
  })();  

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
      },
      fynbogaarden: {
        files: ['style/fynbogaarden/sass/**/*.scss'],
        tasks: ['compass:fynbogaarden'],
        options: {
          spawn: false,
        }
      },
      lottemunk: {
        files: ['style/lottemunk/scss/**/*.scss'],
        tasks: ['compass:lottemunk'],
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
			    noLineComments: true,
        }
      },
      karenslyst: {
        options: {
          sassDir: "style/karenslyst/sass",
          cssDir: "style/karenslyst/css",
			    noLineComments: true,
        }
      },
      fynbogaarden: {
        options: {
          sassDir: "style/fynbogaarden/sass",
          cssDir: "style/fynbogaarden/css",
			    noLineComments: true,
        }
      },
      lottemunk: {
        options: {
          sassDir: "style/lottemunk/scss",
          cssDir: "style/lottemunk/css",
			    noLineComments: true,
        }
      }
    },
    shell: {
      transfer : {
        command : function(client) {
          if (!clients[client]) {
            grunt.log.error('Client not found'); return;
          }
          return [
            'scripts/transfer.sh ' + clients[client].production.database + ' ' + clients[client].production.folder
          ].join(' && ');
        }
      },
      'switch' : {
        command : function(client) {
          if (!clients[client]) {
            grunt.log.error('Client not found'); return;
          }
          return 'scripts/switch.sh ' + clients[client].folder
        }
      },
      'stage' : {
        command : function(client) {
          if (clients[client] && clients[client].staging) {
            return 'scripts/deploy.sh ' + clients[client].staging.folder;
          }
          return '';
        }
      },
      'deploy' : {
        command : function(client) {
          if (clients[client] && clients[client].production) {
            return 'scripts/deploy.sh ' + clients[client].production.folder;
          }
          return '';
        }
      }
    }
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

  grunt.registerTask('default', 'Standard tasks', ['watch']);
  
  grunt.registerTask('switch', 'Switch to another client', function(client) {
    grunt.task.run('shell:switch:'+client);
  });

  grunt.registerTask('stage', 'Stage a client', function(client) {
    grunt.task.run('shell:stage:' + client);
  });

  grunt.registerTask('deploy', 'Deploy a client', function(client) {
    grunt.task.run('shell:deploy:' + client);
  });
  
  grunt.registerTask('transfer', 'Transfer from production to local', function(client) {
    grunt.task.run('shell:transfer:'+client);
  });
  
};