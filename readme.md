# Kisphp Markdown Parser

[![Build Status](https://travis-ci.org/kisphp/markdown-parser.svg?branch=master)](https://travis-ci.org/kisphp/markdown-parser)
[![codecov.io](https://codecov.io/github/kisphp/markdown-parser/coverage.svg?branch=master)](https://codecov.io/github/kisphp/markdown-parser?branch=master)

[![Latest Stable Version](https://poser.pugx.org/kisphp/markdown-parser/v/stable)](https://packagist.org/packages/kisphp/markdown-parser)
[![Total Downloads](https://poser.pugx.org/kisphp/markdown-parser/downloads)](https://packagist.org/packages/kisphp/markdown-parser)
[![License](https://poser.pugx.org/kisphp/markdown-parser/license)](https://packagist.org/packages/kisphp/markdown-parser)
[![Monthly Downloads](https://poser.pugx.org/kisphp/markdown-parser/d/monthly)](https://packagist.org/packages/kisphp/markdown-parser)

## What is this ?

A highly extensible and customizable PHP Markdown Parser that converts makrdown format to HTML format.
Parsing Markdown to HTML is as simple as calling a single method `$markdown->parse($markdownContent)` (see [Usage](https://github.com/kisphp/markdown-parser/wiki)).
To extend the Markdown class to parse custom blocks or format, is as simple as creating new classes that implements `BlockInterface` and include them in the system.
For this, please see [How to extend blocks](https://github.com/kisphp/markdown-parser/wiki/Blocks-Extension-Points).

## What has different from other markdown parsers ?
- code templates to be inserted in other code blocks structures
- generate tables without headers

## Useful wiki pages

- [Installation &amp; Usage](https://github.com/kisphp/markdown-parser/wiki)
- [Templates usage](https://github.com/kisphp/markdown-parser/wiki/Template-blocks)
- [How to extend blocks](https://github.com/kisphp/markdown-parser/wiki/Blocks-Extension-Points)
