(function ($) {
    $(document).ready(function () {
        let search = false, firstConnect = false;
        postCodeSize = Math.pow(10, (+ksaSto.sizePostCode - 1)),
            franchises = [];

        $('.ksa_sto_search').on('input', function () {
            $('#ksa_sto_search_res').html('');
            $('#ksa_sto_franchise').html('');
            $('.ksa_sto_search_img').show();
            if (!search && !firstConnect) {
                firstConnect = true;
                $('.ksa_sto_search_img').show();
                ksaStoGetDataApi('ksa_sto_get_data', 'post_code=' + $(this).val().trim());
            } else if (Number.isInteger(+$(this).val()) && search && +$(this).val() > postCodeSize) {
                $('.ksa_sto_search_img').show();
                ksaStoSearchPostCode('ksa_sto_search_post_code', 'post_code=' + $(this).val().trim());
            } else if (search && $(this).val() <= postCodeSize) {
                $('#ksa_sto_search_res').html('<div class="ksa_sto_franchises"><p class="text-center">Not find post code</p></div>');
                $('.ksa_sto_search_img').hide(300);
            }
        });

        function ksaStoGetDataApi(action, params = false, afterResponse) {
            let data = {
                action: action,
                param: params,
            };
            $('div.full-loader-wrap').show();
            $.post(ksaSto.url, data, function (response) {
                if (response == 'success') {
                    search = true;

                    let postCode = $('.ksa_sto_search').val();
                    if (Number.isInteger(+postCode) && postCode > postCodeSize) {
                        ksaStoSearchPostCode('ksa_sto_search_post_code', 'post_code=' + postCode.trim());
                    } else {

                    }
                    console.log('search = ' + search);
                }
                firstConnect = false;
            });
        }

        function ksaStoSearchPostCode(action, params) {
            let seachData = {
                action: action,
                param: params,
            };
            $('div.full-loader-wrap').show();
            $.post(ksaSto.url, seachData, function (responseSearch) {
                let res = JSON.parse(responseSearch);
                if (res.status == 'success' && res.data.length) {
                    franchises = res.data;
                    console.log(franchises);
                    console.log(franchises.length);

                    let select = '<ul class="ksa_sto_franchises" id="ksa_sto_franchises">';
                    $.each(franchises, function (key, val) {
                        select += '<li data-id="' + key + '">' + val.franchise_name + '</li>';
                    });
                    select += '</ul>';
                    $('#ksa_sto_search_res').html(select);
                } else {
                    $('#ksa_sto_search_res').html('<div class="ksa_sto_franchises"><p class="text-center">' + res.message + '</p></div>');
                }
                $('.ksa_sto_search_img').hide(300);
            });
        }

        $(document).on('click', '#ksa_sto_search_res li', function () {
            $('#ksa_sto_search_res li').removeClass('active');
            $(this).addClass('active');

            let franchise = franchises[$(this).data('id')];
            let franchiseContent = '<div class="ksa_sto_franchise_content">';
            franchiseContent += '<p><strong>' + franchise.franchise_name + '</strong></p>';
            franchiseContent += '<p>' + franchise.phone + '</p>';
            franchiseContent += '<p><a href="mailto:' + franchise.email + '"_target="blank">' + franchise.email + '</a></p>';
            franchiseContent += '<p><a href="http://' + franchise.website + '" target="_blank">' + franchise.website + '</a></p>';
            franchiseContent += '<div class="wrap_img"><img src="' + ksaSto.urlImg + franchise.images + '" alt=""></div></div>';
            $('#ksa_sto_franchise').html(franchiseContent);
        });

    });
})(jQuery);