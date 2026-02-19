<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Run this script from CLI.\n");
    exit(1);
}

main($argv);

function main(array $argv): void
{
    $opts = parseArgs($argv);
    $target = resolvePath($opts['target']);
    $jsRoot = resolvePath($opts['jsRoot']);

    $phpFiles = collectPhpFiles($target);
    if (!count($phpFiles)) {
        fwrite(STDERR, "No PHP files found for target: {$opts['target']}\n");
        exit(1);
    }

    $keepSet = [];

    if ($opts['mode'] === 'class' || $opts['mode'] === 'both') {
        $jsFiles = collectFilesByExtension($jsRoot, 'js');
        foreach ($jsFiles as $jsFile) {
            if (preg_match('/\.min\.js$/i', basename($jsFile))) {
                continue;
            }

            $content = @file_get_contents($jsFile);
            if ($content === false) {
                continue;
            }

            extractKeepTokensFromText($content, $keepSet);
        }

        foreach ($phpFiles as $phpFile) {
            $content = @file_get_contents($phpFile);
            if ($content === false) {
                continue;
            }

            if (preg_match_all('/<script\b[^>]*>([\s\S]*?)<\/script>/i', $content, $matches)) {
                foreach ($matches[1] as $scriptBody) {
                    extractKeepTokensFromText($scriptBody, $keepSet);
                }
            }
        }
    }

    $stats = [
        'files_scanned' => 0,
        'files_changed' => 0,
        'class_attrs_seen' => 0,
        'class_attrs_changed' => 0,
        'class_attrs_removed' => 0,
        'class_tokens_removed' => 0,
        'style_attrs_seen' => 0,
        'style_attrs_removed' => 0,
        'style_attrs_kept' => 0,
    ];

    foreach ($phpFiles as $phpFile) {
        $stats['files_scanned']++;

        $original = @file_get_contents($phpFile);
        if ($original === false) {
            continue;
        }

        $parts = preg_split('/(<script\b[^>]*>[\s\S]*?<\/script>)/i', $original, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!is_array($parts)) {
            continue;
        }

        $rebuilt = '';

        foreach ($parts as $part) {
            if (preg_match('/^<script\b/i', $part)) {
                $rebuilt .= $part;
                continue;
            }

            if ($opts['mode'] === 'class' || $opts['mode'] === 'both') {
                $part = transformAttribute(
                    $part,
                    'class',
                    function ($attrName, $quote, $escaped, $value, $rawAttr) use (&$stats, $keepSet) {
                        $stats['class_attrs_seen']++;

                        if (trim($value) === '') {
                            $stats['class_attrs_removed']++;
                            return '';
                        }

                        $placeholders = [];
                        $normalized = preg_replace_callback(
                            '/<\?(?:php|=)?[\s\S]*?\?>/i',
                            function ($m) use (&$placeholders) {
                                $key = '__PHPBLOCK' . count($placeholders) . '__';
                                $placeholders[$key] = $m[0];
                                return ' ' . $key . ' ';
                            },
                            $value
                        );

                        $tokens = preg_split('/\s+/', trim((string) $normalized));
                        if (!is_array($tokens)) {
                            return $rawAttr;
                        }

                        $kept = [];
                        $removedAny = false;

                        foreach ($tokens as $token) {
                            if ($token === '') {
                                continue;
                            }

                            if (isset($placeholders[$token])) {
                                $kept[] = $token;
                                continue;
                            }

                            if (shouldKeepClassToken($token, $keepSet)) {
                                $kept[] = $token;
                                continue;
                            }

                            $removedAny = true;
                            $stats['class_tokens_removed']++;
                        }

                        if (!$removedAny) {
                            return $rawAttr;
                        }

                        if (!count($kept)) {
                            $stats['class_attrs_removed']++;
                            return '';
                        }

                        $nextValue = implode(' ', $kept);
                        foreach ($placeholders as $key => $phpCode) {
                            $nextValue = str_replace($key, $phpCode, $nextValue);
                        }

                        $stats['class_attrs_changed']++;
                        $q = $escaped ? '\\' . $quote : $quote;
                        return $attrName . '=' . $q . $nextValue . $q;
                    },
                    $stats
                );
            }

            if ($opts['mode'] === 'style' || $opts['mode'] === 'both') {
                $part = transformAttribute(
                    $part,
                    'style',
                    function ($attrName, $quote, $escaped, $value, $rawAttr) use (&$stats) {
                        $stats['style_attrs_seen']++;

                        if (preg_match('/\bdisplay\s*:\s*none\b/i', $value)) {
                            $stats['style_attrs_kept']++;
                            return $rawAttr;
                        }

                        $stats['style_attrs_removed']++;
                        return '';
                    },
                    $stats
                );
            }

            $rebuilt .= $part;
        }

        if ($rebuilt !== $original) {
            $stats['files_changed']++;
            if (!$opts['dryRun']) {
                file_put_contents($phpFile, $rebuilt);
            }
        }
    }

    ksort($keepSet);

    echo 'mode=' . $opts['mode'] . PHP_EOL;
    echo 'target=' . $opts['target'] . PHP_EOL;
    echo 'dry_run=' . ($opts['dryRun'] ? 'true' : 'false') . PHP_EOL;
    echo 'files_scanned=' . $stats['files_scanned'] . PHP_EOL;
    echo 'files_changed=' . $stats['files_changed'] . PHP_EOL;

    if ($opts['mode'] === 'class' || $opts['mode'] === 'both') {
        echo 'keep_tokens=' . count($keepSet) . PHP_EOL;
        echo 'class_attrs_seen=' . $stats['class_attrs_seen'] . PHP_EOL;
        echo 'class_attrs_changed=' . $stats['class_attrs_changed'] . PHP_EOL;
        echo 'class_attrs_removed=' . $stats['class_attrs_removed'] . PHP_EOL;
        echo 'class_tokens_removed=' . $stats['class_tokens_removed'] . PHP_EOL;
    }

    if ($opts['mode'] === 'style' || $opts['mode'] === 'both') {
        echo 'style_attrs_seen=' . $stats['style_attrs_seen'] . PHP_EOL;
        echo 'style_attrs_removed=' . $stats['style_attrs_removed'] . PHP_EOL;
        echo 'style_attrs_kept=' . $stats['style_attrs_kept'] . PHP_EOL;
    }
}

function parseArgs(array $argv): array
{
    $opts = [
        'target' => 'theme/basic',
        'mode' => 'both',
        'jsRoot' => 'js',
        'dryRun' => false,
    ];

    for ($i = 1; $i < count($argv); $i++) {
        $arg = (string) $argv[$i];
        if ($arg === '--dry-run') {
            $opts['dryRun'] = true;
            continue;
        }

        if (strpos($arg, '--target=') === 0) {
            $opts['target'] = substr($arg, 9);
            continue;
        }

        if (strpos($arg, '--mode=') === 0) {
            $opts['mode'] = strtolower((string) substr($arg, 7));
            continue;
        }

        if (strpos($arg, '--js-root=') === 0) {
            $opts['jsRoot'] = substr($arg, 10);
            continue;
        }

        fwrite(STDERR, "Unknown option: {$arg}\n");
        printUsageAndExit(1);
    }

    if (!in_array($opts['mode'], ['class', 'style', 'both'], true)) {
        fwrite(STDERR, "Invalid --mode value: {$opts['mode']}\n");
        printUsageAndExit(1);
    }

    return $opts;
}

function printUsageAndExit(int $code): void
{
    $usage = <<<TXT
Usage:
  php cleanup_legacy_attrs.php [--target=<path>] [--mode=class|style|both] [--js-root=<path>] [--dry-run]

Examples:
  php cleanup_legacy_attrs.php --target=theme/basic --mode=both --dry-run
  php cleanup_legacy_attrs.php --target=theme/basic --mode=class
  php cleanup_legacy_attrs.php --target=theme/basic --mode=style

TXT;
    fwrite($code === 0 ? STDOUT : STDERR, $usage);
    exit($code);
}

function resolvePath(string $path): string
{
    if ($path === '') {
        return getcwd();
    }

    $isAbsolute = false;
    if (preg_match('/^[A-Za-z]:[\\\\\\/]/', $path)) {
        $isAbsolute = true;
    } elseif (strpos($path, '/') === 0 || strpos($path, '\\') === 0) {
        $isAbsolute = true;
    }

    if ($isAbsolute) {
        return $path;
    }

    return getcwd() . DIRECTORY_SEPARATOR . $path;
}

function collectPhpFiles(string $target): array
{
    if (is_file($target)) {
        return strtolower((string) pathinfo($target, PATHINFO_EXTENSION)) === 'php' ? [$target] : [];
    }

    return collectFilesByExtension($target, 'php');
}

function collectFilesByExtension(string $root, string $ext): array
{
    $files = [];
    if (!is_dir($root)) {
        return $files;
    }

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($it as $entry) {
        if (!$entry->isFile()) {
            continue;
        }

        if (strtolower($entry->getExtension()) !== strtolower($ext)) {
            continue;
        }

        $files[] = $entry->getPathname();
    }

    sort($files);
    return $files;
}

function extractKeepTokensFromText(string $text, array &$keepSet): void
{
    if (preg_match_all('/([\'"])((?:\\\\.|(?!\1).)*)\1/s', $text, $literalMatches, PREG_SET_ORDER)) {
        foreach ($literalMatches as $match) {
            $literal = (string) $match[2];
            if ($literal === '') {
                continue;
            }

            if (preg_match_all('/\.([A-Za-z_-][A-Za-z0-9_-]*)/', $literal, $classMatches)) {
                foreach ($classMatches[1] as $token) {
                    addKeepToken($keepSet, (string) $token);
                }
            }
        }
    }

    if (preg_match_all('/(?:classList\.(?:add|remove|toggle|contains|replace)|(?:addClass|removeClass|toggleClass|hasClass))\s*\(([^)]*)\)/s', $text, $methodMatches)) {
        foreach ($methodMatches[1] as $args) {
            if (!preg_match_all('/[\'"]([^\'"]+)[\'"]/', (string) $args, $argMatches)) {
                continue;
            }

            foreach ($argMatches[1] as $chunk) {
                $parts = preg_split('/\s+/', trim((string) $chunk));
                if (!is_array($parts)) {
                    continue;
                }

                foreach ($parts as $token) {
                    if ($token === '') {
                        continue;
                    }

                    if (!preg_match('/^[A-Za-z_-][A-Za-z0-9_-]*$/', $token)) {
                        continue;
                    }

                    addKeepToken($keepSet, $token);
                }
            }
        }
    }

    if (preg_match_all('/getElementsByClassName\s*\(\s*[\'"]([A-Za-z_-][A-Za-z0-9_-]*)[\'"]\s*\)/', $text, $byClassMatches)) {
        foreach ($byClassMatches[1] as $token) {
            addKeepToken($keepSet, (string) $token);
        }
    }
}

function addKeepToken(array &$set, string $token): void
{
    if ($token === '') {
        return;
    }

    $set[$token] = true;
}

function shouldKeepClassToken(string $token, array $keepSet): bool
{
    if ($token === '') {
        return false;
    }

    if (isset($keepSet[$token])) {
        return true;
    }

    if (strpos($token, 'hs-') === 0 || strpos($token, 'ui-') === 0 || strpos($token, 'js-') === 0) {
        return true;
    }

    if (strpos($token, '[--') !== false) {
        return true;
    }

    return false;
}

function isAttrNameChar(string $ch): bool
{
    return ctype_alnum($ch) || $ch === '_' || $ch === '-';
}

function transformAttribute(string $segment, string $attrNeedle, callable $handler, array &$stats): string
{
    $len = strlen($segment);
    if ($len === 0) {
        return $segment;
    }

    $out = '';
    $cursor = 0;
    $needleLen = strlen($attrNeedle);

    while ($cursor < $len) {
        $pos = stripos($segment, $attrNeedle, $cursor);
        if ($pos === false) {
            $out .= substr($segment, $cursor);
            break;
        }

        $out .= substr($segment, $cursor, $pos - $cursor);

        $before = $pos > 0 ? $segment[$pos - 1] : '';
        $after = ($pos + $needleLen) < $len ? $segment[$pos + $needleLen] : '';
        if (($before !== '' && isAttrNameChar($before)) || ($after !== '' && isAttrNameChar($after))) {
            $out .= substr($segment, $pos, $needleLen);
            $cursor = $pos + $needleLen;
            continue;
        }

        $i = $pos + $needleLen;
        while ($i < $len && ctype_space($segment[$i])) {
            $i++;
        }

        if ($i >= $len || $segment[$i] !== '=') {
            $out .= substr($segment, $pos, $needleLen);
            $cursor = $pos + $needleLen;
            continue;
        }

        $i++;
        while ($i < $len && ctype_space($segment[$i])) {
            $i++;
        }

        if ($i >= $len) {
            $out .= substr($segment, $pos);
            break;
        }

        $parsed = parseQuotedAttribute($segment, $i);
        if ($parsed === null) {
            $out .= substr($segment, $pos, $needleLen);
            $cursor = $pos + $needleLen;
            continue;
        }

        $attrNameOriginal = substr($segment, $pos, $needleLen);
        $rawAttr = substr($segment, $pos, $parsed['close_end'] - $pos);
        $value = substr($segment, $parsed['value_start'], $parsed['value_end'] - $parsed['value_start']);

        $replacement = $handler(
            $attrNameOriginal,
            $parsed['quote'],
            $parsed['escaped'],
            $value,
            $rawAttr,
            $stats
        );

        if ($replacement === '') {
            $out = rtrim($out, " \t");
        } else {
            $out .= $replacement;
        }

        $cursor = $parsed['close_end'];
    }

    return $out;
}

function parseQuotedAttribute(string $text, int $start): ?array
{
    $len = strlen($text);
    if ($start >= $len) {
        return null;
    }

    $escaped = false;
    $quote = '';
    $valueStart = $start;

    if ($text[$start] === '\\' && ($start + 1) < $len && ($text[$start + 1] === '"' || $text[$start + 1] === '\'')) {
        $escaped = true;
        $quote = $text[$start + 1];
        $valueStart = $start + 2;
    } elseif ($text[$start] === '"' || $text[$start] === '\'') {
        $quote = $text[$start];
        $valueStart = $start + 1;
    } else {
        return null;
    }

    $i = $valueStart;
    while ($i < $len) {
        if ($text[$i] === '<' && ($i + 1) < $len && $text[$i + 1] === '?') {
            $phpEnd = strpos($text, '?>', $i + 2);
            if ($phpEnd === false) {
                return null;
            }
            $i = $phpEnd + 2;
            continue;
        }

        if ($escaped) {
            if ($text[$i] === '\\' && ($i + 1) < $len && $text[$i + 1] === $quote) {
                return [
                    'quote' => $quote,
                    'escaped' => true,
                    'value_start' => $valueStart,
                    'value_end' => $i,
                    'close_end' => $i + 2,
                ];
            }
        } else {
            if ($text[$i] === $quote) {
                return [
                    'quote' => $quote,
                    'escaped' => false,
                    'value_start' => $valueStart,
                    'value_end' => $i,
                    'close_end' => $i + 1,
                ];
            }
        }

        $i++;
    }

    return null;
}

