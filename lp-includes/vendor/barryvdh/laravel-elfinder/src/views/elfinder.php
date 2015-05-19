

    <!-- jQuery and jQuery UI (REQUIRED) -->
<!--    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />-->
<!--    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
<!--    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>-->

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/elfinder.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/theme.css') ?>">

    <script>
        var lara,lang;
        lara = <?= json_encode(Config::get('settings'))?>;
        lang_strings = <?= json_encode(Lang::get('strings')) ?>;
        lang_messages = <?= json_encode(Lang::get('messages')) ?>;
    </script>

    <!-- elFinder JS (REQUIRED) -->
    <script src="<?= asset($dir.'/js/elfinder.full.js') ?>"></script>
    <?php if($locale){ ?>
    <!-- elFinder translation (OPTIONAL) -->
    <script src="<?= asset($dir."/js/i18n/elfinder.$locale.js") ?>"></script>
    <?php } ?>

    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function() {
            elFinder.prototype.i18.en.messages['cmdeditimage'] = 'Add file as...';
            elFinder.prototype._options.commands.push('loadfile');
            elFinder.prototype.commands.loadfile = function() {
                this.exec = function(hashes) {
                    console.info(hashes);
                }
                this.getstate = function() {
                    //return 0 to enable, -1 to disable icon access
                    return 0;
                }
            }
            $('#elfinder').elfinder({
                height: '800',
                // set your elFinder options here
                <?php if($locale){ ?>
                    lang: '<?= $locale ?>', // locale
                <?php } ?>
                customData: {
                    _token: '<?= csrf_token() ?>'
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
                url : '<?= URL::action('Barryvdh\Elfinder\ElfinderController@showConnector') ?>'  // connector URL
            });

        });
    </script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>