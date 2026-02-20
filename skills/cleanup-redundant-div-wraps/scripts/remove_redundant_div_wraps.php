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
    $phpFiles = collectPhpFiles($target);

    if (!count($phpFiles)) {
        fwrite(STDERR, "No PHP files found for target: {$opts['target']}\n");
        exit(1);
    }

    $stats = [
        'files_scanned' => 0,
        'files_changed' => 0,
        'passes_with_changes' => 0,
        'candidates_seen' => 0,
        'wrappers_removed' => 0,
        'wrappers_empty_removed' => 0,
        'wrappers_single_unwrapped' => 0,
    ];

    foreach ($phpFiles as $phpFile) {
        $stats['files_scanned']++;

        $original = @file_get_contents($phpFile);
        if ($original === false) {
            continue;
        }

        [$updated, $fileStats] = processContent($original, $opts);
        $stats['passes_with_changes'] += $fileStats['passes_with_changes'];
        $stats['candidates_seen'] += $fileStats['candidates_seen'];
        $stats['wrappers_removed'] += $fileStats['wrappers_removed'];
        $stats['wrappers_empty_removed'] += $fileStats['wrappers_empty_removed'];
        $stats['wrappers_single_unwrapped'] += $fileStats['wrappers_single_unwrapped'];

        if ($updated !== $original) {
            $stats['files_changed']++;
            if (!$opts['dryRun']) {
                file_put_contents($phpFile, $updated);
            }
        }
    }

    echo 'mode=' . $opts['mode'] . PHP_EOL;
    echo 'target=' . $opts['target'] . PHP_EOL;
    echo 'dry_run=' . ($opts['dryRun'] ? 'true' : 'false') . PHP_EOL;
    echo 'max_passes=' . $opts['maxPasses'] . PHP_EOL;
    echo 'allow_php=' . ($opts['allowPhp'] ? 'true' : 'false') . PHP_EOL;
    echo 'single_child_tags=' . ($opts['allowAnySingleTag'] ? 'any' : implode(',', $opts['singleChildTags'])) . PHP_EOL;
    echo 'files_scanned=' . $stats['files_scanned'] . PHP_EOL;
    echo 'files_changed=' . $stats['files_changed'] . PHP_EOL;
    echo 'passes_with_changes=' . $stats['passes_with_changes'] . PHP_EOL;
    echo 'div_candidates_seen=' . $stats['candidates_seen'] . PHP_EOL;
    echo 'wrappers_removed=' . $stats['wrappers_removed'] . PHP_EOL;
    echo 'wrappers_empty_removed=' . $stats['wrappers_empty_removed'] . PHP_EOL;
    echo 'wrappers_single_unwrapped=' . $stats['wrappers_single_unwrapped'] . PHP_EOL;
}

function processContent(string $content, array $opts): array
{
    $stats = [
        'passes_with_changes' => 0,
        'candidates_seen' => 0,
        'wrappers_removed' => 0,
        'wrappers_empty_removed' => 0,
        'wrappers_single_unwrapped' => 0,
    ];

    $current = $content;

    for ($pass = 1; $pass <= $opts['maxPasses']; $pass++) {
        $parts = preg_split('/(<script\b[^>]*>[\s\S]*?<\/script>)/i', $current, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!is_array($parts)) {
            break;
        }

        $changedInPass = false;
        $rebuilt = '';

        foreach ($parts as $part) {
            if (preg_match('/^<script\b/i', $part)) {
                $rebuilt .= $part;
                continue;
            }

            [$nextPart, $partStats] = processSegment($part, $opts);
            if ($nextPart !== $part) {
                $changedInPass = true;
            }

            $stats['candidates_seen'] += $partStats['candidates_seen'];
            $stats['wrappers_removed'] += $partStats['wrappers_removed'];
            $stats['wrappers_empty_removed'] += $partStats['wrappers_empty_removed'];
            $stats['wrappers_single_unwrapped'] += $partStats['wrappers_single_unwrapped'];
            $rebuilt .= $nextPart;
        }

        if (!$changedInPass) {
            break;
        }

        $stats['passes_with_changes']++;
        $current = $rebuilt;
    }

    return [$current, $stats];
}

function processSegment(string $segment, array $opts): array
{
    $stats = [
        'candidates_seen' => 0,
        'wrappers_removed' => 0,
        'wrappers_empty_removed' => 0,
        'wrappers_single_unwrapped' => 0,
    ];

    $tokens = tokenizeMarkup($segment);
    if (!count($tokens)) {
        return [$segment, $stats];
    }

    $pairs = annotateDepthAndPairs($tokens);
    if (!count($pairs)) {
        return [$segment, $stats];
    }

    $operations = [];

    foreach ($pairs as $openIdx => $closeIdx) {
        $open = $tokens[$openIdx];
        if (!isBareDivToken($open)) {
            continue;
        }

        $close = $tokens[$closeIdx];
        $innerStart = $open['end'];
        $innerLength = $close['start'] - $innerStart;
        if ($innerLength < 0) {
            continue;
        }

        $inner = substr($segment, $innerStart, $innerLength);
        $stats['candidates_seen']++;

        if (($opts['mode'] === 'empty' || $opts['mode'] === 'both') && isIgnorableSegment($inner, $opts['allowPhp'])) {
            $operations[] = [
                'start' => $open['start'],
                'end' => $close['end'],
                'replacement' => $inner,
                'reason' => 'empty',
            ];
            continue;
        }

        if ($opts['mode'] !== 'single' && $opts['mode'] !== 'both') {
            continue;
        }

        $parentDepth = (int) ($open['depth'] ?? 0);
        $directChildren = findDirectOpeningChildren($tokens, $openIdx, $closeIdx, $parentDepth);
        if (count($directChildren) !== 1) {
            continue;
        }

        $childIdx = $directChildren[0];
        $child = $tokens[$childIdx];
        if (!shouldAllowSingleChildTag((string) $child['name'], $opts['allowAnySingleTag'], $opts['singleChildTags'])) {
            continue;
        }

        $childSpan = resolveElementSpan($tokens, $pairs, $childIdx);
        if ($childSpan === null) {
            continue;
        }

        $before = substr($segment, $innerStart, $childSpan['start'] - $innerStart);
        $afterLength = $close['start'] - $childSpan['end'];
        if ($afterLength < 0) {
            continue;
        }
        $after = substr($segment, $childSpan['end'], $afterLength);

        if (!isIgnorableSegment($before, $opts['allowPhp'])) {
            continue;
        }
        if (!isIgnorableSegment($after, $opts['allowPhp'])) {
            continue;
        }

        $operations[] = [
            'start' => $open['start'],
            'end' => $close['end'],
            'replacement' => $inner,
            'reason' => 'single',
        ];
    }

    if (!count($operations)) {
        return [$segment, $stats];
    }

    $selected = selectNonOverlappingOperations($operations);
    if (!count($selected)) {
        return [$segment, $stats];
    }

    usort(
        $selected,
        function (array $a, array $b): int {
            return $b['start'] <=> $a['start'];
        }
    );

    $updated = $segment;
    foreach ($selected as $operation) {
        $updated = substr($updated, 0, $operation['start']) . $operation['replacement'] . substr($updated, $operation['end']);
        $stats['wrappers_removed']++;
        if ($operation['reason'] === 'empty') {
            $stats['wrappers_empty_removed']++;
        } elseif ($operation['reason'] === 'single') {
            $stats['wrappers_single_unwrapped']++;
        }
    }

    return [$updated, $stats];
}

function tokenizeMarkup(string $segment): array
{
    $tokens = [];
    $length = strlen($segment);
    $i = 0;

    while ($i < $length) {
        if ($segment[$i] !== '<') {
            $i++;
            continue;
        }

        if (substr($segment, $i, 4) === '<!--') {
            $commentEnd = strpos($segment, '-->', $i + 4);
            if ($commentEnd === false) {
                break;
            }
            $tokens[] = [
                'type' => 'comment',
                'start' => $i,
                'end' => $commentEnd + 3,
            ];
            $i = $commentEnd + 3;
            continue;
        }

        if (substr($segment, $i, 2) === '<?') {
            $phpEnd = strpos($segment, '?>', $i + 2);
            if ($phpEnd === false) {
                break;
            }
            $tokens[] = [
                'type' => 'php',
                'start' => $i,
                'end' => $phpEnd + 2,
            ];
            $i = $phpEnd + 2;
            continue;
        }

        $tag = parseTagToken($segment, $i);
        if ($tag !== null) {
            $tokens[] = $tag;
            $i = $tag['end'];
            continue;
        }

        $i++;
    }

    return $tokens;
}

function parseTagToken(string $segment, int $start): ?array
{
    $length = strlen($segment);
    $i = $start + 1;
    $isClosing = false;

    if ($i < $length && $segment[$i] === '/') {
        $isClosing = true;
        $i++;
    }

    while ($i < $length && isWhitespaceByte($segment[$i])) {
        $i++;
    }

    if ($i >= $length || !isAsciiLetter($segment[$i])) {
        return null;
    }

    $nameStart = $i;
    while ($i < $length && isTagNameByte($segment[$i])) {
        $i++;
    }
    $name = strtolower(substr($segment, $nameStart, $i - $nameStart));
    $attrStart = $i;

    $quote = null;
    while ($i < $length) {
        $ch = $segment[$i];

        if ($quote !== null) {
            if ($ch === $quote) {
                $quote = null;
            } elseif ($ch === '\\' && $i + 1 < $length) {
                $i++;
            }
            $i++;
            continue;
        }

        if ($ch === '"' || $ch === "'") {
            $quote = $ch;
            $i++;
            continue;
        }

        if ($ch === '>') {
            break;
        }

        $i++;
    }

    if ($i >= $length || $segment[$i] !== '>') {
        return null;
    }

    $end = $i + 1;
    $raw = substr($segment, $start, $end - $start);
    $attrText = '';

    if (!$isClosing) {
        $attrText = trim(substr($segment, $attrStart, $i - $attrStart));
    }

    $isSelfClosing = false;
    if (!$isClosing) {
        if ($attrText !== '' && preg_match('/\/\s*$/', $attrText)) {
            $isSelfClosing = true;
        }

        static $voidElements = [
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr',
        ];

        if (in_array($name, $voidElements, true)) {
            $isSelfClosing = true;
        }
    }

    return [
        'type' => 'tag',
        'name' => $name,
        'isClosing' => $isClosing,
        'isSelfClosing' => $isSelfClosing,
        'attrText' => $attrText,
        'start' => $start,
        'end' => $end,
        'raw' => $raw,
    ];
}

function annotateDepthAndPairs(array &$tokens): array
{
    $stack = [];
    $pairs = [];

    for ($i = 0; $i < count($tokens); $i++) {
        if ($tokens[$i]['type'] !== 'tag') {
            continue;
        }

        if (!$tokens[$i]['isClosing']) {
            $tokens[$i]['depth'] = count($stack);
            if (!$tokens[$i]['isSelfClosing']) {
                $stack[] = $i;
            }
            continue;
        }

        $tokens[$i]['depth'] = max(count($stack) - 1, 0);

        while (count($stack)) {
            $openIdx = array_pop($stack);
            if ($tokens[$openIdx]['name'] === $tokens[$i]['name']) {
                $pairs[$openIdx] = $i;
                break;
            }
        }
    }

    return $pairs;
}

function isBareDivToken(array $token): bool
{
    if (($token['type'] ?? '') !== 'tag') {
        return false;
    }
    if (($token['name'] ?? '') !== 'div') {
        return false;
    }
    if (($token['isClosing'] ?? false) === true) {
        return false;
    }
    if (($token['isSelfClosing'] ?? false) === true) {
        return false;
    }
    return trim((string) ($token['attrText'] ?? '')) === '';
}

function findDirectOpeningChildren(array $tokens, int $openIdx, int $closeIdx, int $parentDepth): array
{
    $children = [];

    for ($i = $openIdx + 1; $i < $closeIdx; $i++) {
        if (($tokens[$i]['type'] ?? '') !== 'tag') {
            continue;
        }
        if (($tokens[$i]['isClosing'] ?? false) === true) {
            continue;
        }
        if (($tokens[$i]['depth'] ?? -1) !== $parentDepth + 1) {
            continue;
        }
        $children[] = $i;
    }

    return $children;
}

function resolveElementSpan(array $tokens, array $pairs, int $openIdx): ?array
{
    if (($tokens[$openIdx]['type'] ?? '') !== 'tag') {
        return null;
    }

    if (($tokens[$openIdx]['isClosing'] ?? false) === true) {
        return null;
    }

    if (($tokens[$openIdx]['isSelfClosing'] ?? false) === true) {
        return [
            'start' => $tokens[$openIdx]['start'],
            'end' => $tokens[$openIdx]['end'],
        ];
    }

    if (!isset($pairs[$openIdx])) {
        return null;
    }

    $closeIdx = $pairs[$openIdx];
    return [
        'start' => $tokens[$openIdx]['start'],
        'end' => $tokens[$closeIdx]['end'],
    ];
}

function shouldAllowSingleChildTag(string $tagName, bool $allowAny, array $allowList): bool
{
    if ($allowAny) {
        return true;
    }
    return in_array($tagName, $allowList, true);
}

function containsPhpBlock(string $text): bool
{
    return preg_match('/<\?(?!xml)[\s\S]*?\?>/i', $text) === 1;
}

function isIgnorableSegment(string $segment, bool $allowPhp): bool
{
    if (!$allowPhp && containsPhpBlock($segment)) {
        return false;
    }

    $probe = preg_replace('/<!--[\s\S]*?-->/', '', $segment);
    if ($allowPhp) {
        $probe = preg_replace('/<\?(?!xml)[\s\S]*?\?>/i', '', (string) $probe);
    }

    return trim((string) $probe) === '';
}

function selectNonOverlappingOperations(array $operations): array
{
    usort(
        $operations,
        function (array $a, array $b): int {
            $lengthA = $a['end'] - $a['start'];
            $lengthB = $b['end'] - $b['start'];
            if ($lengthA === $lengthB) {
                return $a['start'] <=> $b['start'];
            }
            return $lengthA <=> $lengthB;
        }
    );

    $selected = [];

    foreach ($operations as $operation) {
        $overlap = false;

        foreach ($selected as $chosen) {
            if (rangesOverlap($operation['start'], $operation['end'], $chosen['start'], $chosen['end'])) {
                $overlap = true;
                break;
            }
        }

        if (!$overlap) {
            $selected[] = $operation;
        }
    }

    return $selected;
}

function rangesOverlap(int $startA, int $endA, int $startB, int $endB): bool
{
    return max($startA, $startB) < min($endA, $endB);
}

function parseArgs(array $argv): array
{
    $opts = [
        'target' => 'theme/basic',
        'mode' => 'both',
        'maxPasses' => 2,
        'singleChildTags' => [
            'div',
            'section',
            'article',
            'main',
            'aside',
            'nav',
            'header',
            'footer',
            'ul',
            'ol',
            'table',
            'form',
            'fieldset',
        ],
        'allowAnySingleTag' => false,
        'allowPhp' => false,
        'dryRun' => false,
    ];

    for ($i = 1; $i < count($argv); $i++) {
        $arg = (string) $argv[$i];

        if ($arg === '--dry-run') {
            $opts['dryRun'] = true;
            continue;
        }

        if ($arg === '--allow-php') {
            $opts['allowPhp'] = true;
            continue;
        }

        if (strpos($arg, '--target=') === 0) {
            $opts['target'] = (string) substr($arg, 9);
            continue;
        }

        if (strpos($arg, '--mode=') === 0) {
            $opts['mode'] = strtolower((string) substr($arg, 7));
            continue;
        }

        if (strpos($arg, '--max-passes=') === 0) {
            $value = (string) substr($arg, 13);
            if (!preg_match('/^\d+$/', $value)) {
                fwrite(STDERR, "Invalid --max-passes value: {$value}\n");
                printUsageAndExit(1);
            }
            $opts['maxPasses'] = (int) $value;
            continue;
        }

        if (strpos($arg, '--single-child-tags=') === 0) {
            $value = strtolower(trim((string) substr($arg, 20)));
            if ($value === '' || $value === 'any') {
                $opts['allowAnySingleTag'] = true;
                $opts['singleChildTags'] = [];
                continue;
            }

            $tags = array_values(array_filter(array_map('trim', explode(',', $value)), 'strlen'));
            if (!count($tags)) {
                fwrite(STDERR, "Invalid --single-child-tags value: {$value}\n");
                printUsageAndExit(1);
            }

            $normalized = [];
            foreach ($tags as $tag) {
                if (!preg_match('/^[a-z][a-z0-9:-]*$/', $tag)) {
                    fwrite(STDERR, "Invalid tag in --single-child-tags: {$tag}\n");
                    printUsageAndExit(1);
                }
                $normalized[$tag] = true;
            }

            $opts['allowAnySingleTag'] = false;
            $opts['singleChildTags'] = array_keys($normalized);
            continue;
        }

        fwrite(STDERR, "Unknown option: {$arg}\n");
        printUsageAndExit(1);
    }

    if (!in_array($opts['mode'], ['empty', 'single', 'both'], true)) {
        fwrite(STDERR, "Invalid --mode value: {$opts['mode']}\n");
        printUsageAndExit(1);
    }

    if ($opts['maxPasses'] < 1 || $opts['maxPasses'] > 20) {
        fwrite(STDERR, "Invalid --max-passes value: {$opts['maxPasses']} (allowed: 1-20)\n");
        printUsageAndExit(1);
    }

    return $opts;
}

function printUsageAndExit(int $code): void
{
    $usage = <<<TXT
Usage:
  php remove_redundant_div_wraps.php [--target=<path>] [--mode=empty|single|both] [--max-passes=<n>] [--single-child-tags=<csv|any>] [--allow-php] [--dry-run]

Examples:
  php remove_redundant_div_wraps.php --target=theme/basic --mode=both --dry-run
  php remove_redundant_div_wraps.php --target=theme/basic --mode=single --single-child-tags=div,section
  php remove_redundant_div_wraps.php --target=theme/basic --mode=both --allow-php
TXT;

    fwrite($code === 0 ? STDOUT : STDERR, $usage . PHP_EOL);
    exit($code);
}

function resolvePath(string $path): string
{
    $resolved = realpath($path);
    if ($resolved === false) {
        fwrite(STDERR, "Target path not found: {$path}\n");
        exit(1);
    }
    return $resolved;
}

function collectPhpFiles(string $path): array
{
    if (is_file($path)) {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'php' ? [$path] : [];
    }

    if (!is_dir($path)) {
        return [];
    }

    $files = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $fileInfo) {
        if (!$fileInfo->isFile()) {
            continue;
        }
        if (strtolower($fileInfo->getExtension()) !== 'php') {
            continue;
        }
        $files[] = $fileInfo->getPathname();
    }

    sort($files);
    return $files;
}

function isWhitespaceByte(string $char): bool
{
    return $char === ' ' || $char === "\n" || $char === "\r" || $char === "\t" || $char === "\f" || $char === "\v";
}

function isAsciiLetter(string $char): bool
{
    $ord = ord($char);
    return ($ord >= 65 && $ord <= 90) || ($ord >= 97 && $ord <= 122);
}

function isTagNameByte(string $char): bool
{
    $ord = ord($char);
    if (($ord >= 65 && $ord <= 90) || ($ord >= 97 && $ord <= 122)) {
        return true;
    }
    if ($ord >= 48 && $ord <= 57) {
        return true;
    }
    return $char === '-' || $char === ':' || $char === '_';
}
