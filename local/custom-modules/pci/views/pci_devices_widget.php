<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="pci_devices-widget">
		<div class="panel-heading" data-container="body" >
			<h3 class="panel-title"><i class="fa fa-credit-card-alt"></i>
			    <span data-i18n="pci.clienttab"></span>
			    <list-link data-url="/show/listing/pci/pci"></list-link>
			</h3>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
	
	var box = $('#pci_devices-widget div.scroll-box');
	
	$.getJSON( appUrl + '/module/pci/get_pci_devices', function( data ) {
		
		box.empty();
		if(data.length) {
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
                box.append('<a href="'+appUrl+'/show/listing/pci/pci/#'+d.name+'" class="list-group-item">'+d.name+badge+'</a>')
			});
		} else {
			box.append('<span class="list-group-item">'+i18n.t('pci.no_pci')+'</span>');
		}
	});
});	
</script>
