<?php

namespace Kisphp\Testing;

use Kisphp\MarkdownFactory;
use PHPUnit\Framework\TestCase;

class DataObjectTest extends TestCase
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
