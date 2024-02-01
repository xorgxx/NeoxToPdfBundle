<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\Services;
    
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\NeoxToPdfAbstract;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    
    class pdfLayerService extends NeoxToPdfAbstract
    {
        
        public readonly HttpClientInterface $httpClient;
        
        
        /**
         * method:  class construction
         * The pdflayer API requires a valid webpage URL or posted HTML to convert.
         *
         * @param HttpClientInterface   $httpClient
         * @param ParameterBagInterface $parameterBag
         */
        public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
        {
            parent::__construct($httpClient, $parameterBag);
            $this->schema = "pdflayer";
        }
        
        /**
         * method:  convert
         * usage:   convert([redirect=false]);
         * params:  redirect = redirect browser to api
         * This method will query the api to convert the html to pdf.
         * If redirect is set to true, browser will be redirected directly to api.
         *
         * @param bool $redirect
         *
         * @return pdfLayerService
         * @throws TransportExceptionInterface
         */
        public function convert(bool $redirect = false): self
        {
            
            if (empty($this->params['document_url']) and empty($this->params['document_html'])) {
                throw new NotFoundHttpException('A document source must be provided');
            }
            
            $request        = $this->build_request();
            
            /** In cas we need to add a secret in params */
            // $secret_key = md5($url . $this->secret_keyword);
            // $this->params['secret_key'] = $secret_key;
            
            $keysToCheck    = ['document_html', 'header_html', 'footer_html'];
            $postData       = array_filter($this->params, function ($key) use ($keysToCheck) {
                return in_array($key, $keysToCheck) && !empty($this->params[$key]);
            }, ARRAY_FILTER_USE_KEY);
            
            // !!!
            if ($redirect) {
                header('location: ' . $request);
                exit;
            }
            
            $this->doApi($request, $postData);
            
            return $this;
        }
        
    }