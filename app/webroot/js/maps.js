var prefix = "/AlyssumPortalCake/";
//var prefix = "/";

function icon(i) {
    var base = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|';
    switch (i) {
        case 0:
            return base + 'FE7569'; //red
        case 1:
            return base + '699EFE'; //blue
        case 2:
            return base + 'A1FE69'; //green
        case 3:
            return base + 'FEC169'; //orange
        case 4:
            return base + 'CB69FE'; //violet
        case 5:
            return base + 'FCFE69'; //yellow
        default:
            return base + 'FE7569';
    }
}

/**
 * 
 * @param string dms format dd:mm:ss:{N/S/E/W}
 * @returns float decimal representation of coordinate
 */
function DMStoDec(dms) {
    if (dms === null || dms === '' || dms === 'null') {
        return new Array(); //out of range for latitude and longitude
    }
    var dmss = dms.split('-');
    var coords = [];
    for (var i = 0; i < dmss.length; i++) {
        var tokens = dmss[i].split(':');
        var deg = tokens[0] === '' ? 0 : parseFloat(tokens[0].replace(',', '.'));
        var min = tokens[1] === '' ? 0 : parseFloat(tokens[1].replace(',', '.'));
        var sec = tokens[2] === '' ? 0 : parseFloat(tokens[2].replace(',', '.'));
        var mod = tokens[3] === 'S' || tokens[3] === 'W' ? -1 : 1;
        coords.push((deg + min / 60 + sec / 3600) * mod);
    }
    return coords;
}

function initializeDetail(centers) {
//var cntrLatLng = new google.maps.LatLng(48.170, 17.149);
    var placeMarker = false;
    var center = new google.maps.LatLng(48.172873, 17.066532);
    if (centers !== null && center.length !== 0) {
        center = centers[0];
        placeMarker = true;
    }
    console.log(centers);
    var mapOptions = {
        center: center,
        zoom: 15,
        scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    };
    var map = new google.maps.Map($("#detailMap").get(0), mapOptions);
    if (placeMarker) {
        for (var i = 0; i < centers.length; i++) {
            var marker = new google.maps.Marker({
                position: centers[i],
                map: map
            });
        }
        var bounds = getBounds(centers);
        map.fitBounds(bounds);
        fitToMarkers(bounds, map);
    }
}

function initializeChrom() {
    var mapOptions = {
        center: new google.maps.LatLng(48.172873, 17.066532),
        zoom: 12,
        scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    };
    var map = new google.maps.Map($("#chromMap").get(0), mapOptions);
    return map;
}

//parameter mapping: {name: [{id, chrom}, {id, chrom}]}
function populateChrom(map, $element, mapping) {
    var om = new OverlappingMarkerSpiderfier(map);
    var bounds = new google.maps.LatLngBounds();
    $("#loader").show();
    $.ajax({
        url: prefix + 'materials/coordinates/',
        method: 'POST',
        data: {
            data: mapping
        }
    }).done(function (data) {
        var latlngs = chromosomeMarkers(JSON.parse(data), map, om);
        bounds = getBounds(latlngs);
        map.fitBounds(bounds);
        fitToMarkers(bounds, map);
        $("#loader").hide();
        return false;
    });
    google.maps.event.trigger(map, 'resize');
}

function detailMap() {
    var publLat = DMStoDec($('#detail-published-lat').val());
    var publLon = DMStoDec($('#detail-published-lon').val());
    var georLat = DMStoDec($('#detail-georef-lat').val());
    var georLon = DMStoDec($('#detail-georef-lon').val());
    var latlng = [];
    if (publLat.length !== 0 && publLon.length !== 0) {
        for (var i = 0; i < publLat.length; i++) {
            latlng.push(new google.maps.LatLng(publLat[i], publLon[i]));
        }
    } else if (georLat.length !== 0 && georLon.length !== 0) {
        for (var i = 0; i < georLat.length; i++) {
            latlng.push(new google.maps.LatLng(georLat[i], georLon[i]));
        }
    }
    return latlng;
}

function createMapping($element) {
    var mapping = [];
    $element.each(function () {
        var record = {id: $(this).attr("id"), chrom: $(this).find("p:first-child").text()};
        mapping[mapping.length] = record;
    });
    return mapping;
}

/**
 * Creates markers for the map according to the chromosome count
 * @param JSON data
 * @param {type} map
 * @returns {undefined}
 */
function chromosomeMarkers(data, map, oms) {
    var latlngs = new Array();
    var c = 0;
    var markers = [];
    var content = '';
    var infowindow = new google.maps.InfoWindow({content: content});
    //$("#chromMapLegend").append('<p style="font-size: 1.2em; font-weight: bold;">' + $name.text() + '</p>');
    $.each(data, function (name, group) {
        $("#chromMapLegend").append('<p style="font-size: 1.2em; font-weight: bold;">' + name + '</p>');
        var showingMarkers = 0;
        $.each(group, function (chromname, chrom) {
            showingMarkers = 0;
            $.each(chrom, function (index, v) {
                if (v.lat !== "" && v.lon !== "") {
                    var lats = DMStoDec(v.lat);
                    var lons = DMStoDec(v.lon);
                    for (var i = 0; i < lats.length; i++) {
                        var lat = lats[i];
                        var lon = lons[i];
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat, lon),
                            icon: icon(c),
                            clickable: true,
                            id: v.id,
                            idr: v.idr, //id record (temporary)
                            ch: chromname,
                            map: map
                        });
                        showingMarkers++;
                        oms.addListener('click', function (markerr, event) {
                            content = '<p>' + name + '</p><p>' + markerr.idr + '</p><p>' + markerr.ch + '</p><p><a href="' + prefix + 'data/detail/' + markerr.id + '">Detail...</a></p>';
                            infowindow.setContent(content);
                            infowindow.open(map, markerr);
                        });
                        oms.addMarker(marker);
                        //markers.push(marker);
                        latlngs[latlngs.length] = new google.maps.LatLng(lat, lon);
                    }
                }
            });
            $("#chromMapLegend").append(createLegend(chromname, icon(c), chrom.length, showingMarkers));
            c++;
        });
    });
    //var markerCluster = new MarkerClusterer(map, markers);
    return latlngs;
}

function getBounds(latlngs) {
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < latlngs.length; i++) {
        bounds.extend(latlngs[i]);
    }
    return bounds;
}

function createLegend(label, icon, count, showing) {
    var img = '<img src="' + icon + '" alt="' + label + '" height="12" />';
    return '<p>' + img + '<span>' + label + '</span>(' + count + ' record(s) total; ' + showing + ' record(s) with coordinates shown on the map)</p>';
}

function fitToMarkers(bounds, map) {
    if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
        var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.01, bounds.getNorthEast().lng() + 0.01);
        var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - 0.01, bounds.getNorthEast().lng() - 0.01);
        bounds.extend(extendPoint1);
        bounds.extend(extendPoint2);
    }
    map.fitBounds(bounds);
}

$(document).ready(function () {
    var chromMap = null;

    $("#results").on('click', "ul li a.showmap", function (e) {
        e.preventDefault();
        setMapwidth();
        $("#chromMapLegend").text("");
        $("#chromMapWrap").center().show();
        $("#fade").show();
        chromMap = initializeChrom();
        var mapping = [];
        mapping.push({
            name: $(this).parents("tr").prev("tr").children("td:nth-child(2)").text(),
            chromosomes: []
        });
        $(this).parent("li").siblings("li[id]").each(function () {
            var chrom = $(this).find("span.value").map(function () {
                return $(this).text();
            }).toArray().join(", ");
            mapping[0].chromosomes.push({id: $(this).attr("id"), chrom: chrom});
        });
        populateChrom(chromMap, $(this).parents('ul'), mapping);
    });

    $("#showAllOnMap").click(function (e) {
        e.preventDefault();
        var $tr = $("#results tr[id]");
        var ids = [];
        var type = $tr.attr("id").split("/")[0];
        $tr.each(function () {
            var spl = $(this).attr("id").split("/");
            ids.push(spl[1]);
        });
        $.ajax({
            url: prefix + 'data/chromajaxmap/',
            method: 'POST',
            data: {
                type: type,
                subj: ids,
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
            /*$("#chromMapLegend").text("");
             $("#chromMapWrap").center().show();
             $("#fade").show();
             chromMap = initializeChrom();
             populateChrom(chromMap, $)*/
            console.log(html);
        });
    });

    $("#closeMap").click(function (e) {
        $("#chromMapWrap").hide();
        $("#fade").hide();
    });
    if ($("#detailMap").length > 0) {
        var centers = detailMap();
        initializeDetail(centers);
    }

    $(window).resize(function () {
        setMapwidth();
    });

});