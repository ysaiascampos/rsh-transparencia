<?php


namespace App\Classes;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\FpdiProtection\FpdiProtection;

class PasswordProtectPDF
{
    protected $pdf = null;

    public function __construct($file, $user_pass, $master_pass = null)
    {
        $this->protect($file, $user_pass, $master_pass);
    }

    public function protect($file, $user_pass, $master_pass = null)
    {
        $pdf = new FpdiProtection();

        // set the source of the PDF to protect
        $pagecount = $pdf->setSourceFile($file);

        // copy all pages from the old unprotected pdf in the new one
        for ($i = 1; $i <= $pagecount; $i++) {
            // import each page from the original PDF
            $tplidx = $pdf->importPage($i);

            // get the size, orientation etc of each imported page
            $specs = $pdf->getTemplateSize($tplidx);

            // set the correct orientatil, width and height (allows for mixed page type PDFs)
            $pdf->addPage($specs['orientation'], [ $specs['width'], $specs['height'] ]);
            $pdf->useTemplate($tplidx);
        }

        // set user and master passwords, and allowed permissions
        // the master password allows the PDF to be unlocked for full access
        $pdf->SetProtection([ FpdiProtection::PERM_PRINT ], $user_pass, $master_pass);

        // set app name as creator
        $pdf->SetCreator("My Awesome App");

        $this->pdf = $pdf;

        return $this;
    }

    public function setTitle($title)
    {
        // set the title of the document
        $this->pdf->SetTitle($title);

        return $this;
    }

    public function output($name, $type = 'I')
    {
        // output the document
        if ($type === 'S') {
            return $this->pdf->Output($type, $name);
        } else {
            $this->pdf->Output($type, $name);
        }

        return $this;
    }

}
