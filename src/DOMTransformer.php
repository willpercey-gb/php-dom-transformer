<?php

namespace UWebPro\DOMTransformer;

use DOMDocument;
use DOMElement;
use SimpleXMLElement;

class DOMTransformer
{
    private ?SimpleXMLElement $xml = null;

    private DOMDocument|DOMElement|null $dom = null;

    public function __construct(
        SimpleXMLElement|string|null $xml = null,
        DOMDocument|DOMElement|string|null $dom = null
    ) {
        if ($xml !== null) {
            $this->xml = $xml instanceof SimpleXMLElement ? $xml : simplexml_load_string($xml);
        }

        if ($dom !== null) {
            $this->dom = is_string($dom) ? (new DOMDocument())->loadHTML($dom) : $dom;
        }
    }


    public static function fromXML(string|SimpleXMLElement $xml): DOMTransformer
    {
        return new self($xml);
    }

    public static function fromRawHTML(string $html): DOMTransformer
    {
        return new self(null, $html);
    }

    public static function fromDOM(string|DOMDocument|DOMElement $dom): DOMTransformer
    {
        return new self(null, $dom);
    }

    public function getXML(): ?SimpleXMLElement
    {
        //dom to xml
        if ($this->xml === null) {
            $this->xml = simplexml_import_dom($this->dom ?? null);
        }
        return $this->xml;
    }

    public function getDOM(): ?DOMElement
    {
        if ($this->dom === null) {
            $this->dom = dom_import_simplexml($this->xml ?? null);
        }
        return $this->dom;
    }
}
