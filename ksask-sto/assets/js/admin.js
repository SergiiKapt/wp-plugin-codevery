(function ($) {
    $(document).ready(function () {
        $('#ksa_sto_submit_check_api').click(function () {
            $('#ksa_sto_admin_loader_wrap').show();
            let formData = $('#ksa_sto_form_api_settings').serialize();
            ksaStoPostAjax('ksa_sto_admin_api_connect_check', formData);
        });

        function ksaStoPostAjax(action, params = false) {
            var data = {
                action: action,
                param: params,
            };
            $('div.full-loader-wrap').show();

            $.post(ajaxurl, data, function (response) {
                $('#ksa_sto_admin_loader_wrap').hide();
                $('.ksa_sto_admin_setting .error').remove();
                console.log(response);
                let res = JSON.parse(response);
                if (res.message)
                    $('.ksa_sto_message').html(res.message);
            });
        }

    });
})(jQuery);