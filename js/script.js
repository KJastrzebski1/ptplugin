

jQuery(document).ready(function ($) {
    var pt_search = '';
    var url = ajax_object.ajax_url;
    var countries = new Promise(function (resolve, reject) {
        $.ajax({
            type: "GET",
            url: url,
            data: {'action': 'get_countries'},
            success: function (data) {
                resolve(data);
            }});
    });
    showHideVas();
    countries.then(function (result) {
        result = JSON.parse(result);
        $('#country-search').autocomplete({
            source: result,
            select: function (event, ui) {
                var country = ui.item.value;
                if (country.search(" & ") > -1) {
                    country = country.toLowerCase().replace(' & ', '-');
                }
                if (country.search(" ") > -1) {
                    country = country.toLowerCase().replace(' ', '-');
                }
                if (country.search(".") > -1) {
                    country = country.toLowerCase().replace('.', '');
                }
                window.location.href = '/country/' + country;
            }
        });
    });

    $('#country-search').keyup(function () {
        toggleActive();
        pt_search = $("#country-search").val().toLowerCase();
        showHideCountries();
    });

    $(".search-letters").on('click', function () {
        pt_search = $(this).html().toLowerCase();
        toggleActive();
        $(this).toggleClass('active');
        showHideCountries(true);
    });

    $("#all-rates").on('click', function () {
        toggleActive();
        pt_search = '';
        showHideCountries();
    });

    function toggleActive() {
        $("#letters-list").find('.active').each(function () {
            $(this).toggleClass('active');
        });
    }
    function showHideCountries(onlyFirst) {
        onlyFirst = typeof onlyFirst !== 'undefined' ? onlyFirst : false;

        $('.table tbody tr').each(function () {
            var name = $(this).children('.country-name').children('a').html().toLowerCase();
            if (onlyFirst) {

                if (name.indexOf(pt_search) !== 0) {

                    $(this).hide();
                } else {
                    $(this).show();

                }
            } else {
                if (name.indexOf(pt_search) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }


        });
    }
    function showHideVas() {
        var opt = new Promise(function (resolve, reject) {
            $.ajax({
                type: "GET",
                url: url,
                data: {'action': 'get_option_show_vas'},
                success: function (data) {
                    resolve(data);
                }});
        });
        opt.then(function (result) {
            result =JSON.parse(result);
            if (result == 1) {
                
                $('.table tbody tr').each(function () {
                    var name = $(this).children('td').html().toLowerCase();
                    if (name.indexOf('vas') > -1 ) {
                        
                        $(this).hide();
                    } else {
                        $(this).show();

                    }
                });
            }
        });
    }
});
