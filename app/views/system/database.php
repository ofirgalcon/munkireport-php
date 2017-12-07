<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row">
        <h3 class="col-lg-12" data-i18n="system.database.migrations">Database</h3>
    </div>
    <div class="row">
        <div id="mr-migrations" class="col-lg-12">
            <span id="database-update-count"></span>
            <h4 data-i18n="database.migrations.pending"> Database Update(s) Pending</h4>
        </div>
        <div id="mr-sqllog" class="col-lg-6">
            <h4 data-i18n="database.log">Upgrade Log</h4>
            <table class="table table-console">
                <tr><td data-i18n="database.loghelp">Perform an upgrade and the log results will be displayed here</td></tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button id="db-upgrade" class="btn btn-default">
                <span id="db-upgrade-label" data-i18n="database.upgrade">Upgrade now</span>
                <span class="glyphicon glyphicon-export"></span>
            </button>
        </div>
    </div>
</div>  <!-- /container -->

<script>
    $(document).on('appReady', function(e, lang) {
        $('#db-upgrade').click(function(e) {
            $(this).attr('disabled', true);
            $(this).find('#db-upgrade-label').html('Upgrading&hellip;');
            var $btn = $(this);

            function done() {
                $btn.attr('disabled', false);
                $btn.find('#db-upgrade-label').html('Upgrade now');
            }

            $.getJSON(appUrl + '/system/migrate', function(data) {
                done();

                if (data.notes) {
                    var table = $('#mr-sqllog table').empty();

                    for (var i = 0; i < data.notes.length; i++) {
                        table.append($('<tr><td>' + data.notes[i] + '</td></tr>')); // .text(data.notes[i])
                    }
                }
            }).fail(function(jqXHR, textStatus, error) {
                done();
            })
        });
        
        $.getJSON(appUrl + '/system/migrationsPending', function( data ) {
            var table = $('#mr-migrations table').empty();

            if (data.error) {
                  
            } else {

            }

            $('#database-update-count').text(data['files_pending'].length);

            if (data.hasOwnProperty('files_pending')) {
                for (var i = 0; i < data['files_pending'].length; i++) {
                    table.append($('<tr><td></td></tr>').text(data['files_pending'][i]));
                }
            }
        })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                $('#mr-db table tr td')
                    .empty()
                    .addClass('text-danger')
                    .text(i18n.t('errors.loading', {error:err}));
            });
    });
</script>
<?php
$this->view('partials/foot');