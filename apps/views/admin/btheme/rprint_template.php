<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo base_url(); ?>" />
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Tapan Kumer Das : InnovativeBD">
    <link rel="shortcut icon" href="assets/backend/img/favicon.ico" type="image/x-icon" />

    <!-- styles -->
    <link href="assets/backend/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/backend/css/stilearn.css" rel="stylesheet" />
    <style>
        #footer {
			width: 100%;
            color: #757575;
            font-size: 12px;
			line-height: 15px;
			border-top: 1px solid #eee;
			padding: 5px 0px 10px 0px;
			margin-top: 10px;
        }

        .container-fluid{
            padding-left: 0;
            padding-right: 0;
        }
		
		.content {
			border: 0px solid #fff;
			background-color: #fff;
		}

        .content > .content-body {
            padding: 5px 5px 0px 5px;
        }
        
        @media print{
            p.muted{
                font-weight: bold;
            }
            small.small{
              font-weight: normal;
            }
            #labtest-order-invoice .control-group, legend{
                margin-bottom: 0;
            }    
            .form-horizontal .control-label{
                text-align: left;
            }
            .print-wrap{
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- section content -->
    <section class="section">
        <div class="container">
            <!-- span content -->
            <div class="span12">
                <!-- content -->
                <div class="content">
                    <!-- content-body -->
                    <div class="content-body">
                        <!-- invoice -->
                        <div id="invoice-container" class="invoice-container">
                            <div class="page-header" style="padding-bottom: 0px;overflow: hidden;">
                                <div class="pull-right">
                                    <div class="row-fluid print-wrap">
										<a class="btn btn-large hidden-print" onclick="javascript:window.print();">Print <i class="icon-print icon-big"></i></a>
									</div>
                                </div>
                                <div class="left">
                                    <div class="left" style="margin-right: 10px;width: 115px; float: left; height: 80px;"><img src="<?php echo $this->session->userdata('company_logo'); ?>" width="115" class="img left" /></div>
                                    <h3 class="left" style="margin: 0px;"><?php echo $this->session->userdata('company_name'); ?></h3>
                                    <p style="margin: 0px;">South Banasree, Khilgaon,<br>Dhaka-1219<br>Mobile: 01872224455</p>
                                </div>
                            </div>
                            
                            <!-- BEGIN CONTAINER -->
                            <div id="container" class="row-fluid">
                                <?php $this->load->view($content); ?>
                            </div>
                            <!-- END CONTAINER -->

                             <!-- BEGIN FOOTER -->
                            <div id="footer" class="row-fluid" style="margin-bottom: 0px; margin-top: 15px; padding: 2px;">
                                <div style="color: #333333;font-size: 12px;" class="pull-right">Print Date: <?php echo date('m/d/Y g:i a'); ?></div>
                                <div style="color: #333333;font-size: 12px;" class="left">Powered by: <b>Bisorgo Technology</b></div>
                            </div>
                            <!-- END FOOTER -->
                           
                        </div>
                    </div>
                    <!--/invoice-->
                </div><!--/content-body -->
            </div><!-- /content -->
        </div><!-- /span content -->
    </div><!-- /container -->
</section>

</body>
</html>