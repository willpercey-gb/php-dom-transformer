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
        if ($xml) {
            $this->xml = $xml instanceof SimpleXMLElement ? $xml : simplexml_load_string($xml);
        }

        if ($dom) {
            if (is_string($dom)) {
                $text = $dom;
                $dom = new DOMDocument();
                $dom->loadHtml($text);
            }

            $this->dom = $dom;
        }
    }


    public static function fromXML(string|SimpleXMLElement $xml): DOMTransformer
    {
        return new static($xml);
    }

    public static function fromRawHTML(string $html): DOMTransformer
    {
        return new static(null, $html);
    }

    public static function fromDOM(string|DOMDocument|DOMElement $dom): DOMTransformer
    {
        return new static(null, $dom);
    }

    public function getXML(): ?SimpleXMLElement
    {
        //dom to xml
        if ($this->xml === null) {
            $this->xml = simplexml_import_dom($this->dom ?? null);
        }
        return $this->xml;
    }

    public function getDOM(): DOMDocument|DOMElement|null
    {
        if ($this->dom === null) {
            $this->dom = dom_import_simplexml($this->xml ?? null);
        }
        return $this->dom;
    }

    public function toArray(): array
    {
        return $this->getXML()->jsonSerialize();
    }
}
