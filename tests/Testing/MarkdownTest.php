<?php


class MarkdownTest extends PHPUnit_Framework_TestCase
{
    const DIRECTORY = '/../data/';

    /**
     * @dataProvider dataProvider
     */
    public function test_Markup($fileMd, $fileHtml)
    {
        $md = new \Kisphp\Markdown(new \Kisphp\BlockFactory());
        $codeMd = file_get_contents(__DIR__ . self::DIRECTORY . '/' . $fileMd);
        $codeHtml = file_get_contents(__DIR__ . self::DIRECTORY . '/' . $fileHtml);

        $this->assertSame(
            trim($codeHtml),
            trim($md->parse($codeMd))
        );
    }

    public function dataProvider()
    {
        $data = [];

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()
            ->name('*.md')
            ->in(__DIR__ . self::DIRECTORY)
        ;

        foreach ($finder as $file) {
            $data[] = [
                $file->getFilename(),
                str_replace('.md', '.html', $file->getFilename()),
            ];
        }

        return $data;
    }
}
