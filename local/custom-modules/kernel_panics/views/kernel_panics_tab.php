<div id="kernel_panics-tab"></div>
<h2 data-i18n="kernel_panics.kernel_panics"></h2>

<div id="kernel_panics-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/kernel_panics/get_tab_data/' + serialNumber, function(data){
        
        // Check if we have data
        if( data == "" || ! data){
            $('#kernel_panics-msg').text(i18n.t('no_data'));
            $('#kernel_panics-cnt').text('');
            
        } else {
            
            // Hide
            $('#kernel_panics-msg').text('');
            $('#kernel_panics-count-view').removeClass('hide');
            
            // Set count of kernel_panics
            $('#kernel_panics-cnt').text(data.length);
            var skipThese = ['id','serial_number','type','crash_file','full_text'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        // Do nothing for empty values to blank them
                        if (d[prop] == '' || d[prop] == null){

                        // Format date
                        } else if(prop == "date"){
                            var date = new Date(d[prop] * 1000);
                            rows = rows + '<tr><th>'+i18n.t('kernel_panics.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';
                            
                        // Format extensions_backtrace
                        } else if(prop == "extensions_backtrace"){
                            rows = rows + '<tr><th style="width:150px;">'+i18n.t('kernel_panics.'+prop)+'</th><td>'+d[prop].replace(/\n/g, '<br>').replace(/dependency:/g, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dependency:')+'</td></tr>';

                        // Else, build out rows from panics
                        } else {
                            rows = rows + '<tr><th style="width:150px;">'+i18n.t('kernel_panics.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                    }
                }

                if (d.type == "GPU Reset"){
                    $('#kernel_panics-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-desktop'))
                            .append('  '+d.crash_file+'&nbsp;&nbsp;')
                               .append($('<button>')
                                .addClass('btn btn-info btn-xs')
                                .text(i18n.t('kernel_panics.view_panic'))
                                .click(function(){
                                    // Create large modal
                                    $('#myModal .modal-dialog').addClass('modal-lg');
                                    $('#myModal .modal-title')
                                        .empty()
                                        .append(d.crash_file)
                                    $('#myModal .modal-body')
                                        .empty()
                                        .append(d.full_text.replace(/\n/g, '<br>'));

                                    $('#myModal button.ok').text(i18n.t("dialog.close"));

                                    // Set ok button
                                    $('#myModal button.ok')
                                        .off()
                                        .click(function(){$('#myModal').modal('hide')});

                                    $('#myModal').modal('show');
                                })))
                        .append($('<div style="max-width:600px;">')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed')
                                .append($('<tbody>')
                                    .append(rows))))
                    
                } else if (d.type == "iBridge Crash"){
                    $('#kernel_panics-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-link'))
                            .append('  '+d.crash_file+'&nbsp;&nbsp;')
                               .append($('<button>')
                                .addClass('btn btn-info btn-xs')
                                .text(i18n.t('kernel_panics.view_panic'))
                                .click(function(){
                                    // Create large modal
                                    $('#myModal .modal-dialog').addClass('modal-lg');
                                    $('#myModal .modal-title')
                                        .empty()
                                        .append(d.crash_file)
                                    $('#myModal .modal-body')
                                        .empty()
                                        .append(d.full_text.replace(/\n/g, '<br>'));

                                    $('#myModal button.ok').text(i18n.t("dialog.close"));

                                    // Set ok button
                                    $('#myModal button.ok')
                                        .off()
                                        .click(function(){$('#myModal').modal('hide')});

                                    $('#myModal').modal('show');
                                })))
                        .append($('<div style="max-width:650px;">')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed')
                                .append($('<tbody>')
                                    .append(rows))))
                    
                } else {
                    $('#kernel_panics-tab')
                        .append($('<h4>')
                            .append($('<i>')
                                .addClass('fa fa-bomb'))
                            .append('  '+d.crash_file+'&nbsp;&nbsp;')
                               .append($('<button>')
                                .addClass('btn btn-info btn-xs')
                                .text(i18n.t('kernel_panics.view_panic'))
                                .click(function(){
                                    // Create large modal
                                    $('#myModal .modal-dialog').addClass('modal-lg');
                                    $('#myModal .modal-title')
                                        .empty()
                                        .append(d.crash_file)
                                    $('#myModal .modal-body')
                                        .empty()
                                        .append(d.full_text.replace(/\n/g, '<br>'));

                                    $('#myModal button.ok').text(i18n.t("dialog.close"));

                                    // Set ok button
                                    $('#myModal button.ok')
                                        .off()
                                        .click(function(){$('#myModal').modal('hide')});

                                    $('#myModal').modal('show');
                                })))
                        .append($('<div style="max-width:1150px;">')
                            .append($('<table>')
                                .addClass('table table-striped table-condensed')
                                .append($('<tbody>')
                                    .append(rows))))
                }
            })
        }
	});
});
</script>
