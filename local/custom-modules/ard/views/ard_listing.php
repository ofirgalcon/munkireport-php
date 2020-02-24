<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ard_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="ard.listing.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="ard.listing.text" data-i18n-options='{"number":1}' data-colname='ard.text1'></th>
		        <th data-i18n="ard.listing.text" data-i18n-options='{"number":2}' data-colname='ard.text2'></th>
		        <th data-i18n="ard.listing.text" data-i18n-options='{"number":3}' data-colname='ard.text3'></th>
		        <th data-i18n="ard.listing.text" data-i18n-options='{"number":4}' data-colname='ard.text4'></th>
		        <th data-i18n="ard.admin_machines" data-colname='ard.admin_machines'></th>
		        <th data-i18n="ard.vnc_enabled" data-colname='ard.vnc_enabled'></th>
		        <th data-i18n="ard.directory_login" data-colname='ard.directory_login'></th>
		        <th data-i18n="ard.allow_all_local_users" data-colname='ard.allow_all_local_users'></th>
		        <th data-i18n="ard.screensharing_request_permission" data-colname='ard.screensharing_request_permission'></th>
		        <th data-i18n="ard.load_menu_extra" data-colname='ard.load_menu_extra'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="13" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});

	$(document).on('appReady', function(e, lang) {
		// Get column names from data attribute
		var columnDefs = [],
            col = 0; // Column counter
		$('.table th').map(function(){
              columnDefs.push({name: $(this).data('colname'), targets: col});
              col++;
		});
	    oTable = $('.table').dataTable( {
	        columnDefs: columnDefs,
	        ajax: {
                url: appUrl + '/datatables/data',
                type: "POST"
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
	        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn, '#tab_ard');
	        	$('td:eq(0)', nRow).html(link);
                
                var colvar=$('td:eq(8)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(8)', nRow).html(colvar)
                
                var colvar=$('td:eq(9)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(9)', nRow).html(colvar)
                
                var colvar=$('td:eq(10)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(10)', nRow).html(colvar)
                
                var colvar=$('td:eq(11)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(11)', nRow).html(colvar)
                
                var colvar=$('td:eq(12)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(12)', nRow).html(colvar)
	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
