$.fn.dataTable.defaults.iDisplayLength = 10;

// Add buttons to title bar

$(document).on('appReady', function(e, lang) {

// Add buttons to title bar
$('input.mr-computer_name_input')
	.next()
	.prepend('<a data-toggle="tab" title="Battery" class="btn btn-default tab-btn" href="#battery-tab"><i class="fa fa-battery-three-quarters"></i></a>')
	.prepend('<a data-toggle="tab" title="Storage" class="btn btn-default tab-btn" href="#storage-tab"><i class="fa fa-hdd-o"></i></a>')
	.prepend('<a data-toggle="tab" title="SMART Stats" class="btn btn-default tab-btn" href="#smart_stats-tab"><i class="fa fa-user-md"></i></a>')
	.prepend('<a data-toggle="tab" title="DetectX" class="btn btn-default tab-btn" href="#detectx-tab"><i class="fa fa-shield"></i></a>')
	.prepend('<a data-toggle="tab" title="FileVault" class="btn btn-default tab-btn" href="#filevault-tab"><i class="fa fa-lock"></i></a>')
	.prepend('<a data-toggle="tab" title="Managed Installs" class="btn btn-default tab-btn" href="#munki"><i class="fa fa-download"></i></a>')
	.prepend('<a data-toggle="tab" title="TeamViewer" class="btn btn-default tab-btn" href="#teamviewer-tab"><i class="fa fa-tv"></i></a>')
	.prepend('<a data-toggle="tab" title="WiFi" class="btn btn-default tab-btn" href="#wifi-tab"><i class="fa fa-wifi"></i></a>')
	.prepend('<a data-toggle="tab" title="Network" class="btn btn-default tab-btn" href="#network-tab"><i class="fa fa-indent fa-rotate-270"></i></a>')
	.prepend('<a data-toggle="tab" title="Summary" class="btn btn-default tab-btn" href="#summary"><i class="fa fa-info-circle"></i></a>');
})

// colour graphs

mr.graph.barColor = d3.scale.category10().range();
