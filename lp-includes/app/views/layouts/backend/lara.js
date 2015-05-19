var odatatable = new Array();
var sel_elem = new Array();

$(document).ajaxComplete(function () {

    $("select:not(.no-select2)").select2();

    $('.select2-input').click(function () {
        $(this).focus();
    });

    if ($('.datepicker').length) {
        $('.datepicker').datepicker();
    }
    if ($('.timepicker').length) {
        $('.timepicker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            defaultTime: true
        });
    }

    $(".removeme").each(function(){
        if(!$(this).hasClass('remove_applied')){
            $("<i title='"+lang_strings.delete+"' class='fa fa-remove remove_row ttips'></i>").appendTo($(this));
            $(this).addClass('remove_applied');
        }
    });

    $(".cloneme").each(function(){
        if(!$(this).hasClass('clone_applied')){
            $("<i title='"+lang_strings.clone+"' class='fa fa-copy clone_row ttips'></i>").appendTo($(this));
            $(this).addClass('clone_applied');
        }
    });

    $(".ttips").tooltip();

    $(".tabable").tabs();



});
$(document).ready(function () {


    $(document).on('click','#tabs > ul li a', function (e) {
        localStorage.setItem('lastTab', $(this).attr('href'));
    });


    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $("#tabs > ul li a[href='"+lastTab+"']").trigger('click');
        }
    });

    if($("#elfinder").length){
        var me;
        $("#unisearch").keyup(function(){
            me = $(this);
            if($(this).val().length>2){
                $(".elfinder-button-search input[type='text']").val($(this).val());
                jQuery('.ui-icon-search').click();

                setTimeout(function(){
                    me.focus();
                },350);
            }
        });
    }

    $(document).on('click','.dialog-submitter',function(e){
        var a = window.prompt($(this).data('title'));
        if (a!==null && a.length>=1) {
            var newhref = $(this).attr('href')+'/'+a;
            document.location = newhref;
            return false;
        }
        e.preventDefault();
        return false;
    });

    //Stupid bug on some browsers that make inputs not get focus when in sortable divs
    $(document).on('click','input,select,textarea',function(){
        $(this).focus();
    });


    $(document).on('click', '.popup_selector', function (event) {
        event.preventDefault();
        var updateID = $(this).attr('data-inputid'); // Btn id clicked
        var elfinderUrl = lara.cms.url + '/backend/filemanager/standalonepopup/';
        // trigger the reveal modal with elfinder inside
        var triggerUrl = elfinderUrl + updateID;
        $("#laraModal .modal-body").html("<iframe style='border:0;width: 100%;height:450px' src='" + triggerUrl + "'></iframe>", function () {
        });
        $("#laraModal").modal();
        return false;
    });


    $("#tabs").tabs();

    $('.drg').draggable({
        cursor: 'move',          // sets the cursor apperance
        revert: 'valid',
        revertDuration: 200,
        opacity: 0.5
    });
    // sets droppable
    $('.drop').droppable({
        hoverClass: 'droppable',
        drop: function (event, ui) {

            ui.draggable.appendTo(this);
            var theid = $(this).parent().attr('id').split('-')[1];
            var layout = $(this).parent().parent().parent().parent().attr('id').replace('grid_','');
            var grid = $(this).parent().parent().parent().attr('id');
            saveBlockPosition(theid, ui.draggable.attr('id'), layout, grid);
        }
    });

    if ($('.datepicker').length) {
        $('.datepicker').datepicker();
    }
    if ($('.timepicker').length) {
        $('.timepicker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            defaultTime: true
        });
    }

    $(".sortblocks").sortable({
        revert: true,
        cursor: "move",
        forceHelperSize: true,
        forcePlaceholderSize: true,
        handle: ".handle",
        opacity: 0.8,
        zIndex: 9999,
        update: function (event, ui) {
            //line below gives the ids of elements, you can make ajax call here to save it to the database
            //console.log($(this).sortable('toArray'));
            var order = $(this).sortable("toArray").join(',');
            var theid = $(this).parent().attr('id').split('-')[1];
            var layout = $(this).parent().parent().parent().parent().attr('id').split('_')[1];
            var grid = $(this).parent().parent().parent().attr('id');
            saveBlockOrder(order, theid, grid);
        }
    });

    $(document).on('change', '.auto-input', function (e) {
        if ($(this).prop('checked')) {
            $.post(lara.cms.url + $(this).data('action'), {groupid: $(this).data('groupid'), perm: $(this).attr('name'), newval: 1}, function (data) {
                notifyJs(data);
            })
        } else {
            $.post(lara.cms.url + $(this).data('action'), {groupid: $(this).data('groupid'), perm: $(this).attr('name'), newval: 0}, function (data) {
                notifyJs(data);
            })
        }
    });

    $(document).on('click', '.modal-link', function (e) {
        e.preventDefault();
        $("#laraModal .modal-body").load($(this).attr('href'), function () {
//            LoadSelect2Script(doSelects);
        });
        $("#laraModal").modal();
        return false;
    });

    $('#laraModal').on('show.bs.modal', function () {
        var modalBody = $(this).find('.modal-body');
        var modalDialog = $(this).find('.modal-dialog');
        $(this).find('.modal-dialog').css('width', 900);

    });

    $('#laraModal').on('hidden.bs.modal', function () {
        $('#laraModal .modal-body').html('');
    });

    $(document).on('click', '.folder-link', function (e) {
        $('.folder-link ul').hide();
        $('ul', this).toggle();
    });

    $(".folder-link ul").hide();

    $('.folder-link ul li').each(function () {
        if ($(this).hasClass('lactive')) {
            $(this).parent().show();
        }
    });

    $(document).on('click', '.delbtn', function (e) {

        var a = window.confirm(lang_strings.delete_question);
        if (a) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.warnbtn', function (e) {

        var a = window.confirm(lang_strings.are_you_sure);
        if (a) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    $('.show-sidebar').on('click', function (e) {
        e.preventDefault();
        $('div#main').toggleClass('sidebar-show');
    });

    $('.main-menu').on('click', 'a', function (e) {
        var parents = $(this).parents('li');
        var li = $(this).closest('li.dropdown');
        var another_items = $('.main-menu li').not(parents);
        another_items.find('a').removeClass('active');
        another_items.find('a').removeClass('active-parent');
        if ($(this).hasClass('dropdown-toggle') || $(this).closest('li').find('ul').length == 0) {
            $(this).addClass('active-parent');
            var current = $(this).next();
            if (current.is(':visible')) {
                li.find("ul.dropdown-menu").slideUp('fast');
                li.find("ul.dropdown-menu a").removeClass('active')
            }
            else {
                another_items.find("ul.dropdown-menu").slideUp('fast');
                current.slideDown('fast');
            }
        }
        else {
            if (li.find('a.dropdown-toggle').hasClass('active-parent')) {
                var pre = $(this).closest('ul.dropdown-menu');
                pre.find("li.dropdown").not($(this).closest('li')).find('ul.dropdown-menu').slideUp('fast');
            }
        }
        if ($(this).hasClass('active') == false) {
            $(this).parents("ul.dropdown-menu").find('a').removeClass('active');
            $(this).addClass('active')
        }
    });
    $('.ScreenSaver').on('click', function (e) {
        e.preventDefault();
        $('div#main').toggleClass('sidebar-show');
    });
    $('#locked-screen').on('click', function (e) {
        e.preventDefault();
        $('body').addClass('body-screensaver');
        $('#screensaver').addClass("show");
        $.cookie('locked', 1, { expires: 7, path: '/'});

        ScreenSaver();
    });
    $('#screen_unlock').on('click', function () {

        OpenModalBox();
    });

    if ($.cookie('locked') == 1) {
        $("#locked-screen").trigger('click');
    }

    var screensaverTimer = setTimeout(function () {
        $("#locked-screen").trigger('click');
    }, lara.cms.screensaver_timeout * 1000);

    $(document).on('mousemove', 'body', function () {
        clearTimeout(screensaverTimer);
        screensaverTimer = setTimeout(function () {
            $("#locked-screen").trigger('click');
        }, lara.cms.screensaver_timeout * 1000);
    });
    // $("select:not(.no-select2)").select2();
    $('.main-menu li a').each(function () {
        if ($($(this))[0].href == String(window.location))
            $(this).addClass('active-parent active')
        $(this).parent().addClass('active');
        $(this).parent().parent().parent().addClass('active');
    });


    $(".sortable").sortable({
        revert: true,
        cursor: "move",
        forceHelperSize: true,
        forcePlaceholderSize: true,
        handle: ".handle",
        opacity: 0.8,
        zIndex: 9999,
        update: function (event, ui) {
            //line below gives the ids of elements, you can make ajax call here to save it to the database
            //console.log($(this).sortable('toArray'));
            var order = $(this).sortable("toArray").join(',');
            var cookie_name = event.target.id;
            var path = event.target.rel;
            $.cookie(cookie_name, order, {expires: 365, path: path});
        }
    });
    $(".sortable").disableSelection();

    $(".sortable").each(function(){
        var cookie_name = $(this).attr('id');
        if (typeof $.cookie(cookie_name) != 'undefined') {
            reorder($.cookie(cookie_name).split(","), $(".sortable"));
        }
    });


    $('.nestable').each(function () {
        $(this).nestable({
            group: 1
        }).on('change', updateFromNestable);
    });


    $(document).on('click', '.popup-link', function (e) {
        e.preventDefault();
        var me = $(this);

        $.get($(this).attr('href'), function (data) {

            me.data('content', data);
            me.popover({
                html: true,
                template: '<div class="popover popover-bigger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
            });
            //me.popover('show');
            $("#tabs").tabs();
        });

        return false;
    });

    $(document).on('change', '.auto-update-sort', function (e) {
        e.preventDefault();
        $.post(lara.cms.url + $(this).data('action'), {itemid: $(this).attr('id'), newsort: $(this).val()}, function (data) {
            notifyJs(data);
        })
        return false;
    });

    $(document).on('click', '.close-popup', function (e) {
        e.preventDefault();
        $("input[name='" + $(this).data('appendto') + "']").val($(this).data('href'));
        if ($("input[name='link_text']").length) {
            $("input[name='link_text']").val($(this).html());
        }

        $(this).parent().parent().parent().parent().parent().parent().parent().remove();
        return false;

    });

    $(document).on('click', '.transfer-url', function (e) {
        e.preventDefault();
        $("input[name='" + $(this).data('appendto') + "']").val($(this).data('href'));
        if ($("input[name='link_text']").length) {
            $("input[name='link_text']").val($(this).html());
        }


        return false;

    });

    if($(".popup-link").length){
        $(".popup-link").popover();
    }


    $(".incognito").attr('title',lang_strings.click_to_edit).addClass('ttips');
    $(".checkall").attr('title',lang_strings.select_all).addClass('ttips');
    $(".move-activ,.dd-handle").attr('title',lang_strings.draggable).addClass('ttips');
    $(".fa-life-ring").attr('title',lang_strings.help).addClass('ttips').data('placement','bottom');
    $(".ttips").tooltip();

    if ($(".scrollbars").length) {
        $(".scrollbars").each(function () {
            $(this).slimScroll({
                height: $(this).data('height'),
                wheelStep: 3
            });
        });
    }
    var i = 0;
    $('.table-datatable').each(function(){
        var tableobject = $(this).dataTable({
            "bPaginate": false,
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [ 0 ] }
            ]
        });
        odatatable.push(tableobject);
        i++;
    });


    $('#unisearch').keyup(function(){
        for(i=0; i<odatatable.length; i++){
            odatatable[i].fnFilter($(this).val());

        }

    });

    //assign hotkeys
    Mousetrap.bind(['command+l', 'ctrl+l'], function (e) {
        if ($.cookie('locked') == 1) {
            $('#screen_unlock').trigger('click');
        } else {
            $("#locked-screen").trigger('click');
        }

        return false;
    });


    Mousetrap.bind(['command+m', 'ctrl+m'], function (e) {
        $('.show-sidebar').trigger('click');
        return false;
    });

    Mousetrap.bind(['command+u', 'ctrl+alt+u'], function (e) {
        document.location = $('#userslink').attr('href');
        return false;
    });

    Mousetrap.bind(['command+t', 'ctrl+alt+t'], function (e) {
        document.location = $('#themeslink').attr('href');
        return false;
    });
    Mousetrap.bind(['command+u', 'ctrl+alt+a'], function (e) {
        document.location = $('#addonslink').attr('href');
        return false;
    });



    var lastChecked = null;
    var $chkboxes = $('input[type="checkbox"]');
    $chkboxes.click(function(e) {
        if(!lastChecked) {
            lastChecked = this;
            return;
        }

        if(e.shiftKey) {
            var start = $chkboxes.index(this);
            var end = $chkboxes.index(lastChecked);

            $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked', lastChecked.checked);

        }
        lastChecked = this;
    });

    $(document).on('change',".checkall",function(){
        if($(this).is(":checked")){
            $(".checkme").prop('checked',$(this).attr('checked'));
            $(".checkall").prop('checked',$(this).attr('checked'));
        }else{
            $(".checkme").prop('checked',false);
            $(".checkall").prop('checked',false);
        }
        $(".checkme").trigger('change');
    });

    $(document).on('change','.checkme',function(){
        if($(this).is(':checked')){
            sel_elem.push($(this).val());
            $(".bulk_actions").prop('disabled',false);
        }else{
            sel_elem.pop($(this).val());
            if(!sel_elem.length){
                $(".bulk_actions").prop('disabled',true);
            }
        }
    })

    //magic
    if($(".bulk_actions").length){
        var actions = $("#actions_container").data('actions').split(',');
        for(i=0;i<actions.length;i++){
            var trans_string = ""+actions[i]+"";
            $("<option value='m_"+actions[i]+"'>"+lang_strings[trans_string]+"</option>").appendTo(".bulk_actions");
        }

        $(document).on('change','.bulk_actions',function(e){
            if(sel_elem.length){
                var a = false;
                if($(this).val()=='m_delete'){
                    a = window.confirm(lang_strings.delete_question);
                }else{
                    a = true;
                }
                if (a) {
                    $.post($("#actions_container").data('base')+'/'+$(this).val(),{
                        ids: sel_elem
                    },function(response){
                        notifyJs(response);
                    });
                }
            }
        });
        $(".bulk_actions").prop('disabled',true);
    }


    $(".removeme").each(function(){
        if(!$(this).hasClass('remove_applied')){
            $("<i class='fa fa-remove remove_row'></i>").appendTo($(this));
            $(this).addClass('remove_applied');
        }
    });

    $(".cloneme").each(function(){
        if(!$(this).hasClass('clone_applied')){
            $("<i class='fa fa-copy clone_row'></i>").appendTo($(this));
            $(this).addClass('clone_applied');
        }
    });


    $(document).on('click','.remove_row',function(e){

        $(this).toggleClass('remove_applied');
        var toggler = $(this).hasClass('remove_applied');
        if(toggler){
            $(this).parent().css('opacity','.6');
        }else{
            $(this).parent().css('opacity','1');
        }
        $(':input',$(this).parent()).prop('disabled',toggler);
    });


    $(document).on('click','.clone_row',function(e){

        $('.select2-container',$(this).parent()).remove();
        var cloned = $(this).parent().clone();
        cloned.find(':input').each(function(){
            this.name = this.name.replace(/\[(\d+)\]/,function(str,p1){return '[' + (parseInt(p1,10)+1) + ']'});
        }).end();
        $(cloned).appendTo($(this).parent().parent());
        $("select:not(.no-select2)").select2();
        $(".ttips").tooltip();
    });

});

function saveBlockPosition(position, blockid, layout, grid) {
    $.post(lara.cms.url + "/backend/blockmanager/moveblock", {position: position, block: blockid, layout: layout, grid: grid}, function (data) {
        notifyJs(data);
    });
}

function updateFromNestable(e) {
    var list = e.length ? e : $(e.target);
    var action_url = $(e.currentTarget).data('action');
    $.post(lara.cms.url + action_url, {
        menu_order: list.nestable('serialize')
    }, function (response) {
        notifyJs(response);
    });
}

function reorder(orderArray, elementContainer) {
    $.each(orderArray, function (key, val) {
        elementContainer.append($("#" + val));
    });
}

function lockScreen() {

}
function OpenModalBox() {
    var who = window.prompt(lang_messages.who_are_you);
    if (who == lara.cms.screensaver_password) {
        $.cookie('locked', 0, { expires: 7, path: '/' });
        $('body').removeClass('body-screensaver');
        $('#screensaver').removeClass("show");
        var screensaverTimer = setTimeout(function () {
            $("#locked-screen").trigger('click');
        }, lara.cms.screensaver_timeout * 1000);
        return false;
    }
    if (who) {
        alert(lang_messages.no_access);
    }

}
function ScreenSaver() {


    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    // Size of canvas set to fullscreen of browser
    var W = window.innerWidth;
    var H = window.innerHeight;
    canvas.width = W;
    canvas.height = H;
    // Create array of particles for screensaver
    var particles = [];
    for (var i = 0; i < 25; i++) {
        particles.push(new Particle());
    }
    function Particle() {
        // location on the canvas
        this.location = {x: Math.random() * W, y: Math.random() * H};
        // radius - lets make this 0
        this.radius = 0;
        // speed
        this.speed = 3;
        // random angle in degrees range = 0 to 360
        this.angle = Math.random() * 360;
        // colors
        var r = Math.round(Math.random() * 255);
        var g = Math.round(Math.random() * 255);
        var b = Math.round(Math.random() * 255);
        var a = Math.random();
        this.rgba = "rgba(" + r + ", " + g + ", " + b + ", " + a + ")";
    }

    // Draw the particles
    function draw() {
        // re-paint the BG
        // Lets fill the canvas black
        // reduce opacity of bg fill.
        // blending time
        ctx.globalCompositeOperation = "source-over";
        ctx.fillStyle = "rgba(0, 0, 0, 0.02)";
        ctx.fillRect(0, 0, W, H);
        ctx.globalCompositeOperation = "lighter";
        for (var i = 0; i < particles.length; i++) {
            var p = particles[i];
            ctx.fillStyle = "white";
            ctx.fillRect(p.location.x, p.location.y, p.radius, p.radius);
            // Lets move the particles
            // So we basically created a set of particles moving in random direction
            // at the same speed
            // Time to add ribbon effect
            for (var n = 0; n < particles.length; n++) {
                var p2 = particles[n];
                // calculating distance of particle with all other particles
                var yd = p2.location.y - p.location.y;
                var xd = p2.location.x - p.location.x;
                var distance = Math.sqrt(xd * xd + yd * yd);
                // draw a line between both particles if they are in 200px range
                if (distance < 200) {
                    ctx.beginPath();
                    ctx.lineWidth = 1;
                    ctx.moveTo(p.location.x, p.location.y);
                    ctx.lineTo(p2.location.x, p2.location.y);
                    ctx.strokeStyle = p.rgba;
                    ctx.stroke();
                    //The ribbons appear now.
                }
            }
            // We are using simple vectors here
            // New x = old x + speed * cos(angle)
            p.location.x = p.location.x + p.speed * Math.cos(p.angle * Math.PI / 180);
            // New y = old y + speed * sin(angle)
            p.location.y = p.location.y + p.speed * Math.sin(p.angle * Math.PI / 180);
            // You can read about vectors here:
            // http://physics.about.com/od/mathematics/a/VectorMath.htm
            if (p.location.x < 0) p.location.x = W;
            if (p.location.x > W) p.location.x = 0;
            if (p.location.y < 0) p.location.y = H;
            if (p.location.y > H) p.location.y = 0;
        }
    }

    setInterval(draw, 30);
}

function FileUploadAddons() {
    $('#bootstrapped-fine-uploader').fineUploader({
        template: 'qq-template-bootstrap',
        classes: {
            success: 'alert alert-success',
            fail: 'alert alert-error'
        },
        thumbnails: {
            placeholders: {
                waitingPath: "assets/waiting-generic.png",
                notAvailablePath: "assets/not_available-generic.png"
            }
        },
        request: {
            endpoint: lara.cms.url + '/uploads/handleaddons',
            customHeaders: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        },


        validation: {
            allowedExtensions: ['zip']
        }
    });
}

function FileUploadThemes() {
    $('#bootstrapped-fine-uploader').fineUploader({
        template: 'qq-template-bootstrap',
        classes: {
            success: 'alert alert-success',
            fail: 'alert alert-error'
        },
        thumbnails: {
            placeholders: {
                waitingPath: "assets/waiting-generic.png",
                notAvailablePath: "assets/not_available-generic.png"
            }
        },
        request: {
            endpoint: lara.cms.url + '/uploads/handlethemes',
            customHeaders: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        },

        validation: {
            allowedExtensions: ['zip']
        }
    });
}

function LoadFineUploader(callback) {
    if (!$.fn.fineuploader) {
        $.getScript(lara.cms.rel_includes + '/app/views/layouts/backend/plugins/fineuploader/jquery.fineuploader-5.0.1.min.js', callback);
    }
    else {
        if (callback && typeof(callback) === "function") {
            callback();
        }
    }
}



function SetMinBlockHeight(elem) {
    elem.css('min-height', window.innerHeight - 50)
}



function processSelectedFile(filePath, requestingField) {
    $('#' + requestingField).val(filePath);
    $("#" + requestingField + "_preview").attr('src', lara.cms.url + '/lp-content/files/' + filePath.replace(filePath[0], filePath[0].toLowerCase()));
}
