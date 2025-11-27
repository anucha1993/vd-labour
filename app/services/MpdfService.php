<?php

namespace App\Services;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class MpdfService
{
    protected $mpdf;

    public function __construct()
    {
        $this->mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 12,
            'default_font' => 'sarabun',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'orientation' => 'P'
        ]);
        
        // Support Thai fonts
        $this->mpdf->autoScriptToLang = true;
        $this->mpdf->autoLangToFont = true;
    }

    /**
     * Generate PDF from view
     */
    public function generateFromView($view, $data = [], $filename = 'document.pdf', $destination = 'D', $config = [])
    {
        // Create new instance with custom config if provided
        if (!empty($config)) {
            $this->mpdf = new Mpdf($config);
            $this->mpdf->autoScriptToLang = true;
            $this->mpdf->autoLangToFont = true;
        }
        
        $html = View::make($view, $data)->render();
        
        $this->mpdf->WriteHTML($html);
        
        if ($destination === 'I') {
            // Display inline in browser
            return response($this->mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } else {
            // Download as attachment (default)
            return response($this->mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
    }

    /**
     * Generate PDF from HTML string
     */
    public function generateFromHtml($html, $filename = 'document.pdf', $destination = 'D')
    {
        $this->mpdf->WriteHTML($html);
        
        if ($destination === 'I') {
            // Display inline in browser
            return response($this->mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } else {
            // Download as attachment (default)
            return response($this->mpdf->Output($filename, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
    }

    /**
     * Get PDF content as string (for storage or email)
     */
    public function getContentFromView($view, $data = [])
    {
        $html = View::make($view, $data)->render();
        
        $this->mpdf->WriteHTML($html);
        
        return $this->mpdf->Output('', 'S');
    }

    /**
     * Save PDF to storage
     */
    public function saveToStorage($view, $data = [], $path = 'pdfs/document.pdf')
    {
        $html = View::make($view, $data)->render();
        
        $this->mpdf->WriteHTML($html);
        
        $pdfContent = $this->mpdf->Output('', 'S');
        
        \Storage::disk('local')->put($path, $pdfContent);
        
        return $path;
    }

    /**
     * Set custom configuration
     */
    public function setConfig($config = [])
    {
        $this->mpdf = new Mpdf($config);
        return $this;
    }

    /**
     * Add page break
     */
    public function addPageBreak()
    {
        $this->mpdf->AddPage();
        return $this;
    }

    /**
     * Set header
     */
    public function setHeader($html)
    {
        $this->mpdf->SetHTMLHeader($html);
        return $this;
    }

    /**
     * Set footer
     */
    public function setFooter($html)
    {
        $this->mpdf->SetHTMLFooter($html);
        return $this;
    }

    /**
     * Add CSS
     */
    public function addCSS($css)
    {
        $this->mpdf->WriteHTML($css, 1);
        return $this;
    }
}