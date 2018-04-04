<?php
	$admin_theme = $this->config->item('admin_theme');
?>

<!DOCTYPE html>
<html lang="en-us">	
	<head>
		<?php $this->load->view($admin_theme.'/head'); ?>
	</head>

	<body class="smart-style-0">

		<!-- #HEADER -->
		<?php $this->load->view($admin_theme.'/header'); ?>
		<!-- END HEADER -->

		<!-- #NAVIGATION -->
		<?php $this->load->view($admin_theme.'/sidebar'); ?>
		<!-- END NAVIGATION -->
		
		<!-- #MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<?php $this->load->view($admin_theme.'/ribbon'); ?>
			<!-- END RIBBON -->

			<!-- #MAIN CONTENT -->
			<div id="content">
				<?php $this->load->view($content); ?>
			</div>
			<!-- END #MAIN CONTENT -->

		</div>
		<!-- END #MAIN PANEL -->

		<!-- BEGIN FOOTER -->
		<?php $this->load->view($admin_theme.'/footer'); ?>
		<!-- END FOOTER -->

		<!-- BEGIN JAVASCRIPTS -->
		<?php $this->load->view($admin_theme.'/js'); ?>
		<!-- END JAVASCRIPTS --> 

	</body>

</html>