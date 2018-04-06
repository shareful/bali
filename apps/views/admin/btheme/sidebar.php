<?php
	$theme = $this->config->item('theme');
?>

<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
		<span>
			<a href="user/profile_view" id="show-shortcut" data-action="toggleShortcut">
				<!-- <img src="assets/<?php echo $theme; ?>/img/avatars/sunny.png" alt="me" class="online" />  -->
				<span><?php echo $this->session->userdata('user_name'); ?></span>
			</a> 
		</span>
	</div>

	<nav>
		<ul>
			<li class="">
				<a href="dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-dashboard"></i> <span class="menu-item-parent">Dashboard</span></a>
			</li>
			<li class="">
				<a href="stock" title="Stock"><i class="fa fa-lg fa-fw fa-cube"></i> <span class="menu-item-parent">Stock</span></a>
			</li>
			<li class="">
				<a href="#" title="Security Payment Given"><i class="fa fa-lg fa-fw fa-arrow-up"></i> <span class="menu-item-parent">Security Pmt Given</span></a>
				<ul>
					<li class="">
						<a href="securitygiven/save"><i class="fa fa-lg fa-fw fa-edit"></i> Make a Security Pmt</a>
					</li>
					<li class="">
						<a href="securitygiven"><i class="fa fa-lg fa-fw fa-list"></i> List of Security Pmt Given</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Security Payment Received"><i class="fa fa-lg fa-fw fa-arrow-down"></i> <span class="menu-item-parent">Security Pmt Received</span></a>
				<ul>
					<li class="">
						<a href="securityreceived/save"><i class="fa fa-lg fa-fw fa-edit"></i> Make a Security Pmt</a>
					</li>
					<li class="">
						<a href="securityreceived"><i class="fa fa-lg fa-fw fa-list"></i> List of Security Pmt Received</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Advance Given"><i class="fa fa-lg fa-fw fa-arrow-circle-up"></i> <span class="menu-item-parent">Advance Given</span></a>
				<ul>
					<li class="">
						<a href="advancegiven/save"><i class="fa fa-lg fa-fw fa-edit"></i> Make an Advance</a>
					</li>
					<li class="">
						<a href="advancegiven"><i class="fa fa-lg fa-fw fa-list"></i> List of Advance Given</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Advance Received"><i class="fa fa-lg fa-fw fa-arrow-circle-down"></i> <span class="menu-item-parent">Advance Received</span></a>
				<ul>
					<li class="">
						<a href="advancereceived/save"><i class="fa fa-lg fa-fw fa-edit"></i> Receive New Advance</a>
					</li>
					<li class="">
						<a href="advancereceived"><i class="fa fa-lg fa-fw fa-list"></i> List of Advance Received</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Expenses"><i class="fa fa-lg fa-fw fa-money"></i> <span class="menu-item-parent">Expenses</span></a>
				<ul>
					<li class="">
						<a href="expense/save"><i class="fa fa-lg fa-fw fa-edit"></i> New Expense</a>
					</li>
					<li class="">
						<a href="expense"><i class="fa fa-lg fa-fw fa-list"></i> Expense List</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Projects"><i class="fa fa-lg fa-fw fa-road"></i> <span class="menu-item-parent">Projects</span></a>
				<ul>
					<?php 
					if (in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
					?>
					<li class="">
						<a href="project/save"><i class="fa fa-lg fa-fw fa-edit"></i> Add Project</a>
					</li>
					<?php 
					}
					?>
					<li class="">
						<a href="project"><i class="fa fa-lg fa-fw fa-list"></i> Project List</a>
					</li>
				</ul>
			</li>
			<li class="">
				<a href="#" title="Items"><i class="fa fa-lg fa-fw fa-cubes"></i> <span class="menu-item-parent">Items</span></a>
				<ul>
					<li class="">
						<a href="item/save"><i class="fa fa-lg fa-fw fa-edit"></i> Add Item</a>
					</li>
					<li class="">
						<a href="item"><i class="fa fa-lg fa-fw fa-list"></i> Item List</a>
					</li>
				</ul>
			</li> 
			<li class="">
				<a href="#" title="Customers"><i class="fa fa-lg fa-fw fa-group"></i> <span class="menu-item-parent">Customers</span></a>
				<ul>
					<li class="">
						<a href="customer/save"><i class="fa fa-lg fa-fw fa-user-plus"></i> Add Customer</a>
					</li>
					<li class="">
						<a href="customer"><i class="fa fa-lg fa-fw fa-list"></i> Customer List</a>
					</li>
				</ul>
			</li> 
			<li class="">
				<a href="#" title="Suppliers"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">Suppliers</span></a>
				<ul>
					<li class="">
						<a href="supplier/save"><i class="fa fa-lg fa-fw fa-user-plus"></i> Add Supplier</a>
					</li>
					<li class="">
						<a href="supplier"><i class="fa fa-lg fa-fw fa-list"></i> Supplier List</a>
					</li>
				</ul>
			</li> 
			<?php 
			if (in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			?>
			<li class="">
				<a href="#" title="Users"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">Users</span></a>
				<ul>
					<li class="">
						<a href="user/save"><i class="fa fa-lg fa-fw fa-user-plus"></i> Add User</a>
					</li>
					<li class="">
						<a href="user"><i class="fa fa-lg fa-fw fa-list"></i> User List</a>
					</li>
				</ul>
			</li>
			<?php
			}
			?> 	
		</ul>
	</nav>
	<span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>