<?php

if (!function_exists('mpdf')) {
    /**
     * Generate PDF using mPDF
     */
    function mpdf()
    {
        return app('mpdf');
    }
}

if (!function_exists('pdf_from_view')) {
    /**
     * Generate PDF from view
     */
    function pdf_from_view($view, $data = [], $filename = 'document.pdf', $destination = 'D')
    {
        return app('mpdf')->generateFromView($view, $data, $filename, $destination);
    }
}

if (!function_exists('pdf_from_html')) {
    /**
     * Generate PDF from HTML string
     */
    function pdf_from_html($html, $filename = 'document.pdf', $destination = 'D')
    {
        return app('mpdf')->generateFromHtml($html, $filename, $destination);
    }
}