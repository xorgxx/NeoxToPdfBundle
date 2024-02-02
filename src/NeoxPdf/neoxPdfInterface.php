<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\NeoxPdf;
    
    interface neoxPdfInterface
    {
        // Process to convert html to pdf
        public function htmlConverter(bool $redirect = false): mixed;
        
        // Process to convert any (support) to pdf
        public function anyConverter(): mixed;
        
        // Build the request with the Dsn in .env file
        public function buildRequest(): string;
        
        // Send to API it will give back a string
        public function sendRequest(string $request, array $postData): neoxToPdfAbstract;
        
    }