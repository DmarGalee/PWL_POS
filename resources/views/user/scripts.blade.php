<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                level_id: { required: true, number: true },
                username: { required: true, minlength: 3, maxlength: 20 },
                nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
                password: { minlength: 6, maxlength: 20 }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: "POST",  
                    data: $(form).serialize() + "&_method=PUT", 
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: "success",
                                title: "Sukses",
                                text: response.message
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            $(".error-text").text("");
                            $.each(response.msgField, function(prefix, val) {
                                $("#error-" + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi Kesalahan",
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
