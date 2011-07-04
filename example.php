#!/usr/bin/env php
<?php
require __DIR__ . '/bootstrap.php';

if (!isset($argv[1]) || !is_readable($argv[1])) {
  echo "Usage: {$argv[0]} path/to/php/file.php\n";
  exit(1);
}
$data = file_get_contents($argv[1]);

try {
  $tree = XHPASTTree::newFromData($data);
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
  exit(1);
}
$root = $tree->getRootNode();
echo $root->getSemanticString();
echo "\n";

function render($children, $indentLevel = 0) {
 foreach ($children as $node) {
  echo str_repeat(' ', $indentLevel);
  echo $node->getTypeName();
  echo ' ';
  echo $node->getSemanticString();
  echo "\n";
  render($node->getChildren(), $indentLevel + 1);
 }
}

render($root->getChildren());

