<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\NeoxPdf;
    
    use NeoxToPdf\NeoxToPdfBundle\Services\pdfLayerService;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    class NeoxToPdfFactory
    {
        public function __construct(
            readonly HttpClientInterface $httpClient,
            readonly ParameterBagInterface $parameterBag,
        ) {}
        public function pdfLayerService( ): pdfLayerService
        {
            return new pdfLayerService($this->httpClient, $this->parameterBag);
        }
    }