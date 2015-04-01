/*global module:false*/
module.exports = function (grunt) {
    'use strict'

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            dist: {
                options: {
                    config: 'config.rb'
                }
            }
        },
        coffee: {
            modules: {
                files: [
                    {
                        expand: true,
                        cwd: 'libs/coffee/',
                        src: ['**/**/*.coffee'],
                        dest: 'libs',
                        ext: '.js'
                    }
                ]
            },
            tests: {
                files: [
                    {
                        expand: true,
                        cwd: 'libs/tests/coffee/',
                        src: ['**/**/*.coffee'],
                        dest: 'libs/tests/js/',
                        ext: '.js'
                    }
                ]
            }
        },

        concat: {
            dist: {
                src: [ 'libs/js/*.js', 'libs/js/**/*.js'],
                dest: 'libs/main.js'
            }
        },

        uglify: {

            prod: {
                files: {
                    'main.min.js': ['libs/main.js']
                }
            }
        },

        jasmine : {
            src: ['libs/js/beautytrends/**/**/*.js'],
            options: {
                specs: 'libs/tests/js/**/*.js',
                vendor: ['libs/js/vendors/jquery-1.10.1.min.js', 'libs/tests/vendors/jasmine-jquery.js']
            }
        },

        watch: {
            coffee: {
                files: ['inc/coffee/*.coffee'],
                tasks: ['coffee', 'concat', 'uglify']
            },
            compass: {
                files: ['inc/sass/*.sass'],
                tasks: 'compass'
            }
        },

        docco: {
            debug: {
                src: ['libs/tests/coffee/**/*.coffee', 'libs/coffee/**/**/*.coffee'],
                options: {
                    output: 'docs/'
                }
            }
        }


    });

    // Load necessary plugins
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jasmine');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-docco2');

    grunt.registerTask('default', ['compass', 'coffee', 'concat', 'uglify']);

};