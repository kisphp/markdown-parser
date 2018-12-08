<?php

$header = <<<'EOF'
This file is part of Kisphp framework

(c) Marius-Bogdan Rizac <mariusbogdan83@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules([
        '@PHP56Migration' => true,
//        '@PHPUnit60Migration:risky' => true,
        '@Symfony' => true,
//        '@Symfony:risky' => true,
        'align_multiline_comment' => false,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'final_internal_class' => true,
//        'header_comment' => ['header' => $header],
        'heredoc_to_nowdoc' => true,
        'increment_style' => false,
        'list_syntax' => ['syntax' => 'long'],
        'method_chaining_indentation' => true,
        'method_argument_space' => ['ensure_fully_multiline' => true],
        'no_extra_consecutive_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_null_property_initialization' => true,
        'no_short_echo_tag' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unreachable_default_argument_value' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_align' => false,
        'phpdoc_indent' => false,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_separation' => true,
        'phpdoc_summary' => false,
        'php_unit_strict' => true,
        'php_unit_test_annotation' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => false,
        'phpdoc_types_order' => true,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => false,
        'strict_comparison' => true,
        'strict_param' => false,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests/Fixtures')
            ->exclude('src/System/AdminBundle/Resources/')
            ->in(__DIR__ . '/src')
    )
;

// special handling of fabbot.io service if it's using too old PHP CS Fixer version
try {
    PhpCsFixer\FixerFactory::create()
        ->registerBuiltInFixers()
        ->registerCustomFixers($config->getCustomFixers())
        ->useRuleSet(new PhpCsFixer\RuleSet($config->getRules()));
} catch (PhpCsFixer\ConfigurationException\InvalidConfigurationException $e) {
    $config->setRules([]);
} catch (UnexpectedValueException $e) {
    $config->setRules([]);
} catch (InvalidArgumentException $e) {
    $config->setRules([]);
}

return $config;
