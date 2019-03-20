<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title><?php echo $this->config_model->getConfig('Software') ?></title>
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="<?php echo base_url('assets/css/style.min.css') ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url('assets/css/buttons.css') ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url('assets/css/main.min.css') ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url('assets/plugins/chosen/chosen.min.green.css') ?>" rel="stylesheet" type="text/css"/>
		<link rel="shortcut icon" href="<?php echo base_url('assets/img/main/favicon.png') ?>" />
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		
		
		<!--[if lt IE 9]>
		<![endif]-->
		
		<script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/framework.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/JsBarcode.all.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/plugins/highcharts/js/highcharts.js'); ?>"></script>
		<script src="<?php echo base_url('assets/plugins/highcharts/js/modules/data.js') ?>"></script>
		<script src="<?php echo base_url('assets/plugins/highcharts/js/modules/drilldown.js') ?>"></script>
		<script src="<?php echo base_url('assets/plugins/chosen/chosen.jquery.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/plugins/chosen/chosen.proto.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>" type="text/javascript"></script>
		
		<?php 
		if (!empty($css)) {
		    foreach ($css as $style) {
		    ?>
			<script src="<?php echo base_url('assets/css/'.$style) ?>" type="text/javascript"></script>
		<?php 
			}
		}
			
		if (!empty($plugin_css)) {
			foreach ($plugin_css as $style) {
		?>
		<script src="<?php echo base_url('assets/plugins/'.$style) ?>" type="text/javascript"></script>
		<?php 
			}
		}
		?>
		<script>
		function display_session_items(item_name, display_area) 
		{  	
			var new_controller = '<?php echo $new_controller_page?>';
			
			if(new_controller) {
				$.post('<?php echo $controller_page ?>/display_session_items/'+display_area, { sessionSet: item_name },
				  function(data){
					$('#'+display_area).html(data);
				  }, "text");
			} else {
				$.post('<?php echo $controller_page ?>/display_session_items/'+display_area, { sessionSet: item_name },
				  function(data){
					$('#'+display_area).html(data);
				  }, "text");
			}
		}
		</script>
		
		<style type="text/css">
			/*    .datepicker, .table-condensed {
			width: 100%;
			height: 100%;
			}
			.datepicker, .table-condensed, tbody {
			width: 100%;
			height: 100%;
			}
			.datepicker, .table-condensed, thead {
			height: 20%;
			}*/
			.main_loader {
			position: fixed;
			left: 50%; 
			top: 50%; 
			z-index: 9999; 
			display: none;
			}
			.bg_disabled {
			z-index: -1;
			}
			.custom-select-a {color: #495057;border: 1px solid #dedede;background: #fbfbfb;border-radius: 4px;box-shadow: none; box-shadow: 0 0 !important;outline: 0 !important;padding: 5px 8px !important;height: 32px;}
		</style>
	</head>
	
	
	<body id="page" class="page push-right">
		<div class="body-inner">
			
			<!-- ---------------------- Header Main Container -------------------- -->
			<header class="header navbar-expand">
				<div class="header-wrapper">
				
					<!-- ---------------------- Header Logo -------------------- -->
					<div class="header-brand">
						<div class="d-flex">
							<div class="brand-logo">
								<a href="#" class="logo">
								<img alt="" src="<?php echo base_url('assets/img/main/logo.png') ?>" style="width:160px;height:40px"/>
								</a>
							</div>
							<div class="brand-tools">
								<a id="btn-p-left" href="javascript:;" class="brand-icon active">
								<span></span>
								</a>
							</div>
						</div>
					</div>
					
					<!-- ---------------------- Header Quick Access -------------------- -->
					<div class="header-nav">
						<div id="header-menu" class="header-menu">
							<div class="collapse navbar-collapse" id="navbarText">
								<ul class="navbar-nav">
									<li class="nav-item dropdown medium">
										<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon left flaticon-stopwatch"></i> <span class="nav-text">Quick Access</span></a>
										<div class="dropdown-menu wrap">
											<ul class="nav">
												<?php if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Create Purchase Order')) { ?>
												<li class="nav-item"><a class="nav-link" href="<?php echo site_url('purchase_order/create') ?>"><i class="icon left la la-shopping-cart"></i><span class="nav-text">Create Purchase Order</span></a></li>
												<?php } ?>


												<?php if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Create Sales Order')) { ?>
												<li class="nav-item"><a class="nav-link" href="<?php echo site_url('sales_order/create') ?>"><i class="icon left la la-money"></i><span class="nav-text">Create Sales Order</span></a></li>
												<?php } ?>


												
												<?php if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Collections')) { ?>
												<li class="nav-item"><a class="nav-link" href="<?php echo site_url('collections/create') ?>"><i class="icon left la la-money"></i><span class="nav-text">Collections</span></a></li>
												<?php } ?>


												<?php if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Make Payments')) { ?>
												<li class="nav-item"><a class="nav-link" href="<?php echo site_url('payments/create') ?>"><i class="icon left la la-shopping-cart"></i><span class="nav-text">Make Payments</span></a></li>
												<?php } ?>
												
											</ul>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
						<!-- ---------------------- Header Notification -------------------- -->
						<div id="header-topbar" class="header-topbar">
							<div class="nav-wrapper navbar-collapse">
								<ul class="navbar-nav icons">
									<li id="notifications" class="nav-item dropdown large header-bg-fill">
										<!-- Add class "has-notification" if notification is not empty  has-notification -->
										<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="badge badge-danger notification-badge">&nbsp;</span><i class="icon flaticon-music-2"></i></a>
										<div class="dropdown-menu wrap dropdown-menu-right">
											<div class="dropdown-header">
												<span class="dropdown-title">0 new</span>
												<span class="dropdown-sub-title">User Notifications</span>
											</div>
											<div class="dropdown-body">
												<div class="scrollable-wrap">
													<ul class="timeline-list">
														
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li class="nav-item dropdown top-user medium header-bg-fill">
										<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="user-pic">
										<?php if (file_exists('assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension)) {?>
										<img src="<?php echo base_url('assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension) ?>" class="img-rounded" alt="">
										<?php } else { ?>
										<span class="user-text">
										<?php
											$lname = $this->session->userdata('current_user')->lastName;
											$fname = $this->session->userdata('current_user')->firstName;
											echo $lname[0].$fname[0];
											?>
										</span>
										<?php } ?>
										</span>
										</a>
										<div class="dropdown-menu wrap dropdown-menu-right">
											<div class="dropdown-header">
												<div class="card-user">
													<div class="card-user-pic"> 
														<?php if (file_exists('assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension)) {?>
														<img src="<?php echo base_url('assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension) ?>" class="img-rounded" alt="">
														<?php } else { ?>
														<span class="user-text"><?php echo $lname[0].$fname[0]; ?></span>
														<?php } ?>
													</div>
													<div class="card-user-details">
														<span class="card-user-name"><?php echo $this->session->userdata('current_user')->lastName ?></span>
													</div>
												</div>
											</div>
											<div class="dropdown-body">
												<ul class="nav">
													<li class="nav-item"><a class="nav-link" href="<?php echo site_url('user/profile') ?>"><i class="icon left flaticon-profile-1"></i><span class="nav-text">My Account</span></a></li>
													<li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#changePassModal"><i class="icon left flaticon-lock-1"></i><span class="nav-text">Change Password</span></a></li>
												</ul>
											</div>
											<div class="dropdown-footer">
												<a class="btn btn-sm pill btn-outline-primary btn-raised" href="<?php echo site_url('logout') ?>">Logout</a>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</header>
			
			
			<!-- ----------------------------------------- Page Body Main Container --------------------------------------- -->
			<div class="page-body">
				<div id="left-menu" class="aside-left sidebar-nav">
					<div class="nav nav-wrapper">
						<ul class="sidebar-menu navbar-nav">
						
							<!-- -------------------------- Page Body Menu - Left ------------------------------- -->
							<?php $preferences = explode(",",$this->session->userdata('current_user')->preferences);?>
							
							<!-- --------------------------------Dashboard ------------------------------ -->
							<?php  if (in_array("Dashboard", $preferences) || $this->session->userdata('current_user')->userID) { ?>
							<li class="nav-item <?php if ($current_main_module['title'] == 'Dashboard') echo "active"; ?>">
								<a href="<?php echo site_url('dashboard') ?>" class="nav-link" aria-expanded="false">
								<i class="icon left la la-desktop"></i>
								<span class="nav-text">Dashboard</span>
								</a>
							</li>
							<?php } ?>
							
							<!-- ----------------------- Transactions -------------------------------- -->
							<?php 
							foreach ($modules as $mod) {
								$preferences = explode(",",$this->session->userdata('current_user')->preferences);
								
								if (in_array($mod['main']['title'], $preferences) || $this->session->userdata('current_user')->isAdmin) {
									if ($mod['main']['title'] == 'Transactions') { 
									?>
										<li class="nav-item item-submenu <?php if (strtoupper($mod['main']['title']) == strtoupper($current_main_module['title'])) echo "active"; ?>">
											<a  href="#" class="nav-link nav-toggle" aria-expanded="true">
											<i class="icon left la <?php echo $mod['main']['icon'] ?>"></i>
											<span class="nav-text"><?php echo $mod['main']['title'] ?></span>
											<i class="icon right ti-angle-right"></i>
											</a>
											<ul class="submenu">
												<?php foreach($mod['main']['menu'] as $m) {
													if (!empty($m['subitem'])) {
													?>
												<li class="nav-item item-submenu">
													<a href="#" class="nav-link nav-toggle">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													<i class="icon right ti-angle-right"></i>
													</a>
													<ul class="submenu" aria-expanded="false">
														<?php foreach($m['subitem'] as $ms) {
															?>
														<li class="nav-item">
															<a href="<?php echo $ms['url'] ?>" class="nav-link">
															<i class="icon left bullet"></i>
															<span class="nav-text"><?php echo $ms['label'] ?></span>
															</a>
														</li>
														<?php 
															}?>
													</ul>
												</li>
												<?php } else {
													?>
												<li class="nav-item <?php if (base_url(uri_string()) == $m['url']) echo "active"; ?>">
													<a href="<?php echo $m['url'] ?>" class="nav-link">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													</a>
												</li>
												<?php 
													}
													}                          
													?>
											</ul>
										</li>
							<?php 
									}
								}
							}
							?>
							
							<!-- ----------------------- Inventory -------------------------------- -->
							<?php 
							foreach ($modules as $mod) {
								$preferences = explode(",",$this->session->userdata('current_user')->preferences);
								
								if (in_array($mod['main']['title'], $preferences) || $this->session->userdata('current_user')->isAdmin) {
									if ($mod['main']['title'] == 'Inventory') { 
									?>
										<li class="nav-item item-submenu <?php if (strtoupper($mod['main']['title']) == strtoupper($current_main_module['title'])) echo "active"; ?>">
											<a  href="#" class="nav-link nav-toggle" aria-expanded="true">
											<i class="icon left la <?php echo $mod['main']['icon'] ?>"></i>
											<span class="nav-text"><?php echo $mod['main']['title'] ?></span>
											<i class="icon right ti-angle-right"></i>
											</a>
											<ul class="submenu">
												<?php foreach($mod['main']['menu'] as $m) {
													if (!empty($m['subitem'])) {
													?>
												<li class="nav-item item-submenu">
													<a href="#" class="nav-link nav-toggle">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													<i class="icon right ti-angle-right"></i>
													</a>
													<ul class="submenu" aria-expanded="false">
														<?php foreach($m['subitem'] as $ms) {
															?>
														<li class="nav-item">
															<a href="<?php echo $ms['url'] ?>" class="nav-link">
															<i class="icon left bullet"></i>
															<span class="nav-text"><?php echo $ms['label'] ?></span>
															</a>
														</li>
														<?php 
															}?>
													</ul>
												</li>
												<?php } else {
													?>
												<li class="nav-item <?php if (base_url(uri_string()) == $m['url']) echo "active"; ?>">
													<a href="<?php echo $m['url'] ?>" class="nav-link">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													</a>
												</li>
												<?php 
													}
													}                          
													?>
											</ul>
										</li>
							<?php 
									}
								}
							}
							?>
							
							<!-- --------------------------------Master File ------------------------------ -->
							<?php 
							foreach ($modules as $mod) {
								$preferences = explode(",",$this->session->userdata('current_user')->preferences);
								
								if (in_array('Master Files', $preferences) || $this->session->userdata('current_user')->isAdmin) {
								
									$with_master_files = false;
									if ($mod['main']['title'] == 'Master Files') { 
									    $with_master_files = true;
									    break;
									} 
								}
								
								if ($with_master_files) {
								  ?>
									<li class="menu-section">
										<span class="menu-section-text">
										Master Files
										</span>
										<i class="icon flaticon-more-v3"></i>
									</li>
							<?php 
								} 
							}
							// ------------------- Master File Sub-Menu -------------------
							foreach ($modules as $mod) {
								$preferences = explode(",",$this->session->userdata('current_user')->preferences);
								if (in_array($mod['main']['title'], $preferences) || $this->session->userdata('current_user')->isAdmin) {
									if ($mod['main']['title'] == 'Master Files') { 
									?>
										<li class="nav-item item-submenu <?php if (strtoupper($mod['main']['title']) == strtoupper($current_main_module['title'])) echo "active"; ?>">
											<a  href="#" class="nav-link nav-toggle" aria-expanded="true">
											<i class="icon left la <?php echo $mod['main']['icon'] ?>"></i>
											<span class="nav-text"><?php echo $mod['main']['title'] ?></span>
											<i class="icon right ti-angle-right"></i>
											</a>
											<ul class="submenu">
												<?php foreach($mod['main']['menu'] as $m) {
													if (!empty($m['subitem'])) {
													?>
												<li class="nav-item item-submenu">
													<a href="#" class="nav-link nav-toggle">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													<i class="icon right ti-angle-right"></i>
													</a>
													<ul class="submenu" aria-expanded="false">
														<?php foreach($m['subitem'] as $ms) {
															?>
														<li class="nav-item">
															<a href="<?php echo $ms['url'] ?>" class="nav-link">
															<i class="icon left bullet"></i>
															<span class="nav-text"><?php echo $ms['label'] ?></span>
															</a>
														</li>
														<?php 
															}?>
													</ul>
												</li>
												<?php } else {
													?>
												<li class="nav-item <?php if (base_url(uri_string()) == $m['url']) echo "active"; ?>">
													<a href="<?php echo $m['url'] ?>" class="nav-link">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													</a>
												</li>
												<?php 
													}
												}                          
												?>
											</ul>
										</li>
								<?php 
									} 
								} 
							}
							?>
							
							
							<!-- ----------------------- Reports -------------------------------- -->
							<?php 
							foreach ($modules as $mod) {
								$preferences = explode(",",$this->session->userdata('current_user')->preferences);
								
								if (in_array($mod['main']['title'], $preferences) || $this->session->userdata('current_user')->isAdmin) {
									if ($mod['main']['title'] == 'Reports') { 
									?>
										<li class="nav-item item-submenu <?php if (strtoupper($mod['main']['title']) == strtoupper($current_main_module['title'])) echo "active"; ?>">
											<a  href="#" class="nav-link nav-toggle" aria-expanded="true">
											<i class="icon left la <?php echo $mod['main']['icon'] ?>"></i>
											<span class="nav-text"><?php echo $mod['main']['title'] ?></span>
											<i class="icon right ti-angle-right"></i>
											</a>
											<ul class="submenu">
												<?php foreach($mod['main']['menu'] as $m) {
													if (!empty($m['subitem'])) {
													?>
												<li class="nav-item item-submenu">
													<a href="#" class="nav-link nav-toggle">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													<i class="icon right ti-angle-right"></i>
													</a>
													<ul class="submenu" aria-expanded="false">
														<?php foreach($m['subitem'] as $ms) {
															?>
														<li class="nav-item">
															<a href="<?php echo $ms['url'] ?>" class="nav-link">
															<i class="icon left bullet"></i>
															<span class="nav-text"><?php echo $ms['label'] ?></span>
															</a>
														</li>
														<?php 
															}?>
													</ul>
												</li>
												<?php } else {
													?>
												<li class="nav-item <?php if (base_url(uri_string()) == $m['url']) echo "active"; ?>">
													<a href="<?php echo $m['url'] ?>" class="nav-link">
													<i class="icon left la <?php echo $m['icon'] ?>"></i>
													<span class="nav-text"><?php echo $m['label'] ?></span>
													</a>
												</li>
												<?php 
													}
													}                          
													?>
											</ul>
										</li>
							<?php 
									}
								}
							}
							?>
							
							<!-- ---------------System Configuration Menu--------------------- -->
							<?php if ( $this->session->userdata('current_user')->isAdmin ) {?>
									<li class="menu-section">
										<span class="menu-section-text">System Configurations</span>
										<i class="icon flaticon-more-v3"></i>
									</li>
									
									<?php 
									foreach ($modules as $mod) {
										if ($mod['main']['title'] == 'Administrator') { 
										?>
											<li class="nav-item item-submenu <?php if (strtoupper($mod['main']['title']) == strtoupper($current_main_module['title'])) echo "active"; ?>">
												<a  href="#" class="nav-link nav-toggle" aria-expanded="true">
													<i class="icon left la <?php echo $mod['main']['icon'] ?>"></i>
													<span class="nav-text"><?php echo $mod['main']['title'] ?></span>
													<i class="icon right ti-angle-right"></i>
												</a>
												<ul class="submenu">
													<?php 
													foreach($mod['main']['menu'] as $m) {
														if (!empty($m['subitem'])) {
														?>
															<li class="nav-item item-submenu">
																<a href="#" class="nav-link nav-toggle">
																<i class="icon left la <?php echo $m['icon'] ?>"></i>
																<span class="nav-text"><?php echo $m['label'] ?></span>
																<i class="icon right ti-angle-right"></i>
																</a>
																<ul class="submenu" aria-expanded="false">
																	<?php foreach($m['subitem'] as $ms) {
																		?>
																	<li class="nav-item">
																		<a href="<?php echo $ms['url'] ?>" class="nav-link">
																		<i class="icon left bullet"></i>
																		<span class="nav-text"><?php echo $ms['label'] ?></span>
																		</a>
																	</li>
																	<?php 
																		}?>
																</ul>
															</li>
													<?php } else { ?>
															<li class="nav-item <?php if (base_url(uri_string()) == $m['url']) echo "active"; ?>">
																<a href="<?php echo $m['url'] ?>" class="nav-link">
																<i class="icon left la <?php echo $m['icon'] ?>"></i>
																<span class="nav-text"><?php echo $m['label'] ?></span>
																</a>
															</li>
													<?php 
														}
													}                          
													?>
												</ul>
											</li>
								<?php 
										}
									}
								} 
								?>
						</ul>
					</div>
				</div>
				
				<!-- ----------------------------------Body Content---------------------------------- -->
				<div class="content-wrapper">
					<!--  BODY STARTS HERE -->
					<div id="main_loader" class="main_loader"><img style="width: 40px; height: 40px;" src="<?php echo base_url('assets/img/main/loading.gif') ?>"></div>

