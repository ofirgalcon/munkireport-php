<div id="audio-tab"></div>
<h2 data-i18n="audio.clienttab"></h2>

<div id="audio-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/audio/get_data/' + serialNumber, function(data){
        
        // Check if we have data
        if( data == "" || ! data){
            $('#audio-msg').text(i18n.t('audio.no_audio'));
            
            // Set the tab badge to blank
            $('#audio-cnt').html("");
            
        } else {

            // Hide
            $('#audio-msg').text('');
            $('#audio-count-view').removeClass('hide');
        
            // Set count of audio devices
            $('#audio-cnt').text(data.length);
            var skipThese = ['id','name'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        if (d[prop] == null || d[prop] == ""){
                            // Do nothing for the nulls to blank them
                                                    
                        // } else if((prop == 'driver_installed' || prop == 'msi') && d[prop] == 1){
                        //     rows = rows + '<tr><th>'+i18n.t('audio.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        // } else if((prop == 'driver_installed' || prop == 'msi') && d[prop] == 0){
                        //     rows = rows + '<tr><th>'+i18n.t('audio.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                            
                        } else {
                            rows = rows + '<tr><th>'+i18n.t('audio.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                    }
                }
                $('#audio-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-credit-card-alt'))
                        .append(' '+d.name))
                    .append($('<div style="max-width:550px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            })
        }
    });
});
</script>
