<?php $modules = getMrModuleObj()->loadInfo(); ?>
<?php $dashboard = getDashboard()->loadAll();?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	<a class="navbar-brand" href="<?php echo url(''); ?>"><?php echo conf('sitename'); ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">

	<?php $page = $GLOBALS[ 'engine' ]->get_uri_string(); ?>
		<?php $url = 'show/dashboard/'; ?>
		<?php if($dashboard->getCount() === 1):?>
		<li <?php echo strpos($page, $url)===0?'class="nav-item active"':''; ?>>
			<a class="nav-link" href="<?php echo url(); ?>">
				<i class="fa fa-th-large"></i>
				<span class="d-none d-lg-inline" data-i18n="nav.main.dashboard"></span>
			</a>
		</li>
		<?php else:?>
			<li class="dropdown<?php echo strpos($page, $url)===0?' active':''; ?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-th-large"></i>
					<span class="d-none d-lg-inline" data-i18n="nav.main.dashboard_plural"></span>
					<b class="caret"></b>
				</a>
				<ul class="dashboard dropdown-menu">

					<?php foreach($dashboard->getDropdownData('show/dashboard', $page) as $item): ?>

						<li class="<?=$item->class?>">
							<a href="<?=$item->url?>">
								<span class="pull-right"><?=strtoupper($item->hotkey)?></span>
								<span class="dropdown-link-text "><?=$item->display_name?></span>
							</a>
						</li>

					<?php endforeach; ?>

				</ul>

			</li>
		<?php endif?>
		<?php $url = 'show/report/'; ?>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle<?php echo strpos($page, $url)===0?' active':''; ?>" data-toggle="dropdown">
				<i class="fa fa-bar-chart-o"></i>
				<span class="d-none d-lg-inline" data-i18n="nav.main.reports"></span>
				<b class="caret"></b>
			</a>
			<div class="report dropdown-menu">

				<?php foreach($modules->getDropdownData('reports', 'show/report', $page) as $item): ?>

					<a class="dropdown-item <?=$item->class?>" href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>

				<?php endforeach; ?>

			</div>

		</li>

		<?php $url = 'show/listing/'; ?>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle<?php echo strpos($page, $url)===0?' active':''; ?>" data-toggle="dropdown">
				<i class="fa fa-list-alt"></i>
				<span data-i18n="nav.main.listings"></span>
				<b class="caret"></b>
			</a>
			<div class="listing dropdown-menu">

				<?php foreach($modules->getDropdownData('listings', 'show/listing', $page) as $item): ?>

					<a class="dropdown-item <?=$item->class?>" href="<?=$item->url?>" data-i18n="<?=$item->i18n?>"></a>

				<?php endforeach; ?>

			</div>

		</li>

		<?php if($_SESSION['role'] == 'admin'):?>
		<?php $url = 'admin/show/'; ?>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle<?php echo strpos($page, $url)===0?' active':''; ?>" data-toggle="dropdown">
				<i class="fa fa-list-alt"></i>
				<span data-i18n="nav.main.admin"></span>
				<b class="caret"></b>
			</a>
			<div class="admin dropdown-menu">

				<?php foreach(scandir(conf('view_path').'admin') as $list_url): ?>

					<?php if( strpos($list_url, 'php')): ?>
					<?php $page_url = $url.strtok($list_url, '.'); ?>

						<a class="dropdown-item <?php echo strpos($page, $url)===0?' active':''; ?>" href="<?php echo url($url.strtok($list_url, '.')); ?>" data-i18n="nav.admin.<?php echo $name = strtok($list_url, '.'); ?>"></a>

					<?php endif; ?>

				<?php endforeach; ?>

			</div>

		</li>
		<?php endif?>

		<li>
			<a class="nav-link" href="#" id="filter-popup" class="filter-popup">
				<i class="fa fa-filter"></i>
			</a>
		</li>
    </ul>
	<ul class="navbar-nav ml-auto">
		<li class="dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-wrench"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right theme">

				<?php foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme): ?>

					<?php if( $theme != 'fonts' && strpos($theme, '.') === false):?>

					<a class="dropdown-item" data-switch="<?php echo $theme; ?>" href="#"><?php echo $theme; ?></a>

					<?php endif; ?>

				<?php endforeach; ?>

			</div>
		</li>
		<li class="dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-globe"></i>
			</a>
			<div class="dropdown-menu locale">

					<?php foreach(scandir(PUBLIC_ROOT.'assets/locales') AS $list_url): ?>

						<?php if( strpos($list_url, 'json')):?>

						<?php $lang = strtok($list_url, '.'); ?>

						<a class="dropdown-item" href="<?php echo url($page, false, ['setLng' => $lang]); ?>" data-i18n="nav.lang.<?php echo $lang; ?>"><?php echo $lang; ?></a>

						<?php endif; ?>

					<?php endforeach; ?>

			</div>
		</li>

		<?php if( ! array_key_exists('auth_noauth', conf('auth'))): // Hide logout button if auth_noauth?>

		<li class="dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-user"></i> <?php echo $_SESSION['user']; ?>
				<b class="caret"></b>
			</a>
			<div class="dropdown-menu">
					<a class="dropdown-item"  href="<?php echo url('auth/logout'); ?>">
						<i class="fa fa-power-off"></i>
						<span data-i18n="nav.user.logout"></span>
					</a>
			</div>
		</li>

		<?php endif; ?>

		<?php if(conf('show_help')):?>
				
            <li>
                <a class="nav-link" href="<?php echo conf('help_url');?>" target="_blank">
                    <i class="fa fa-question"></i>
                </a>
            </li>
				
		<?php endif; ?>
	</ul>
  </div>
</nav>