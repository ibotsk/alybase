
function clrReplyField(element) {
    var $input = element.find('div.input');
    $input.children('input').val('');
    $input.children('textarea').val('');
    $('#replyField').hide();
}

/**
 * 
 * @param {type} url
 * @param {type} indata serialised data
 * @param {type} referer element form sending request
 * @returns {undefined}
 */
function sendPostTo(url, indata, referer) {
    $.post(url, indata, function (data) {
        if (data) {
            alert("Your comment has been submitted and is waiting for approval");
            clrReplyField(referer);
        }
    });
}

$(document).ready(function () {

    var $rf = $('#replyField');
    $rf.hide();
    $('#addNewComment').click(function (e) {
        e.preventDefault();
        $rf.find('input#ParentId').val('null');
        $rf.appendTo($('div.comments'));
        $rf.show();
        $(this).hide();
    });

    $('a.reply').click(function (e) {
        e.preventDefault();
        $rf.hide().detach();
        var repToId = $(this).parents('div.comment').find('input#commentId').val();
        $rf.find('input#ParentId').val(repToId);
        $(this).parent('div.foot').append($rf);
        $(this).hide();
        $(this).siblings('a.closeReply').show();
        $('#addNewComment').show();
        $rf.show();
    });

    $('a.closeReply').hide();
    $('a.closeReply').click(function (e) {
        e.preventDefault();
        $rf.hide().detach().appendTo($('#addNewComment'));
        $(this).siblings('a.reply').show();
        $(this).hide();
    });

    $('#DcommentDetailForm').submit(function (e) {
        e.preventDefault();
        sendPostTo("/AlyssumPortalCake/dcomments/add", $(this).serialize(), $(this));
    });

    $('#LosCommentDetailForm').submit(function (e) {
        e.preventDefault();
        sendPostTo("/AlyssumPortalCake/loscomments/add", $(this).serialize(), $(this));
    });

});
