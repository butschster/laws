module.exports = {
    mounted () {
        Bus.$on('validation.thrown', (errors) => {
            _.forEach(errors.errors, (list, i) => {
                let $input = $('[validation-key="'+i+'"]').addClass('is-invalid');

                _.forEach(list, error => {
                    $('<small class="invalid-feedback validation-error">'+error+'</small>').insertAfter($input);
                })
            });
        });
    },
    methods: {
        clearInvalidInputs() {
            $('[validation-key]').removeClass('is-invalid');
            $('.validation-error').remove();
        }
    }
}