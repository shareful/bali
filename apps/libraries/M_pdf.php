<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pdf {

    function m_pdf() {
        $CI = & get_instance();
    }

    function load( $param = NULL ) {
        include_once APPPATH . 'third_party/mpdf60/mpdf.php';

        if ( $params == NULL ) {
            $param = '"utf-8", "A4", "", "", 10, 10, 10, 10, 5, 5';
        }

        return new mPDF( $param );
    }
}