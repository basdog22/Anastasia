<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>

    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <?= HTML::script(Config::get("settings.cms.rel_includes").'/app/views/layouts/backend/lp-funcs.js') ?>
    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?= asset($dir . '/css/elfinder.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset($dir . '/css/theme.css') ?>">

    <!-- elFinder JS (REQUIRED) -->
    <script src="<?= asset($dir . '/js/elfinder.min.js') ?>"></script>

    <?php if ($locale)
    { ?>
        <!-- elFinder translation (OPTIONAL) -->
        <script src="<?= asset($dir . "/js/i18n/elfinder.$locale.js") ?>"></script>
    <?php } ?>
    <!-- Include jQuery, jQuery UI, elFinder (REQUIRED) -->

    <script type="text/javascript">
        $().ready(function () {
            var elf = $('#elfinder').elfinder({
                // set your elFinder options here
                <?php if($locale){ echo "lang: '$locale',\n"; } ?>
                url: '<?= URL::action('Barryvdh\Elfinder\ElfinderController@showConnector') ?>',  // connector URL

                customData: {
                    _token: '<?= csrf_token() ?>'
                },
                commandsOptions: {
                    getfile: {
                        oncomplete: 'destroy'
                    }
                },
                handlers : {
                    upload : function(event, instance) {
                        notifyFileUpload('<?= URL::to('backend/assets/savemedia'); ?>',event,instance);
                    },
                    remove : function(event, instance) {
                        notifyFileRemoval('<?= URL::to('backend/assets/removemedia'); ?>',event,instance);
                    },
                    paste : function(event,instance){
                        console.info(event);
                        console.info('TODO:WHAT HAPPENS HERE?');
                    },
                    rename : function(event,instance){
                        notifyFileUpload('<?= URL::to('backend/assets/savemedia'); ?>',event,instance);
                        event.data.added = null;
                        notifyFileRemoval('<?= URL::to('backend/assets/removemedia'); ?>',event,instance);
                    },

                    archive : function(event,instance){
                        notifyFileUpload('<?= URL::to('backend/assets/savemedia'); ?>',event,instance);

                    },
                    extract : function(event,instance){
                        notifyFileUpload('<?= URL::to('backend/assets/savemedia'); ?>',event,instance);
                        console.info('TODO:WHAT HAPPENS HERE?');
                    }
                },
                getFileCallback: function (file) {
                    window.parent.processSelectedFile(file.path, '<?= $input_id?>');
                    parent.jQuery("#laraModal").modal('hide');
                }
            }).elfinder('instance');
        });
    </script>


</head>
<body>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

</body>
</html>
