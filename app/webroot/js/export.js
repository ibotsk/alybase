
var prefix = "/AlyssumPortalCake/";
//var prefix = "/";

$(document).ready(function () {
     
    $("#exportDialog").dialog({
        autoOpen: false,
        draggable: false
    });
    
    $("#exportChoice").click(function (e) {
        e.preventDefault();
        $("#exportDialog").dialog("open");
    });
    
    $("#exportSubmit").click(function (e) {
        var ids = $("#exportIds").val();
        var type = $("input[name='data[exportRadio]']:checked").val();
        var url = prefix + (type === 'rtf' ? 'checklists/view_rtf' : 'checklists/view_pdf');
        $.ajax({
            type: "POST",
            url: url,
            data: {
                data: ids
            }
        }).done(function(msg) {
            $("#exportDialog").dialog("close");
        });
    });
    
});