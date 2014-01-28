<?php

namespace Crawler\dom;

use Crawler\http\CURL;

use DOMDocument;
use DOMXPath;

class WebMotors
{

    private $document;
    private $xpath;
    private $url = 'http://www.webmotors.com.br';

    public function __construct()
    {
        libxml_use_internal_errors(true);
        
        $this->document = new DOMDocument();
        $this->document->loadHTMLFile($this->url);
        $this->xpath = new DOMXPath($this->document);
    }

    public function getBrands()
    {
        return $this->xpath->query('//*[@id="IdMarca"]/option[number(@value) = number(@value) and number(@value) > 0]');
    }

    public function getBrand($id)
    {
        return $this->xpath->query("//*[@id='IdMarca']/option[@value={$id}]")->item(0); 
    }

    public function getCars($brand)
    {
        $brand = $this->getBrand($brand)->getAttribute('value');
        $curl = new CURL('http://www.webmotors.com.br/carro/modelos');
        $curl->setMethod('POST');
        $curl->setFields(array('marca' => $brand));

        $response = $curl->getResponse();
        $curl->close();

        return $response;
    }

}