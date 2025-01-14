<?php

    namespace NeoxToPdf\NeoxToPdfBundle\Services;

    use Exception;
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\Dsn;
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\neoxPdfInterface;
    use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\NeoxToPdfAbstract;

    class StirlingPDFService extends NeoxToPdfAbstract implements neoxPdfInterface
    {
        private ?Dsn  $dsn             = null;
        private array $currentPostData = [];

//        public function __construct(private readonly HttpClientInterface $httpClient, private readonly ParameterBagInterface $parameterBag) {}

        /**
         * @throws Exception
         */
        public function htmlConverter(bool $redirect = false): mixed
        {
            $request = $this->buildRequest() . '/api/v1/convert/url/pdf';
            return $this->sendRequest($request, $this->currentPostData);
        }

        /**
         * @throws Exception
         */
        public function anyConverter(): mixed
        {
            $request = $this->buildRequest() . 'convert/file/pdf';
            $this->sendRequest($request, $this->postData);
            return $this;
        }

        public function buildRequest(): string
        {
            return $this->build_request();
        }

        /**
         * @throws Exception
         */
        public function sendRequest(string $request, array $postData): neoxToPdfAbstract
        {
         
            return $this->doApiStirlingPDF($request, $postData);
        }
    }