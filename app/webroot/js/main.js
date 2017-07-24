
var prefix = "/AlyssumPortalCake/";
//var prefix = "/";

$.fn.center = function () {
    var $this = this;
    $this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
            $(window).scrollLeft()) + "px");
    /*$this.css("height", ($(window).height() - 80) + "px");
     $(window).resize(function () {
     $this.css("height", ($(window).height() - 80) + "px");
     });*/
    return this;
};

function clearFieldsData() {
    $(".filter input[type='text'], .filter input[type='number']").val('');
    $(".filter select").val('');
}

function setMapwidth() {
    var mapwidth = $("#chromMapWrap").width() - 310;
    $("#chromMap").css("width", mapwidth + "px");
}

$(document).ready(function () {
    //var resultClickedPosition = {top: 0, left: 0};
    $("#chromMapWrap").hide();
    $("#fade").hide();

    $("#chromResults").jExpand();
    $("#chromResults .odd").click(function () {
        $tr = $(this).next("tr");
        if (($tr).is(':visible')) {
            $(this).find('span.arrow img').attr('src', prefix + 'img/icon-arrow-up.png');
        } else {
            $(this).find('span.arrow img').attr('src', prefix + 'img/icon-arrow-down.png');
        }
        var $ul = $tr.find('ul');
        var id = $(this).attr("id");
        var splitted = id.split('/');
        if ($ul.has('li').length === 0) {
            $.ajax({
                url: prefix + 'data/chromajax/',
                method: 'POST',
                data: {
                    type: splitted[0],
                    subj: splitted[1],
                    authorPu: $("#FilterAuthorPu").val(),
                    authorAn: $("#FilterAuthorAn").val(),
                    world1: $("#FilterWorld1").val(),
                    world2: $("#FilterWorld2").val(),
                    world3: $("#FilterWorld3").val(),
                    world4: $("#FilterWorld4").val(),
                    chromX: $("#FilterChromX").val(),
                    chromN: $("#FilterChromN").val(),
                    chromDn: $("#FilterChromDn").val(),
                    chromPloidy: $("#FilterChromPloidy").val(),
                    latDegrees: $("#FilterLatDegrees").val(),
                    latMinutes: $("#FilterLatMinutes").val(),
                    latSeconds: $("#FilterLatSeconds").val(),
                    latitude: $("input[name='data[Filter][latitude]']:checked").val(),
                    lonDegrees: $("#FilterLonDegrees").val(),
                    lonMinutes: $("#FilterLonMinutes").val(),
                    lonSeconds: $("#FilterLonSeconds").val(),
                    longitude: $("input[name='data[Filter][longitude]']:checked").val(),
                    range: $("#FilterRange").val()
                }
            }).done(function (html) {
                $ul.html(html);
            });
        }
    });

    /*$(".menu span").hover(function (e) {
        $(this).css('border-bottom', '3px solid #ffc62e');
    }, function (e) {
        if (!$(this).hasClass("active")) {
            $(this).css('border-bottom', 'none');
        }
    });*/

    $("#ClearFieldsData").click(function () {
        clearFieldsData();
    });

    if ($("#FilterTypesMultiple input").is(":checked")) {
        $("#FilterTypes").removeAttr('checked');
    }
    $("#FilterTypesMultiple input").change(function (e) {
        if ($(this).is(':checked')) {
            $("#FilterTypes").removeAttr('checked');
        }
    });
    $("#FilterTypes").change(function (e) {
        if ($(this).is(':checked')) {
            $("#FilterTypesMultiple input").removeAttr('checked');
        }
    });

    $("#FilterChromDn").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: prefix + 'data/ploidyautosuggest',
                dataType: 'JSON',
                method: 'POST',
                data: {
                    term: request.term,
                    type: 'dn'
                }
            }).done(function (data) {
                response(data);
            });
        }
    });
    
    $("#FilterChromN").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: prefix + 'data/ploidyautosuggest',
                dataType: 'JSON',
                method: 'POST',
                data: {
                    term: request.term,
                    type: 'n'
                }
            }).done(function (data) {
                response(data);
            });
        }
    });

    $("#home .ahidden").hide();
    $("#home a").click(function () {
        var href = $(this).attr("href");
        $("#home .ahidden").hide();
        $(href).toggle();
    });

    $("#navbarHeaderLink").hover(function() {
        var $src = $("#navbarLogo").attr('src');
        $("#navbarLogo").attr('src', $src.replace("AlyBase_4.png", "AlyBase_4_bw.png"));
    }, function() {
        var $src = $("#navbarLogo").attr('src');
        $("#navbarLogo").attr('src', $src.replace("AlyBase_4_bw.png", "AlyBase_4.png"));
    });

});


