$(document).ready(function(){

    $(".btn-reply-to-reply").click(function(){

        var replyId = $(this).attr("id").substring(10);

        $("#reply-to-reply-" + replyId).css("display", "table");
        
    });

    $(".btn-reply-to-reply-cancel").click(function() {

        var replyId = $(this).attr("id").substring(26);

        $("#reply-to-reply-" + replyId).css("display", "none");
    })

    $(".btn-reply-to-reply-write").click(function() {

        var replyId = $(this).attr("id").substring(25);

        var message = $("#write-reply-message-" + replyId).val();
        
        $("#form_message").val(message);
        $("#form_reply_id").val(replyId);

        $("#form_save").click();
        
    });

    $(".btn-edit-reply").click(function() {

        var replyId = $(this).attr("id").substring(15);

        var message = $("#reply-message-" + replyId + " .reply-message-message").text();

        $("#form_edit_message").val(message);

        $("#form_edit_reply_id").val(replyId);

        $("#btn-open-modal").click();
        
    });
});