var $ = jQuery.noConflict();
function validateFile(delete_id, error_id, obj, target)
{
    removeToolTipErrorManual("all");
    if(( typeof obj.files == undefined) || !obj.files[0] || obj.files[0]==undefined || obj.files[0]=='undefined') {
        $( delete_id ).trigger( "click" );
        return false;
    }

    var fileSize = obj.files[0].size;
    var fileSize_validate = fileSize;

    var jpeg_regex = new RegExp("(.*?)\.(jpeg)$");
    var jpg_regex = new RegExp("(.*?)\.(jpg)$");
    var png_regex = new RegExp("(.*?)\.(png)$");
    var pdf_regex = new RegExp("(.*?)\.(pdf)$");

    var tiff_regex = new RegExp("(.*?)\.(tiff)$");
    var gif_regex = new RegExp("(.*?)\.(gif)$");
    var bmp_regex = new RegExp("(.*?)\.(bmp)$");
    var exif_regex = new RegExp("(.*?)\.(exif)$");
    var bpg_regex = new RegExp("(.*?)\.(bpg)$");
    var rtf_regex = new RegExp("(.*?)\.(rtf)$");

    if( (jpeg_regex.test(target.files[0].type) == false) &&
        (jpg_regex.test(target.files[0].type) == false) &&
        (png_regex.test(target.files[0].type) == false) &&
        (pdf_regex.test(target.files[0].type) == false) &&
        (tiff_regex.test(target.files[0].type) == false) &&
        (gif_regex.test(target.files[0].type) == false) &&
        (bmp_regex.test(target.files[0].type) == false) &&
        (exif_regex.test(target.files[0].type) == false) &&
        (bpg_regex.test(target.files[0].type) == false) &&
        (rtf_regex.test(target.files[0].type) == false)) {
        $(delete_id).trigger( "click" );
        showToolTipErrorManual(error_id, "You can only upload jpeg/png/pdf/tiff/gif/bmp/exif/bpg/rtf files.", "bottom", $("#"+error_id), undefined);
        return false;
    }

    if(fileSize_validate > 2000000) {
        $( delete_id).trigger( "click" );
        showToolTipErrorManual(error_id, "Please upload file less than 2Mbs.", "bottom", $("#"+error_id), undefined);
        return false;
    }
    removeToolTipErrorManual("all");
    return true;
}
$(document).ready(function () {
    $('#upload-single-doc').change(function() {
        removeToolTipErrorManual("all");
        $("#single-doc-file").val("");
        if($('#upload-single-doc').val() == "default") {
            $('#single-doc-browse').css("display", "none");
            return false;
        }
        $('#single-doc-browse').css("display", "block");
    });

    $('#single-doc-file').bind('change', function(event) {
        if($(this).val() == "")
            return false;

        if(validateFile("#single-doc-delete", "upload-single-doc", this, event.target) == false)
            return false;

        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;

        if(fileSize/1024 < 1)
            fileSize = fileSize + " Bytes";
        else if(Math.round(fileSize/1024) == 1)
            fileSize = Math.round(fileSize/1024) + " KB";
        else if(Math.round(fileSize/1024) > 1)
            fileSize = Math.round(fileSize/1024) + " KB's";

        $('#single-document-preview').css("display", "block");
        $('#single-document-preview').parent().css("display", "block");

        $('#single-document-preview span.document_name').html('<strong>'+fileName+'</strong>');

        $('#single-doc-browse').css("display", "none");
        // $('#single-doc-select').css("display", "none");
        $('#single-doc-upload-btn').css("display", "block");
    });

    $('#single-doc-edit').click(function() {
        $( "#single-doc-file" ).trigger( "click" );
    });

    $('#single-doc-delete').click(function() {
        $('#single-document-preview span.document_name').html('');
        $('#single-document-preview').css("display", "none");
        $('#single-document-preview').parent().css("display", "none");
        $('#single-doc-upload-btn').css("display", "none");
        $('#single-doc-select').css("display", "block");
        $('#single-doc-browse').css("display", "none");
        $("#single-doc-file").val("");
        $("#upload-single-doc").val("default");
    });

    $('#single-doc-upload-btn').click(function() {

        $('#single-doc-upload-btn').css("display", "none");
        var formData = new FormData();
        formData.append('file', $('#single-doc-file')[0].files[0]);
        formData.append('uploadType', 'SINGLE');
        formData.append('isAjax', 'true');
        formData.append('single', $("#upload-single-doc").val());
        var params = formData;

        startAjaxFileUpload(withdrawalDocUploadURL, params, getSingleDocUploadResponse, 'null', function () {
            $("#single-doc-progress-bar").css("display", "block");
        }, function () {
            $("#single-doc-progress-bar").css("display", "none");
        });
    });

    $('#upload-multi-id-doc').change(function() {
        removeToolTipError("all");
        $("#multi-id-doc-file").val("");
        if($('#upload-multi-id-doc').val() == "default") {
            $('#multi-id-doc-browse').css("display", "none");
            //$('#custom-multi-id').css("display", "none");
            return false;
        }
        $('#multi-id-doc-browse').css("display", "block");

    });

    $('#multi-id-doc-file').bind('change', function(event) {
        if($(this).val() == "")
            return false;

        if(validateFile("#multi-id-doc-delete", "upload-multi-id-doc", this, event.target) == false)
            return false;

        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;

        if(fileSize/1024 < 1)
            fileSize = fileSize + " Bytes";
        else if(Math.round(fileSize/1024) == 1)
            fileSize = Math.round(fileSize/1024) + " KB";
        else if(Math.round(fileSize/1024) > 1)
            fileSize = Math.round(fileSize/1024) + " KB's";

        $('#multi-id-document-preview').css("display", "block");
        $('#multi-id-document-preview').parent().css("display", "block");

        $('#multi-id-document-preview span.document_name').html('<strong>'+fileName+'</strong>');

        $('#multi-id-doc-browse').css("display", "none");
        // $('#multi-id-doc-select').css("display", "none");
        //$('#custom-multi-id').css("display", "none");

        if($("#multi-add-document-preview").css("display") == 'none') {
            $('#multi-doc-upload-btn').css("display", "none");
        }
        else {
            $('#multi-doc-upload-btn').css("display", "block");
        }
    });

    $('#multi-id-doc-edit').click(function() {
        $( "#multi-id-doc-file" ).trigger( "click" );
    });

    $('#multi-id-doc-delete').click(function() {
        $('#multi-id-document-preview span.document_name').html('');
        $('#multi-id-document-preview').css("display", "none");
        $('#multi-id-document-preview').parent().css("display", "none");
        $('#multi-doc-upload-btn').css("display", "none");
        $('#multi-id-doc-select').css("display", "block");
        $('#multi-id-doc-browse').css("display", "none");
        $("#multi-id-doc-file").val("");
        $("#upload-multi-id-doc").val("default");
    });

    $('#upload-multi-add-doc').change(function() {
        removeToolTipError("all");
        if($('#upload-multi-add-doc').val() == "default") {
            $('#multi-add-doc-browse').css("display", "none");
            //$('#custom-multi-add').css("display", "none");
            return false;
        }

        $('#multi-add-doc-browse').css("display", "block");

    });

    $('#multi-add-doc-file').bind('change', function(event) {
        if($(this).val() == "")
            return false;

        if(validateFile("#multi-add-doc-delete", "upload-multi-add-doc", this, event.target) == false)
            return false;

        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;

        if(fileSize/1024 < 1)
            fileSize = fileSize + " Bytes";
        else if(Math.round(fileSize/1024) == 1)
            fileSize = Math.round(fileSize/1024) + " KB";
        else if(Math.round(fileSize/1024) > 1)
            fileSize = Math.round(fileSize/1024) + " KB's";

        $('#multi-add-document-preview').css("display", "block");
        $('#multi-add-document-preview').parent().css("display", "block");

        $('#multi-add-document-preview span.document_name').html('<strong>'+fileName+'</strong>');

        $('#multi-add-doc-browse').css("display", "none");
        // $('#multi-add-doc-select').css("display", "none");
        //$('#custom-multi-add').css("display", "none");
        $('#multi-doc-upload-btn').css("display", "block");

        if($("#multi-id-document-preview").css("display") == 'none') {
            $('#multi-doc-upload-btn').css("display", "none");
        }
        else {
            $('#multi-doc-upload-btn').css("display", "block");
        }

    });

    $('#multi-add-doc-edit').click(function() {
        $( "#multi-add-doc-file" ).trigger( "click" );
    });

    $('#multi-add-doc-delete').click(function() {

        $('#multi-add-document-preview span.document_name').html('');
        $('#multi-add-document-preview').css("display", "none");
        $('#multi-add-document-preview').parent().css("display", "none");
        $('#multi-doc-upload-btn').css("display", "none");
        $('#multi-add-doc-select').css("display", "block");
        $('#multi-add-doc-browse').css("display", "none");
        $("#multi-add-doc-file").val("");
        $("#upload-multi-add-doc").val("default");
    });

    $('#multi-doc-upload-btn').click(function() {

        $('#multi-add-doc-upload-btn').css("display", "none");
        var formData = new FormData();
        formData.append('id-proof-file', $('#multi-id-doc-file')[0].files[0]);
        formData.append('address-proof-file', $('#multi-add-doc-file')[0].files[0]);
        formData.append('uploadType', 'MULTI');
        formData.append('isAjax', 'true');

        formData.append('id', $("#upload-multi-id-doc").val());
        formData.append('address', $("#upload-multi-add-doc").val());
        var params = formData;
        startAjaxFileUpload(withdrawalDocUploadURL, params, getMultiDocUploadResponse, 'null');
    });
});

function getSingleDocUploadResponse(result)
{
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);
    if(res.errorCode != 0) {
        showToolTipErrorManual("upload-single-doc", res.respMsg, "bottom", $("#upload-single-doc"), undefined);
        return false;
    }

    $("div#upload_doc_tab>ul, div#upload_doc_tab>div").css('display', 'none');
    $("div#upload_doc_tab>div.upload_doc_thankyou").css('display', 'block');
}


function getMultiDocUploadResponse(result)
{
    if(validateSession(result) == false)
        return false;

    var res = $.parseJSON(result);
    if(res.errorCode != 0) {
        showToolTipErrorManual("upload-multi-id-doc", res.respMsg, "bottom", $("#upload-multi-id-doc"), undefined);
        return false;
    }

    $("div#upload_doc_tab>ul, div#upload_doc_tab>div").css('display', 'none');
    $("div#upload_doc_tab>div.upload_doc_thankyou").css('display', 'block');
}