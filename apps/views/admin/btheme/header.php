<?php
	$theme = $this->config->item('theme');
	$company_name = $this->config->item('company_name');
?>
<header id="header">
	<div id="logo-group">

		<!-- PLACE YOUR LOGO HERE -->
		<span id="logo"> 
			<?php if ($this->session->userdata('company_logo')) { ?>
				<img src="<?php echo $this->session->userdata('company_logo') ?>" alt="<?php echo $this->session->userdata('company_name') ?>"> 
			<?php } else {?>
				<img src="assets/<?php echo $theme; ?>/img/logo.png" alt="<?php echo $this->session->userdata('company_name') ?>"> 
			<?php } ?>
		</span>		
		<!-- END LOGO PLACEHOLDER -->
		
	</div>
	<div class="project-context hidden-xs">
		<h3 class="text-primary txt-color-blue" style="font-size: 18px; margin: 5px 0; display: inline-block;">
			<?php // echo $company_name . '<small>'. $this->session->userdata('company_name').'</small>'; ?>				
			<?php echo $company_name; ?>				
		</h3>
		<?php /*if ($this->session->userdata('branch_name')) { ?>
			<span class="label bg-color-blueLight" style="display: inline-block;"><?php echo $this->session->userdata('branch_name') ?></span>
		<?php }*/ ?>
	</div>
	
	<!-- #TOGGLE LAYOUT BUTTONS -->
	<!-- pulled right: nav area -->
	<div class="pull-right">
		<!-- collapse menu button -->
		<div id="hide-menu" class="btn-header pull-right">
			<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
		</div>
		<!-- end collapse menu -->
		
		<!-- #MOBILE -->
		<!-- Top menu profile link : this shows only when top menu is active -->
		<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
			<li class="">
				<a href="user/profile_view" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
					<?php echo $this->session->userdata('user_name'); ?>
				</a>
				<ul class="dropdown-menu pull-right">
					<li>
						<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
					</li>
					<li class="divider"></li>
					<!-- <li>
						<a href="#ajax/profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
					</li> -->
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="login/logout" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
					</li>
				</ul>
			</li>
		</ul>

		<!-- logout button -->
		<div id="logout" class="btn-header transparent pull-right">
			<span> <a href="login/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Are you sure want to logout?"><i class="fa fa-sign-out"></i></a> </span>
		</div>
		<!-- end logout button -->

		<!-- search mobile button (this is hidden till mobile view port) -->
		<!-- <div id="search-mobile" class="btn-header transparent pull-right">
			<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
		</div> -->
		<!-- end search mobile button -->
		
		<!-- #SEARCH -->
		<!-- input: search field -->
		<!-- <form action="#ajax/search.html" class="header-search pull-right">
			<input id="search-fld" type="text" name="param" placeholder="Find reports and more">
			<button type="submit">
				<i class="fa fa-search"></i>
			</button>
			<a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
		</form> -->
		<!-- end input: search field -->

		<!-- fullscreen button -->
		<div id="fullscreen" class="btn-header transparent pull-right">
			<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
		</div>
		<!-- end fullscreen button -->


	</div>
	<!-- end pulled right: nav area -->

</header>