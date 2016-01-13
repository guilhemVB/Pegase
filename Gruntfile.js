module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            dist: {
                src: [
                    'src/AppBundle/Resources/public/js/*/*.js'
                ],
                dest: 'web/js/app.js'
            }
        },
        uglify: {
            build: {
                src: 'web/js/app.js',
                dest: 'web/js/app.min.js'
            }
        },
        imagemin: {
            dist: {
                options: { cache: false },
                files: [{
                    expand: true,
                    cwd: 'web/images/banners',
                    src: ['**/*/*.JPG', '**/*.JPG'],
                    dest: 'web/images/min/'
                }]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.registerTask('default', ['concat', 'uglify', 'imagemin']);
};
