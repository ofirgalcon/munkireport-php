<?php $this->view('partials/head', array('stylesheets' => array('bootstrap-markdown.min.css', 'bootstrap-tagsinput.css'))) ?>

<?php

// Tab list, each item should contain:
//	'view' => path/to/tab
// 'i18n' => i18n identifier matching a localised name
// Optionally:
// 'view_vars' => array with variables to pass to the views
// 'badge' => id of a badge for this tab
$tab_list = [
	'summary' => [
		'view' => 'client/summary_tab',
		'view_vars' => [
			'widget_list' => [],
		],
		'i18n' => 'client.tab.summary',
	],
];

// Include module tabs
$modules = getMrModuleObj()->loadInfo();
$modules->addTabs($tab_list);

// Add custom tabs
$tab_list = array_merge($tab_list, conf('client_tabs', []));

// Add widgets to summary tab
$modules->addWidgets(
	$tab_list['summary']['view_vars']['widget_list'],
	conf('detail_widget_list', [])
);


?>

<script>
	var serialNumber = '<?php echo $serial_number?>';
</script>

<div class="container">
	<div class="row">

		<div class="col-lg-12">

			<div class="input-group">

				<div class="input-group-prepend">
					<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span data-i18n="show" class="hidden-sm hidden-xs"></span>
						<i class="fa fa-list fa-fw"></i>
					</button>
					<div class="dropdown-menu client-tabs" role="tablist">
							<?php foreach($tab_list as $name => $data):?>

								<a class="dropdown-item" href="#<?php echo $name?>" data-toggle="tab"><span data-i18n="<?php echo $data['i18n']?>"></span>
								<?php if(isset($data['badge'])):?>
									<span id="<?php echo $data['badge']?>" class="badge badge-secondary">0</span>
								<?php endif?>
								</a>

							<?php endforeach?>

						</div>
				</div><!-- /btn-group -->

				<input type="text" class="form-control mr-computer_name_input" readonly>

				<div class="input-group-append">
					<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span data-i18n="remote_control" class="hidden-sm hidden-xs"></span>
						<i class="fa fa-binoculars fa-fw"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-right" role="tablist" id="client_links">
					</div>
				</div><!-- /btn-group -->

			</div>

		</div><!-- /col -->

	</div><!-- /row -->
	<div class="row">
		<div class="col-lg-12">

			<div class="tab-content">

			<?php foreach($tab_list as $name => $data):?>

				<div class="tab-pane <?php if(isset($data['class'])):?>active<?php endif?>" id='<?php echo $name?>'>
					<?php $this->view($data['view'], isset($data['view_vars'])?$data['view_vars']:array(), isset($data['view_path'])?$data['view_path']:conf('view_path'));?>
				</div>

			<?php endforeach?>

			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-markdown.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/typeahead.bundle.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/marked.min.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.comment.js"></script>

<script>

	// Ugly hack to clear the active class from the dropdown
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		if (e.target) {
			$(e.target).parent().find('a').removeClass('active');
			$(e.target).addClass('active');
		}
	})
  
</script>
<?php $this->view('partials/foot'); ?>
