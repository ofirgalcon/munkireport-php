<?php $this->view('partials/head', array('scripts' => array('clients/client_list.js'))); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Warranty_model;
new Disk_report_model;
new Reportdata_model;
new Munkireport_model;
new department_model;
?>

<div class="container">

  <div class="row">

	<div class="col-lg-12">

	  <h3>Replacement Plan <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.username" data-colname='reportdata.console_user'>Username</th>
		  	<th data-i18n="listing.computername" data-colname='machine.computer_name'>Machine Name</th>
			<th data-i18n="listing.machine_model" data-colname='machine.machine_model'>Gestalt</th>
			<th data-i18n="listing.machine_desc" data-colname='machine.machine_desc'>Human Name</th>
			<th data-colname='machine.os_version'>OS</th>
			<th data-i18n="listing.physical_memory" data-colname='machine.physical_memory'>RAM</th>
			<th data-i18n="listing.department.department" data-colname='department.department'>Department</th>
			<th data-colname='reportdata.timestamp'>Check-in</th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'>Serial</th>
			<th data-i18n="disk_report.media_type" data-colname='diskreport.media_type'>Media Type</th>
		    <th data-i18n="disk_report.mountpoint" data-colname='diskreport.MountPoint'>Mount Point</th>
			<th data-i18n="warranty.est_manufacture_date" data-colname='warranty.purchase_date'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="10" class="dataTables_empty"></td>
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
		var columnDefs = [], //Column Definitions
            col = 0; // Column counter
		$('.table th').map(function(){
            columnDefs.push({name: $(this).data('colname'), targets: col});
            col++;
		});
		var oTable = $('.table').dataTable( {
            ajax: {
                url: "<?php echo url('datatables/data'); ?>",
                type: "POST",
                data: function( d ){
                    // Look for 'osversion' statement
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
                    }
                    
                    // Only search on bootvolume
                    d.where = [
                        {
                            table: 'diskreport',
                            column: 'MountPoint',
                            value: '/'
                        }
                    ];
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            columnDefs: columnDefs,
			createdRow: function( nRow, aData, iDataIndex ) {
				// Update name in first column to link
				var name=$('td:eq(1)', nRow).html();
				if(name == ''){name = "No Name"};
				var sn=$('td:eq(8)', nRow).html();
				var link = mr.getClientDetailLink(name, sn, '<?php echo url(); ?>/');
				$('td:eq(1)', nRow).html(link);

				// Format OS Version
				var osvers = mr.integerToVersion($('td:eq(4)', nRow).html());
				$('td:eq(4)', nRow).html(osvers);

				// Format RAM
	        	var mem=$('td:eq(5)', nRow).html();
	        	$('td:eq(5)', nRow).html(parseInt(mem) + ' GB');

				// Format date
				var checkin = parseInt($('td:eq(7)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(7)', nRow).html(moment(date).fromNow());


			}
		});
	});
</script>

<?php $this->view('partials/foot'); ?>


