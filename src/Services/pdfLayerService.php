<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\Services;
    
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\neoxPdfInterface;
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\NeoxToPdfAbstract;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    
    class pdfLayerService extends NeoxToPdfAbstract implements neoxPdfInterface
    {
        public readonly HttpClientInterface $httpClient;
        
        /**
         * method:  class construction
         * The pdflayer API requires a valid webpage URL or posted HTML to convert.
         *
         * @param HttpClientInterface   $httpClient
         * @param ParameterBagInterface $parameterBag
         */
        public function htmlConverter(bool $redirect = false): mixed
        {
            if (empty($this->params['document_url']) and empty($this->params['document_html'])) {
                throw new NotFoundHttpException('A document source must be provided');
            }
            
            $request        = $this->buildRequest();
            
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
            
            $this->sendRequest($request, $postData);
            
            return $this;
        }
        
        // Process to convert any (support) to pdf
        public function anyConverter(): mixed
        {
            // TODO: Implement anyConverter() method. Woooooooonnnn !
        }
        
        public function buildRequest(): string
        {
            return $this->build_request();
        }
        
        public function sendRequest(string $request, array $postData): string
        {
            return $this->doApi($request, $postData);
        }
    }