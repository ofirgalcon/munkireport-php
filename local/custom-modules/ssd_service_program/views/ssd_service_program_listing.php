<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ssd_service_program_model;
new department_model;
new Munkireport_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="ssd_service_program.ssd_service_program"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		        <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
                <th data-i18n="username" data-colname='reportdata.long_username'></th>
                <th data-i18n="ssd_service_program.needs_service" data-colname='ssd_service_program.needs_service'></th>
                <th data-i18n="ssd_service_program.ssd_model" data-colname='ssd_service_program.ssd_model'></th>
                <th data-i18n="ssd_service_program.ssd_revision" data-colname='ssd_service_program.ssd_revision'></th>
                <th data-i18n="listing.machine_model" data-colname='machine.machine_model'>Gestalt</th>
                <th data-i18n="listing.machine_desc" data-colname='machine.machine_desc'>Human Name</th>
                <th data-i18n="listing.department.department" data-colname='department.department'>Department</th>
                <th data-colname='reportdata.timestamp'>Check-in</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="14" class="dataTables_empty"></td>
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

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            runtypes = [], // Array for runtype column
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function( d ){
                  d.mrColNotEmpty = "ssd_service_program.id"
                }
			},
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn);
	        	$('td:eq(0)', nRow).html(link);

            var needs_service = $('td:eq(3)', nRow).html();
            $('td:eq(3)', nRow).html(function(){
                if( needs_service == "True" ){
                    return '<span class="label label-danger">'+i18n.t('ssd_service_program.true')+'</span>';
                }
                return '<span class="label label-success">'+i18n.t('ssd_service_program.false')+'</span>';
            });

            // Format date
            var checkin = parseInt($('td:eq(9)', nRow).html());
            var date = new Date(checkin * 1000);
            $('td:eq(9)', nRow).html(moment(date).fromNow());

	        }
    });
  });
</script>

<?php $this->view('partials/foot'); ?>
