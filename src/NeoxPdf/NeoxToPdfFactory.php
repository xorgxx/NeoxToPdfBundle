<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\NeoxPdf;
    
    use NeoxToPdf\NeoxToPdfBundle\Services\pdfLayerService;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    class NeoxToPdfFactory
    {
        public function __construct(
            protected readonly HttpClientInterface $httpClient,
            protected readonly ParameterBagInterface $parameterBag,
        ) {}
        public function pdfLayerService( ): pdfLayerService
        {
            return new pdfLayerService($this->httpClient, $this->parameterBag);
        }
        
        public function customService( string $custom ): mixed
        {
            $directory_class    = $this->parameterBag->get('neox_to_pdf.directory_class');
            $customs            = $this->parameterBag->get('neox_to_pdf.customs') ?? [];
            $absolutePath       = str_replace("/", "\\", $directory_class);  //$this->parameterBag->get('kernel.project_dir').
  
            
            if (!array_key_exists($custom, $customs)) {
                throw new \InvalidArgumentException("La clé $custom n'existe pas dans le tableau customs.");
            }
            
            $customClass = $absolutePath . DIRECTORY_SEPARATOR . $custom . "Service";
            
            if (!class_exists($customClass)) {
                throw new \InvalidArgumentException("La classe $customClass n'existe pas.");
            }
            
            return new $customClass($this->httpClient, $this->parameterBag);

        }
    }