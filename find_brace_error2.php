<?php

declare(strict_types=1);

$code = file_get_contents('app/Domains/Chatbot/Actions/ProcessRuleBasedChatMessageAction.php');
$tokens = token_get_all($code);

$depth = 0;
$line = 1;
$parenDepth = 0;
$lastOpenBraceLine = 0;
$openBraceLines = [];

foreach ($tokens as $i => $token) {
    if (is_array($token)) {
        $line = $token[2];
        if (in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, T_CONSTANT_ENCAPSED_STRING, T_INLINE_HTML])) {
            continue;
        }
    }

    if ($token === '{') {
        $depth++;
        $openBraceLines[] = $line;
        $lastOpenBraceLine = $line;
    } elseif ($token === '}') {
        $depth--;
        if ($depth < 0) {
            echo "EXTRA CLOSE BRACE at line {$line}\n";
            $depth = 0;
            array_pop($openBraceLines);
        } else {
            array_pop($openBraceLines);
        }
    } elseif ($token === '(') {
        $parenDepth++;
    } elseif ($token === ')') {
        $parenDepth--;
        if ($parenDepth < 0) {
            echo "EXTRA CLOSE PAREN at line {$line}\n";
            $parenDepth = 0;
        }
    }
}

echo "Final brace depth: {$depth}\n";
echo "Final paren depth: {$parenDepth}\n";
echo "Last unmatched open brace line: {$lastOpenBraceLine}\n";

if ($depth > 0) {
    echo "\n{$depth} unclosed brace(s) at lines: ".implode(', ', array_slice($openBraceLines, -$depth))."\n";
}
echo "DONE\n";
