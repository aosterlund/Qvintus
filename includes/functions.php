<?php
<?php
// Scans project for top-level functions and writes them into includes/functions.php
$root = __DIR__ . '/../';
$outFile = $root . 'includes/functions.php';
$files = [];

// gather php files (skip vendor, .git)
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($rii as $f) {
    if ($f->isDir()) continue;
    $path = $f->getPathname();
    if (!preg_match('#\.php$#i', $path)) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php') !== false) continue;
    $files[] = $path;
}

$extracted = [];
foreach ($files as $file) {
    $src = file_get_contents($file);
    if ($src === false) continue;
    $tokens = token_get_all($src);
    $level = 0; // track bracket depth to avoid methods inside classes
    $i = 0;
    while ($i < count($tokens)) {
        $t = $tokens[$i];
        if (is_array($t)) {
            if ($t[0] === T_CLASS || $t[0] === T_INTERFACE || $t[0] === T_TRAIT) {
                // skip class/interface/trait body
                // advance to first '{'
                while ($i < count($tokens) && !(is_string($tokens[$i]) && $tokens[$i] === '{')) $i++;
                if ($i < count($tokens) && is_string($tokens[$i]) && $tokens[$i] === '{') {
                    $i++;
                    $level++;
                    // consume until matching closing brace
                    $depth = 1;
                    while ($i < count($tokens) && $depth > 0) {
                        if (is_string($tokens[$i])) {
                            if ($tokens[$i] === '{') $depth++;
                            elseif ($tokens[$i] === '}') $depth--;
                        }
                        $i++;
                    }
                    continue;
                }
            } elseif ($t[0] === T_FUNCTION) {
                // check if this is a closure: next meaningful token is '(' -> closure; else name token
                $j = $i + 1;
                // skip whitespace & comments
                while ($j < count($tokens) && is_array($tokens[$j]) && in_array($tokens[$j][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT])) $j++;
                if ($j < count($tokens) && is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                    // collect function source by finding matching braces after '(' and the body
                    // get position in original source via token offsets if available, else reconstruct naive
                    // fallback: regex extraction of function name and body from file text
                    $m = null;
                    if (preg_match('/function\s+' . preg_quote($tokens[$j][1]) . '\s*\([^)]*\)\s*\{.*\}\s*/sU', $src, $m, PREG_OFFSET_CAPTURE, 0)) {
                        $extracted[$tokens[$j][1]] = $m[0][0];
                    } else {
                        // fallback: try to capture until matching brace
                        $name = $tokens[$j][1];
                        $pattern = '/function\s+' . preg_quote($name) . '\s*\([^)]*\)\s*\{/';
                        if (preg_match($pattern, $src, $mm, PREG_OFFSET_CAPTURE)) {
                            $start = $mm[0][1];
                            // find matching closing brace
                            $pos = $start + strlen($mm[0][0]);
                            $depth = 1;
                            $len = strlen($src);
                            while ($pos < $len && $depth > 0) {
                                if ($src[$pos] === '{') $depth++;
                                elseif ($src[$pos] === '}') $depth--;
                                $pos++;
                            }
                            $extracted[$name] = substr($src, $start, $pos - $start);
                        }
                    }
                }
            }
        }
        $i++;
    }
}

// write output file
$header = "<?php\n// Auto-extracted functions - review before use\n\n";
$out = $header;
foreach ($extracted as $name => $code) {
    $out .= "\n// --- function: $name ---\n";
    $out .= $code . "\n\n";
}

// Example helper function (replace with your extracted functions)
$out .= "function qv_escape(\$s) {\n";
$out .= "    return htmlspecialchars(\$s, ENT_QUOTES, 'UTF-8');\n";
$out .= "}\n\n";

file_put_contents($outFile, $out);
echo "Wrote " . count($extracted) . " functions to $outFile\n";