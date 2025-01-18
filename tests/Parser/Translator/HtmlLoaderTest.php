<?php

namespace Ucscode\UssElement\Test\Parser\Translator;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Parser\HtmlLoader;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Node\TextNode;

class HtmlLoaderTest extends TestCase
{
    public const BOOTSTRAP_MODAL_STR = <<<BOOTSTRAP_MODAL
        <div class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- this is a comment -->
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        BOOTSTRAP_MODAL;

    public const DOCUMENT_STR = <<<DOCUMENT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Test Page</title>
            <!-- This is a comment expression -->
        </head>
        <body>
            <div class="container">
                <h1>Welcome to the Test Page</h1>
                <p>This is a small snippet for testing purposes.</p>
                <button onclick="alert('Button clicked!')">Click Me</button>
            </div>
        </body>
        </html>
        DOCUMENT;

    public function testBootstrapHtmlLoader(): void
    {
        $htmlLoader = new HtmlLoader(self::BOOTSTRAP_MODAL_STR);

        /**
         * @var ElementInterface $modalElement
         */
        $modalElement = $htmlLoader->getNodeList()->first();

        $this->assertCount(1, $htmlLoader->getNodeList());
        $this->assertSame('modal', (string) $modalElement->getClassList());

        $pElement = $modalElement->querySelector('p');

        $this->assertSame('P', $pElement->getTagName());
        $this->assertCount(1, $pElement->getChildNodes());

        $this->assertInstanceOf(TextNode::class, $pElement->getFirstChild());
    }

    public function testDocumentHtmlLoader(): void
    {
        $htmlLoader = new HtmlLoader(self::DOCUMENT_STR);

        /**
         * @var ElementNode $htmlElement
         */
        $htmlElement = $htmlLoader->getNodeList()->get(1);

        $this->assertNotNull($htmlElement);
        $this->assertCount(2, $htmlElement->querySelectorAll('meta'));
    }
}
