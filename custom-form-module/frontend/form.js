/**
 * Frontend Form Validation and AJAX Submission
 */

jQuery(document).ready(function($) {
    // Custom file validation method
    $.validator.addMethod('filesize', function(value, element, param) {
        if (element.files && element.files[0]) {
            return element.files[0].size <= param;
        }
        return true;
    }, 'File size must be less than {0} MB');
    
    $.validator.addMethod('fileextension', function(value, element, param) {
        if (element.files && element.files[0]) {
            var ext = element.files[0].name.split('.').pop().toLowerCase();
            return $.inArray(ext, param) !== -1;
        }
        return true;
    }, 'Please upload a valid file type');

    $('#cf-form').validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            file: {
                required: true,
                filesize: (5 * 1024 * 1024), // 5 MB
                fileextension: ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']
            },
            message: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            name: {
                required: "Please enter your name",
                minlength: "Name must be at least 3 characters"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            phone: {
                digits: "Please enter only digits",
                minlength: "Phone must be 10 digits",
                maxlength: "Phone must be 10 digits"
            },
            file: {
                required: "Please upload a file",
                filesize: "File size must be less than 5 MB",
                fileextension: "Please upload a valid file (JPG, JPEG, PNG, PDF, DOC, DOCX)"
            },
            message: {
                required: "Please enter a message",
                minlength: "Message must be at least 10 characters"
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr('name') === 'file') {
                error.insertAfter('.cf-file-info');
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);
            var $errorDiv = $('#cf-error-message');
            $errorDiv.html('').hide();

            $.ajax({
                url: cf_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Clear any previous errors
                    $errorDiv.html('').hide();
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data
                        });
                        form.reset();
                        // Reset reCaptcha if present
                        if (typeof grecaptcha !== 'undefined') {
                            grecaptcha.reset();
                        }
                    } else {
                        $errorDiv.html(response.data).addClass('cf-error-message').show();
                    }
                },
                error: function() {
                    $errorDiv.html('An error occurred. Please try again.').addClass('cf-error-message').show();
                }
            });
        }
    });
});