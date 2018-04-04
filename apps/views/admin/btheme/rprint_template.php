<?php
    $theme = $this->config->item('theme');
    $company_name = $this->config->item('company_name');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<base href="<?php echo base_url(); ?>" />
<meta charset="utf-8" />
<title><?php echo isset($title) ? $title : ''; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="Shareful Islam<km.shareful@gmail.com>" />

<link href="assets/invoice/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/invoice/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="assets/invoice/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />

<link href="assets/invoice/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="assets/invoice/css/style.css" rel="stylesheet" />
<link href="assets/invoice/css/style-default.css" rel="stylesheet" id="style_color" />
<link href="assets/invoice/css/print.css" rel="stylesheet">

</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body>
    <!-- BEGIN CONTAINER -->
    <div id="container" class="row-fluid">
        <!-- BEGIN PAGE -->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN BLANK PAGE PORTLET-->
                <div class="widget grey">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="pull-right">
                                    <img src="<?php echo $this->session->userdata('company_logo'); ?>" width="115" class="img">
                                </div>
                                <h3 class="left"><?php echo $this->session->userdata('company_name'); ?></h3>
                                <hr>
                            </div>
                        </div>
                        <div class="space20"></div>

                        <?php $this->load->view($content); ?>
                        <div class="row-fluid" style="text-align: center;">THANK YOU FOR YOUR BUSINESS!</div>
                        <div class="space20"></div>
                        <div class="row-fluid text-center">
                            <a class="btn btn-inverse btn-large hidden-print" onclick="javascript:window.print();">Print <i class="icon-print icon-big"></i></a>
                        </div>
                    </div>
                </div>
                <!-- END BLANK PAGE PORTLET-->
            </div>
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->

    <div id="footer">
        Print Date: <?php echo date('m/d/Y g:i a', now()); ?><br>
        <?php echo date('Y'); ?> &copy; Powered by <a href="http://bisorgo.com/" target="_blank">Bisorgo Technology</a>
    </div>        

</body>
<!-- END BODY -->
</html>