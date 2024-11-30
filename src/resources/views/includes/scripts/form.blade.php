<script type="text/javascript">
    $(document).ready(function() {
        $('.onSave').on('click', function() {
            let $form = $('#onSaveForm');
            let $formData = new FormData($('#onSaveForm')[0]);

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $formData,
                contentType: false,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function (res) {
                    if(res.status == 1){
                        if(res.redirect){
                            window.location = res.redirect;
                        } else {
                            $('.action-message').html(`<div class="alert alert-success">`+ res.message +`</div>`);
                        }
                    } else if(res.status == 0) {
                        $('.action-message').html(res.message);
                         
                        if(res.errors) {
                            /** Mark form fields with errors warnings **/
                            $.each(res.errors, function (id, message) {

                                id = id.split('.');

                                $("input[name=" + id + "], select[name=" + id + "], textarea[name=" + id + "]").addClass('is-invalid');
                            });

                            $('html,body').animate({
                                scrollTop: $('.is-invalid').first().offset().top - 200
                            }, 'slow');
                        }

                        $('.action-message').html(`<div class="alert alert-danger">`+ res.message +`</div>`);
                    } else {
                    }
                }
            })
        })

        $('.doDelete').on('click', function() {

            let delete_id = $(this).data('delete_id');
            let csrf_token = $(this).data('csrf');
            let url = $(this).data('action');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    delete_id
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                cache: false,
                dataType: 'json',
                success: function (res) {
                    if(res.status == 1){
                        if(res.redirect){
                            window.location = res.redirect;
                        } else {
                            $('.action-message').html(`<div class="alert alert-success">`+ res.message +`</div>`);
                        }
                    } else if(res.status == 0) {
                        if(res.errors) {
                            /** Mark form fields with errors warnings **/
                            $.each(res.errors, function (id, message) {

                                id = id.split('.');

                                $("input[name=" + id + "], select[name=" + id + "], textarea[name=" + id + "]").addClass('is-invalid');
                            });

                            $('html,body').animate({
                                scrollTop: $('.is-invalid').first().offset().top - 200
                            }, 'slow');
                        }

                        $('.action-message').html(`<div class="alert alert-danger">`+ res.message +`</div>`);
                   } else {
                        // error
                    }
                }
            })
        })
    })
</script>