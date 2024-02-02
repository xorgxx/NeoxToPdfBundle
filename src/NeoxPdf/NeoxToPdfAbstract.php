<?php
    
    namespace NeoxToPdf\NeoxToPdfBundle\NeoxPdf;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
    use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
    use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
    use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;
    use Symfony\Component\HttpClient\Response\TraceableResponse;
    
    Abstract class NeoxToPdfAbstract
    {
        protected string $schema    = "pdflayer";
        /**
         * API key/value pair params
         **/
        protected array $postData   = array();
        protected array $query      = array();
        protected mixed $response;
        protected string $pdf;
        
        public function __construct(private readonly HttpClientInterface $httpClient, private readonly ParameterBagInterface $parameterBag) {}
        
        protected function build_request(): string
        {
            // get class name
            $fullClassName      = get_class($this);
            $className          = str_replace('Service', '', basename(str_replace('\\', '/', $fullClassName)));
            $this->schema       = $className;  // strtolower()
            
            $dsn    = $this->getSchema();
            $dsn    = new Dsn($dsn);
            
            // Build the query string
            $query  = http_build_query($this->query, '', '&', PHP_QUERY_RFC3986);
            return "https://" . $dsn->getHost() . $dsn->getPath() . '?access_key=' .$dsn->getUser() .'&' . $query;

        }
        
        /**
         * @param string $request
         * @param array  $postData
         *
         * @return void
         * @throws TransportExceptionInterface
         */
        protected function doApi(string $request, array $postData): self
        {
            $response = $this->httpClient->request('POST', $request, [
                'body' => http_build_query($postData),
            ]);
            
            try {
                $this->response     = $response;
                $this->pdf          = $response->getContent();
                
            } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
                throw new NotFoundHttpException($response->error->info);
            }
            
            return $this;
        }
        
        
        /**
         * method:  setParams
         * usage:   setParams(string key, string value);
         * params:  key = key of the params key/value pair
         * value =  value of the params key/value pair
         * add or change the params key/value pair specified.
         *
         * @returns: null
         **/
        public function setPostData($key, $value = 1): self
        {
            $this->postData[$key] = $value;
            return $this;
        }
        
        /**
         * method:  setParams
         * usage:   setParams(string key, string value);
         * params:  key = key of the params key/value pair
         * value =  value of the params key/value pair
         * add or change the params key/value pair specified.
         *
         * @returns: null
         **/
        public function setQuery($key, $value = 1): self
        {
            $this->query[$key] = $value;
            return $this;
        }
        
        /**
         * method:  download_pdf
         * usage:   download_pdf([string file_name='']);
         * params:  file_name = The name of the file written to disk
         *
         * This method will download the pdf to the client.
         *
         * returns: null
         **/
        public function download_pdf($file_name = ''): Response
        {
            
            $file_name = (empty($file_name)) ? 'pdf' : $file_name;
            
            if (empty($this->pdf)) {
                
                throw new NotFoundHttpException('No PDF has been generated');
                
            }

//            header('Content-Type: application/pdf');
//            header('Content-Disposition: attachment; filename="'.$file_name.'"');
//            header('Content-Transfer-Encoding: binary');
            
            $response = new Response($this->pdf);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $file_name . '"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            return $response;
            
        }
        
        /**
         * method:  display_pdf
         * usage:   display_pdf(void);
         * params:  none
         *
         * This method will display the pdf to the browser.
         *
         * returns: null
         **/
        public function display_pdf(): Response
        {
            
            if (empty($this->pdf)) {
                throw new NotFoundHttpException('No PDF has been generated');
            }
            
            $response = new Response($this->pdf);
            $response->headers->set('Content-Type', 'application/pdf');
            
            return $response;
        }
        
        public function getStreamPdf(): string
        {
            return $this->pdf;
        }
        
        public function getRawResponse(): mixed
        {
            return $this->response;
        }
        
        public function file_pdf($file_name = ''): BinaryFileResponse
        {
            $file_name = (empty($file_name)) ? 'pdf' : $file_name;
            
            if (empty($this->pdf)) {
                throw new NotFoundHttpException('No PDF has been generated');
            }
            $path           = $this->parameterBag->get('neox_to_pdf.directory_save');
            $pdfDirectory   = $this->parameterBag->get('kernel.project_dir') . $path ;
            $pdfFilePath    = $pdfDirectory . $file_name . '.pdf';
            
            // Vérifier si le répertoire existe, sinon le créer
            if (!is_dir($pdfDirectory)) {
                if (!mkdir($pdfDirectory, 0777, true) && !is_dir($pdfDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $pdfDirectory));
                }
            }
            
            // Écrire le contenu du PDF dans le fichier
            file_put_contents($pdfFilePath, $this->pdf);
            
            // Retourner le fichier en tant que réponse
            return new BinaryFileResponse($pdfFilePath);
        }
        
        
        
        private function getSchema(): string | array | null
        {
            $services       = $this->parameterBag->get("neox_to_pdf.services", []);
            $customs        = $this->parameterBag->get("neox_to_pdf.customs", []);
            $schema         = array_merge($services, $customs);
            
            return $schema[$this->schema] ?? null;
//            $schemas = $this->parameterBag->get("neox_to_pdf.services");
//            return $schemas[$this->schema] ?? null;
        }
    }