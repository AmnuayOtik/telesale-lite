<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Pdf extends Dompdf
{
	public function __construct()
	{
		 parent::__construct();

	} 
    
	function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait'){
    //    $dompdf = new Dompdf\DOMPDF();
        $this->load_html($html);
        $this->allow_charset_conversion=true;  // Set by default to TRUE
        $this->set_paper($paper, $orientation);
        $this->charset_in = 'UTF-8';
        $this->autoScriptToLang = true;
        $this->autoLangToFont = true;
        
        

        $this->render();
        
        if($download)
            $this->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $this->stream($filename.'.pdf', array('Attachment' => 0));
    }

    function createPDFlandscape($html, $filename='', $download=TRUE, $paper='A4', $orientation='landscape'){
        //    $dompdf = new Dompdf\DOMPDF();
        $this->load_html($html);
        $this->allow_charset_conversion=true;  // Set by default to TRUE
        $this->set_paper($paper, $orientation);
        $this->charset_in = 'UTF-8';
        $this->autoScriptToLang = true;
        $this->autoLangToFont = true;
        
        

        $this->render();
        
        if($download)
            $this->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $this->stream($filename.'.pdf', array('Attachment' => 0));
    }

}



?>