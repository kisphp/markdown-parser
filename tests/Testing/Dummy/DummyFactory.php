<?php

namespace Kisphp\Testing\Dummy;

use Kisphp\MarkdownFactory;

class DummyFactory extends MarkdownFactory
{
    /**
     * @return array
     */
    public function getAvailableNamespaces()
    {
        $dummyNamespaces = [
            'Kisphp\\Testing\\Dummy\\Blocks\\',
        ];

        $coreNamespaces = parent::getAvailableNamespaces();

        return array_merge($coreNamespaces, $dummyNamespaces);
    }

    /**
     * @return DummyMarkdown
     */
    public static function createMarkdown()
    {
        return new DummyMarkdown(new self());
    }

    /**
     * @param string $markdownContent
     *
     * @return DummyDataObject
     */
    public function createDataObject($markdownContent)
    {
        return new DummyDataObject($markdownContent);
    }
}
