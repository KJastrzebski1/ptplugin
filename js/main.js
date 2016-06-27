

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
    countries.then(function (result) {
        result = JSON.parse(result);
        $('#country-search').autocomplete({
            source: result,
            select: function(event, ui){
                var country = ui.item.value;
                if(country.search(" & ") > -1){
                    country = country.toLowerCase().replace(' & ', '-');
                }
                if(country.search(" ") > -1){
                    country = country.toLowerCase().replace(' ', '-');
                }
                if(country.search(".") > -1){
                    country = country.toLowerCase().replace('.', '');
                }
                window.location.href = '/country/' + country;
            }
        });
    });
    var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    for (var i = 0; i < letters.length; i++) {
        $('#letters-list').append('<span id="' + letters[i] + '-countries" class="search-letters">' + letters[i] + '</span>');
    }
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
            var name = $(this).children('.country-name').html().toLowerCase();
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
});
