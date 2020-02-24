<?php $this->view('partials/head'); ?>

<?php
// Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Kernel_panics_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="kernel_panics.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="kernel_panics.crash_file" data-colname='kernel_panics.crash_file'></th>
		        <th data-i18n="kernel_panics.process_name" data-colname='kernel_panics.process_name'></th>
		        <th data-i18n="kernel_panics.date" data-colname='kernel_panics.date'></th>
		        <th data-i18n="kernel_panics.macos_version" data-colname='kernel_panics.macos_version'></th>
		        <th data-i18n="kernel_panics.model_id" data-colname='kernel_panics.model_id'></th>
		        <th data-i18n="kernel_panics.type" data-colname='kernel_panics.type'></th>
		        <th data-i18n="kernel_panics.full_text" data-colname='kernel_panics.id'></th>
		        <th data-i18n="kernel_panics.anonymous_uuid" data-colname='kernel_panics.anonymous_uuid'></th>
		      </tr>
		    </thead>
		    <tbody>
		        <tr>
                    <td data-i18n="listing.loading" colspan="10" class="dataTables_empty"></td>
		        </tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
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
            columnDefs = [{ visible: false, targets: hideThese }]; // Column Definitions

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
                data: function(d){
                    d.mrColNotEmpty = "kernel_panics.anonymous_uuid";
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_kernel_panics-tab');
	        	$('td:eq(0)', nRow).html(link);
                                
                // Format Date Timestamp
                var checkin = $('td:eq(4)', nRow).html();
                var date = new Date(checkin * 1000);
                $('td:eq(4)', nRow).html('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>');
                                
	        	// View button
	        	var colvar=$('td:eq(8)', nRow).html();
	        	var panic_file=$('td:eq(2)', nRow).html();
	        	var uuid=$('td:eq(9)', nRow).html();
	        	$('td:eq(8)', nRow).html('<button onclick="viewPanic(\''+uuid+'\',\''+panic_file+'\')" class="btn btn-info btn-xs" style="min-width: 100px;" >'+i18n.t('kernel_panics.view')+'</button>')
            }
	    } );
        
	    // Use hash as searchquery
	    if(window.location.hash.substring(1))
	    {
			oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }
	});
    
    // Get panic log via API and display in modal
    function viewPanic(uuid, panic_file){
        $.get(appUrl + '/module/kernel_panics/get_panic_log/' + uuid, function(data, status){

            // Create large modal
            $('#myModal .modal-dialog').addClass('modal-lg');
            $('#myModal .modal-title')
                .empty()
                .append(panic_file)
            $('#myModal .modal-body')
                .empty()
                .append(data.replace(/\n/g, '<br>'));

            $('#myModal button.ok').text(i18n.t("dialog.close"));

            // Set ok button
            $('#myModal button.ok')
                .off()
                .click(function(){$('#myModal').modal('hide')});

            $('#myModal').modal('show');
        });
    }
</script>

<?php $this->view('partials/foot')?>
