# NeoxPdfBundle { Symfony 6 }
This bundle provides service multi API to Pdf-convert in your application.
Its main goal is to make it simple for you to manage integration additional tools!

## Installation BETA VERSION !! 
Install the bundle for Composer !! as is still on beta version !!

````
  composer require xorgxx/neox-pdf-bundle
  or 
  composer require xorgxx/neox-pdf-bundle:0.*
````

**NOTE:** _You may need to use [ symfony composer dump-autoload ] to reload autoloading_

 ..... Done 🎈

## Requirement !!!
You will need to register to one or more API services, they will provide api key:
* Currently, we have implemented only one provider if you need more PR on [GitHub](https://github.com/xorgxx?tab=repositories). we will implement more in the future
  
| Provider                             | env                                                                 | Freemium | Documentation                                       |
|--------------------------------------|---------------------------------------------------------------------|----------|-----------------------------------------------------|
| [PDF-Layer](http://www.pdflayer.com) | PDFLAYER_DSN=pdflayer://opps:[api-key]@api.pdflayer.com/api/convert | v        | [documentation](https://pdflayer.com/documentation) |


## How ?
in order, you will need to add in .env Dsn for ex: pdflayer
````php
  ....
  ###> NeoxToPdf ###
    PDFLAYER_DSN=pdflayer://opps:[api-key]@api.pdflayer.com/api/convert
  ###> NeoxToPdf ###
  ....
````
then add in config/packages/neox_to_pdf.yaml
````php
  neox_to_pdf:
      directory_save: "/public/neoxPdf/"
      services:
          pdflayer: "%env(PDFLAYER_DSN)%"
````
** important : s c:c & c dump-autoload **

## Controller
````php
  <?php
  
  namespace App\Controller\Admin;
  
  use NeoxToPdf\NeoxToPdfBundle\NeoxPdf\NeoxToPdfFactory;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Attribute\Route;
  use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
  
  #[Route('/neox-to-pd')]
  class NeoxToPdfController extends AbstractController
  {
      /**
       * @throws TransportExceptionInterface
       */
      #[Route('/', name: 'app_neox_to_pdf')]
      public function index( NeoxToPdfFactory $neoxToPdf): Response
      {
          return $neoxToPdf->pdfLayerService()
              ->setParams('document_html',"Neox Wooooonnnn convert to pdf")
              ->setParams('test',true)
              ->convert()
              ->display_pdf();
      }
  }
````

## build in command :
  - display_pdf   | give back Pdf in browser
  - download_pdf  | download Pdf file
  - getStream     | get as string
  - file_pdf      | get as BinaryFileResponse


## Contributing
If you want to contribute \(thank you!\) to this bundle, here are some guidelines:

* Please respect the [Symfony guidelines](http://symfony.com/doc/current/contributing/code/standards.html)
* Test everything! Please add tests cases to the tests/ directory when:
    * You fix a bug that wasn't covered before
    * You add a new feature
    * You see code that works but isn't covered by any tests \(there is a special place in heaven for you\)
## Todo
* Packagist

## Thanks