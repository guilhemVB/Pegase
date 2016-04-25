module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            basic_and_extras: {
                files: {
                    'src/AppBundle/Resources/public/js/dist/homepage/homepage.js': 'src/AppBundle/Resources/public/js/homepage/*.js',
                    'src/AppBundle/Resources/public/js/dist/common/common.js': 'src/AppBundle/Resources/public/js/common/*.js',
                    'src/AppBundle/Resources/public/js/dist/contact/contact.js': 'src/AppBundle/Resources/public/js/contact/*.js',
                    'src/AppBundle/Resources/public/js/dist/destination/destination.js': 'src/AppBundle/Resources/public/js/destination/*.js',
                    'src/AppBundle/Resources/public/js/dist/country/country.js': 'src/AppBundle/Resources/public/js/country/*.js',
                    'src/AppBundle/Resources/public/js/dist/destinationList/destinationList.js': 'src/AppBundle/Resources/public/js/destinationList/*.js',
                    'src/CalculatorBundle/Resources/public/js/dist/voyage/createVoyage.js': 'src/CalculatorBundle/Resources/public/js/voyage/*.js',
                    'src/CalculatorBundle/Resources/public/js/dist/dashboard/dashboardVoyage.js': 'src/CalculatorBundle/Resources/public/js/dashboard/*.js',
                    'src/CalculatorBundle/Resources/public/js/dist/share/shareVoyage.js': 'src/CalculatorBundle/Resources/public/js/share/*.js'
                }
            }
        },
        uglify: {
            build: {
                files: {
                    'src/AppBundle/Resources/public/js/dist/homepage/homepage.min.js': 'src/AppBundle/Resources/public/js/dist/homepage/homepage.js',
                    'src/AppBundle/Resources/public/js/dist/common/common.min.js': 'src/AppBundle/Resources/public/js/dist/common/common.js',
                    'src/AppBundle/Resources/public/js/dist/contact/contact.min.js': 'src/AppBundle/Resources/public/js/dist/contact/contact.js',
                    'src/AppBundle/Resources/public/js/dist/destination/destination.min.js': 'src/AppBundle/Resources/public/js/dist/destination/destination.js',
                    'src/AppBundle/Resources/public/js/dist/country/country.min.js': 'src/AppBundle/Resources/public/js/dist/country/country.js',
                    'src/AppBundle/Resources/public/js/dist/destinationList/destinationList.min.js': 'src/AppBundle/Resources/public/js/dist/destinationList/destinationList.js',
                    'src/CalculatorBundle/Resources/public/js/dist/voyage/createVoyage.min.js': 'src/CalculatorBundle/Resources/public/js/dist/voyage/createVoyage.js',
                    'src/CalculatorBundle/Resources/public/js/dist/dashboard/dashboardVoyage.min.js': 'src/CalculatorBundle/Resources/public/js/dist/dashboard/dashboardVoyage.js',
                    'src/CalculatorBundle/Resources/public/js/dist/share/shareVoyage.min.js': 'src/CalculatorBundle/Resources/public/js/dist/share/shareVoyage.js'
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
                    style: 'compressed',
                    sourcemap: 'none'
                },
                files: {
                    'src/AppBundle/Resources/public/css/dist/app.min.css': 'src/AppBundle/Resources/public/css/global.scss',
                    'src/CalculatorBundle/Resources/public/css/dist/calculator.min.css': 'src/CalculatorBundle/Resources/public/css/global.scss'
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
