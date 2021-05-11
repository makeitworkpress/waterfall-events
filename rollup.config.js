export default {
    input: './assets/js/src/app.js',
    watch: {
        include: ['./assets/js/src/*', './assets/js/src/modules/*'],
        clearScreen: false
    },    
    output: {
        file: './assets/js/waterfall-events.js',
        format: 'iife'
    }
};