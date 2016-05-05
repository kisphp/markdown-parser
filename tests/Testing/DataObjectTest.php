<?php

namespace Kisphp\Testing;

use Kisphp\MarkdownFactory;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Kisphp\Exceptions\DataObjectBlockAlreadyExists
     */
    public function test_DoubleAddTemplateBlock()
    {
        $text = <<<MARKDOWN
:::code-1
this is my content
:::

:::code-1
this is my content
:::

MARKDOWN;

        $md = MarkdownFactory::createMarkdown();

        $md->parse($text);
    }
}
