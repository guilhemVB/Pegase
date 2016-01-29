module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            basic_and_extras: {
                files: {
                    'web/js/homepage.js': 'src/AppBundle/Resources/public/js/homepage/*.js',
                    'web/js/common.js': 'src/AppBundle/Resources/public/js/common/*.js',
                    'web/js/contact.js': 'src/AppBundle/Resources/public/js/contact/*.js',
                    'web/js/destination.js': 'src/AppBundle/Resources/public/js/destination/*.js',
                    'web/js/destinationList.js': 'src/AppBundle/Resources/public/js/destinationList/*.js',
                    'web/js/createVoyage.js': 'src/CalculatorBundle/Resources/public/js/voyage/*.js'
                }
            }
        },
        uglify: {
            build: {
                files: {
                    'web/js/homepage.min.js': 'web/js/homepage.js',
                    'web/js/common.min.js': 'web/js/common.js',
                    'web/js/contact.min.js': 'web/js/contact.js',
                    'web/js/destination.min.js': 'web/js/destination.js',
                    'web/js/destinationList.min.js': 'web/js/destinationList.js',
                    'web/js/createVoyage.min.js': 'web/js/createVoyage.js'
                }
            }
        },
        imagemin: {
            dist: {
                options: { cache: false },
                files: [{
                    expand: true,
                    cwd: 'src/AppBundle/Resources/public/images/',
                    src: ['**/*/*.JPG', '**/*.JPG'],
                    dest: 'web/images/'
                }]
            }
        },
        watch: {
            scripts: {
                files: ['src/*Bundle/Resources/public/js/*/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['src/*Bundle/Resources/public/css/*.scss'],
                tasks: ['sass'],
                options: {
                    spawn: false
                }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'web/css/app.min.css': 'src/AppBundle/Resources/public/css/global.scss',
                    'web/css/calculator.min.css': 'src/CalculatorBundle/Resources/public/css/global.scss'
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.registerTask('default', ['concat', 'uglify', 'sass', 'imagemin']);
};
