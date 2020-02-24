<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ssd_service_program-widget">
        <div class="panel-heading" data-container="body" title="">
            <h3 class="panel-title"><i class="fa fa-hdd-o"></i>
                <span data-i18n="ssd_service_program.ssd_service_program"></span>
                <list-link data-url="/show/listing/ssd_service_program/ssd_service_program"></list-link>
            </h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ssd_service_program/get_ssd_service_program_stats', function( data ) {
        
    	if(data.error){
    		//alert(data.error);
    		return;
    	}
		
		var panel = $('#ssd_service_program-widget div.panel-body'),
			baseUrl = appUrl + '/show/listing/ssd_service_program/ssd_service_program#';
		panel.empty();

		// Set statuses
        if(data.needs_service){
			panel.append(' <a href="'+baseUrl+'True" class="btn btn-danger"><span class="bigger-150">'+data.needs_service+'</span><br>'+i18n.t('ssd_service_program.needs_service')+'</a>');
		}
    });
});
</script>
