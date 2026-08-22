<?php

declare(strict_types=1);

$code = file_get_contents('app/Domains/Chatbot/Actions/ProcessRuleBasedChatMessageAction.php');

$depth = 0;
$line = 1;
$openStack = [];
$problemLine = null;
$problemToken = null;

$tokens = token_get_all($code);
foreach ($tokens as $token) {
    if (is_array($token)) {
        $line = $token[2];
        $text = $token[1];
        if ($token[0] === T_WHITESPACE || $token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
            continue;
        }
        // Check for string contents that contain braces
        if ($token[0] === T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }
    } else {
        $text = $token;
    }

    if ($text === '{') {
        $depth++;
        $openStack[] = ['line' => $line, 'char' => $text];
    } elseif ($text === '}') {
        $depth--;
        if ($depth < 0) {
            $problemLine = $line;
            $problemToken = $text;
            break;
        }
        array_pop($openStack);
    } elseif ($text === '(') {
        $depth++;
        $openStack[] = ['line' => $line, 'char' => $text];
    } elseif ($text === ')') {
        $depth--;
        if ($depth < 0) {
            $problemLine = $line;
            $problemToken = $text;
            break;
        }
        array_pop($openStack);
    }
}

echo "Final depth: {$depth}\n";
echo 'Problem at line: '.var_export($problemLine, true).' token: '.var_export($problemToken, true)."\n";
if ($depth > 0 && $problemLine === null) {
    echo "UNCLOSED BRACES remaining: {$depth}\n";
    foreach (array_slice($openStack, -$depth) as $b) {
        echo "  Unclosed at line {$b['line']} char={$b['char']}\n";
    }
}

// Show context around problem line
if ($problemLine !== null) {
    $lines = explode("\n", $code);
    $start = max(0, $problemLine - 5);
    $end = min(count($lines), $problemLine + 3);
    echo "\n=== Context around line {$problemLine} ===\n";
    for ($i = $start; $i < $end; $i++) {
        $marker = ($i + 1 == $problemLine) ? '>>> ' : '    ';
        echo $marker.($i + 1).': '.$lines[$i]."\n";
    }
}
