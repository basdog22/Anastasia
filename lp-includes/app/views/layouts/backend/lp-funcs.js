function notifyFileUpload(notify_url,event, instance) {
    var uploadedFiles = event.data.added;
    var imagetypes = ['image/jpeg', 'image/png', 'image/gif','image/pjpeg','image/bmp','image/svg+xml','image/tiff','image/vnd.djvu'];
    var audiotypes = ['audio/basic', 'audio/L24', 'audio/mp4','audio/mpeg','audio/ogg','audio/flac','audio/opus','audio/vorbis','audio/vnd.rn-realaudio','audio/vnd.wave','audio/webm'];
    var videotypes = ['video/3gpp','video/avi', 'video/mpeg', 'video/mp4','video/ogg','video/quicktime','video/webm','video/x-matroska','video/x-ms-wmv','video/x-flv'];
    var archivetypes = ['application/vnd.debian.binary-package','application/gzip','application/zip','application/x-cpio','application/x-shar','application/x-tar','application/x-bzip2','application/x-gzip','application/x-lzip','application/x-lzma','application/x-lzop','application/x-xz','application/x-compress','application/x-7z-compressed','application/x-ace-compressed','application/x-astrotite-afa','application/x-alz-compressed','application/vnd.android.package-archive','application/x-arj','application/x-b1','application/vnd.ms-cab-compressed','application/x-cfs-compressed','application/x-dar','application/x-dgc-compressed','application/x-apple-diskimage','application/x-gca-compressed','application/x-lzh','application/x-rar-compressed','application/x-stuffit','application/x-stuffitx','application/x-gtar','application/x-zoo','application/x-rar'];
    for (i in uploadedFiles) {
        var file = uploadedFiles[i];
        if(jQuery.inArray(file.mime, imagetypes) >= 0){
            var type = 'image';
        }else if(jQuery.inArray(file.mime, audiotypes) >= 0){
            var type = 'audio';
        }else if(jQuery.inArray(file.mime, videotypes) >= 0){
            var type = 'video';
        }else if(jQuery.inArray(file.mime, archivetypes) >= 0){
            var type = 'archive';
        }else{
            var type = 'other';
        }
        jQuery.get(notify_url,{
            file: file,
            type: type
        },function(data){
            notifyJs(data);
            //function(event) { console.log(event.data); }
        },'json');

    }
}

function notifyFileRemoval(notify_url,event, instance) {
    if(typeof event.data.added =='undefined' || event.data.added ==null){
        var uploadedFiles = event.data.removed;
        var imagetypes = ['image/jpeg', 'image/png', 'image/gif','image/pjpeg','image/bmp','image/svg+xml','image/tiff','image/vnd.djvu'];
        var audiotypes = ['audio/basic', 'audio/L24', 'audio/mp4','audio/mpeg','audio/ogg','audio/flac','audio/opus','audio/vorbis','audio/vnd.rn-realaudio','audio/vnd.wave','audio/webm'];
        var videotypes = ['video/avi', 'video/mpeg', 'video/mp4','video/ogg','video/quicktime','video/webm','video/x-matroska','video/x-ms-wmv','video/x-flv'];
        var archivetypes = ['application/vnd.debian.binary-package','application/gzip','application/zip','application/x-cpio','application/x-shar','application/x-tar','application/x-bzip2','application/x-gzip','application/x-lzip','application/x-lzma','application/x-lzop','application/x-xz','application/x-compress','application/x-7z-compressed','application/x-ace-compressed','application/x-astrotite-afa','application/x-alz-compressed','application/vnd.android.package-archive','application/x-arj','application/x-b1','application/vnd.ms-cab-compressed','application/x-cfs-compressed','application/x-dar','application/x-dgc-compressed','application/x-apple-diskimage','application/x-gca-compressed','application/x-lzh','application/x-rar-compressed','application/x-stuffit','application/x-stuffitx','application/x-gtar','application/x-zoo','application/x-rar'];
        for (i in uploadedFiles) {
            var file = uploadedFiles[i];
            if(jQuery.inArray(file.mime, imagetypes) >= 0){
                var type = 'image';
            }else if(jQuery.inArray(file.mime, audiotypes) >= 0){
                var type = 'audio';
            }else if(jQuery.inArray(file.mime, videotypes) >= 0){
                var type = 'video';
            }else if(jQuery.inArray(file.mime, archivetypes) >= 0){
                var type = 'archive';
            }else{
                var type = 'other';
            }
            jQuery.get(notify_url,{
                file: file,
                type: type
            },function(data){
                notifyJs(data);
                //function(event) { console.log(event.data); }
            },'json');

        }
    }

}
function taskviewer(calEvent, jsEvent, view) {
    // change the border color just for fun
    $(this).css('border-color', 'red');

    var toggle;


    if(calEvent.complete=="1"){
        toggle = lang_strings.open;
    }else{
        toggle = lang_strings.close;
    }
    console.info(calEvent);
    var html = '<h3>'+calEvent.title+'</h3>';
    html += '<div class="tada well col-md-12">'+calEvent.description+'</div>';
    html += '<div class="clearfix"></div> ';
    html += '<label class="label label-info pull-left">'+calEvent.start._i+'</label>';
    html += '<label class="label label-danger pull-right">'+calEvent.end._i+'</label>';

    html += '<div class="clearfix"></div> ';
    html += '<div class="col-md-12">';
    html += '<div class="col-md-6">';
    html += '<a class="pull-left btn btn-danger delbtn" href="'+lara.cms.url+'/backend/tasks/delete/'+calEvent.id+'">'+lang_strings.delete+'</a> ';
    html += '</div>';
    html += '<div class="col-md-6">';
    html += '<a class="pull-right btn btn-warning" href="'+lara.cms.url+'/backend/tasks/toggle/'+calEvent.id+'">'+toggle+'</a>';
    html += '</div>';
    html += '</div>';
    html += '<div class="clearfix"></div> ';

    $("#laraModal .modal-body").html(html);
    $("#laraModal").modal();
}


function readable_date(timestamp){
    console.info(timestamp);
    var date = new Date(timestamp*1000);
    var year = date.getFullYear();
    var month =date.getMonth();
    var day = date.getDate();
    var hour = date.getHours();
    var minute = "0" + date.getMinutes();
    var second = "0" + date.getSeconds();
    var formattedTime = year + '-' + month + '-' + day + ' ' + hour + ':' + minute.substr(minute.length-2) + ':' + second.substr(second.length-2);
    return formattedTime;
}

function notifyJs(data) {
    $('<div class="alert alert-' + data.type + ' alert-dismissible " role="alert"><button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' + data.text + '</div>').appendTo('#notifications-container');
}