<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * MODIFIED BY Sean Coates (in accordance with the license)
 */


/**
 * @group xhpast
 */
class XHPASTTree {

  protected $tree = array();
  protected $stream = array();
  protected $lineMap;
  protected $rawSource;

  static public $TOKENS = array(
     258 => 'T_REQUIRE_ONCE',
     259 => 'T_REQUIRE',
     260 => 'T_EVAL',
     261 => 'T_INCLUDE_ONCE',
     262 => 'T_INCLUDE',
     263 => 'T_YIELD',
     264 => 'T_LOGICAL_OR',
     265 => 'T_LOGICAL_XOR',
     266 => 'T_LOGICAL_AND',
     267 => 'T_PRINT',
     268 => 'T_SR_EQUAL',
     269 => 'T_SL_EQUAL',
     270 => 'T_XOR_EQUAL',
     271 => 'T_OR_EQUAL',
     272 => 'T_AND_EQUAL',
     273 => 'T_MOD_EQUAL',
     274 => 'T_CONCAT_EQUAL',
     275 => 'T_DIV_EQUAL',
     276 => 'T_MUL_EQUAL',
     277 => 'T_MINUS_EQUAL',
     278 => 'T_PLUS_EQUAL',
     279 => 'T_BOOLEAN_OR',
     280 => 'T_BOOLEAN_AND',
     281 => 'T_IS_NOT_IDENTICAL',
     282 => 'T_IS_IDENTICAL',
     283 => 'T_IS_NOT_EQUAL',
     284 => 'T_IS_EQUAL',
     285 => 'T_IS_GREATER_OR_EQUAL',
     286 => 'T_IS_SMALLER_OR_EQUAL',
     287 => 'T_SR',
     288 => 'T_SL',
     289 => 'T_INSTANCEOF',
     290 => 'T_UNSET_CAST',
     291 => 'T_BOOL_CAST',
     292 => 'T_OBJECT_CAST',
     293 => 'T_ARRAY_CAST',
     294 => 'T_BINARY_CAST',
     295 => 'T_UNICODE_CAST',
     296 => 'T_STRING_CAST',
     297 => 'T_DOUBLE_CAST',
     298 => 'T_INT_CAST',
     299 => 'T_DEC',
     300 => 'T_INC',
     301 => 'T_CLONE',
     302 => 'T_NEW',
     303 => 'T_EXIT',
     304 => 'T_IF',
     305 => 'T_ELSEIF',
     306 => 'T_ELSE',
     307 => 'T_ENDIF',
     308 => 'T_LNUMBER',
     309 => 'T_DNUMBER',
     310 => 'T_STRING',
     311 => 'T_STRING_VARNAME',
     312 => 'T_VARIABLE',
     313 => 'T_NUM_STRING',
     314 => 'T_INLINE_HTML',
     315 => 'T_CHARACTER',
     316 => 'T_BAD_CHARACTER',
     317 => 'T_ENCAPSED_AND_WHITESPACE',
     318 => 'T_CONSTANT_ENCAPSED_STRING',
     319 => 'T_BACKTICKS_EXPR',
     320 => 'T_ECHO',
     321 => 'T_DO',
     322 => 'T_WHILE',
     323 => 'T_ENDWHILE',
     324 => 'T_FOR',
     325 => 'T_ENDFOR',
     326 => 'T_FOREACH',
     327 => 'T_ENDFOREACH',
     328 => 'T_DECLARE',
     329 => 'T_ENDDECLARE',
     330 => 'T_AS',
     331 => 'T_SWITCH',
     332 => 'T_ENDSWITCH',
     333 => 'T_CASE',
     334 => 'T_DEFAULT',
     335 => 'T_BREAK',
     336 => 'T_CONTINUE',
     337 => 'T_GOTO',
     338 => 'T_FUNCTION',
     339 => 'T_CONST',
     340 => 'T_RETURN',
     341 => 'T_TRY',
     342 => 'T_CATCH',
     343 => 'T_THROW',
     344 => 'T_USE',
     345 => 'T_GLOBAL',
     346 => 'T_PUBLIC',
     347 => 'T_PROTECTED',
     348 => 'T_PRIVATE',
     349 => 'T_FINAL',
     350 => 'T_ABSTRACT',
     351 => 'T_STATIC',
     352 => 'T_VAR',
     353 => 'T_UNSET',
     354 => 'T_ISSET',
     355 => 'T_EMPTY',
     356 => 'T_HALT_COMPILER',
     357 => 'T_CLASS',
     358 => 'T_INTERFACE',
     359 => 'T_EXTENDS',
     360 => 'T_IMPLEMENTS',
     361 => 'T_OBJECT_OPERATOR',
     362 => 'T_DOUBLE_ARROW',
     363 => 'T_LIST',
     364 => 'T_ARRAY',
     365 => 'T_CLASS_C',
     366 => 'T_METHOD_C',
     367 => 'T_FUNC_C',
     368 => 'T_LINE',
     369 => 'T_FILE',
     370 => 'T_COMMENT',
     371 => 'T_DOC_COMMENT',
     372 => 'T_OPEN_TAG',
     373 => 'T_OPEN_TAG_WITH_ECHO',
     374 => 'T_OPEN_TAG_FAKE',
     375 => 'T_CLOSE_TAG',
     376 => 'T_WHITESPACE',
     377 => 'T_START_HEREDOC',
     378 => 'T_END_HEREDOC',
     379 => 'T_HEREDOC',
     380 => 'T_DOLLAR_OPEN_CURLY_BRACES',
     381 => 'T_CURLY_OPEN',
     382 => 'T_PAAMAYIM_NEKUDOTAYIM',
     383 => 'T_BINARY_DOUBLE',
     384 => 'T_BINARY_HEREDOC',
     385 => 'T_NAMESPACE',
     386 => 'T_NS_C',
     387 => 'T_DIR',
     388 => 'T_NS_SEPARATOR',
     389 => 'T_XHP_WHITESPACE',
     390 => 'T_XHP_TEXT',
     391 => 'T_XHP_LT_DIV',
     392 => 'T_XHP_LT_DIV_GT',
     393 => 'T_XHP_ATTRIBUTE',
     394 => 'T_XHP_CATEGORY',
     395 => 'T_XHP_CHILDREN',
     396 => 'T_XHP_ANY',
     397 => 'T_XHP_EMPTY',
     398 => 'T_XHP_PCDATA',
     399 => 'T_XHP_COLON',
     400 => 'T_XHP_HYPHEN',
     401 => 'T_XHP_BOOLEAN',
     402 => 'T_XHP_NUMBER',
     403 => 'T_XHP_ARRAY',
     404 => 'T_XHP_STRING',
     405 => 'T_XHP_ENUM',
     406 => 'T_XHP_FLOAT',
     407 => 'T_XHP_REQUIRED',
     408 => 'T_XHP_ENTITY',
  );

  public static function newFromData($php_source) {
    $descriptorspec = array(
      0 => array("pipe", "r"), // stdin
      1 => array("pipe", "w"), // stdout
      2 => array("pipe", "w"), // stderr
    );

    if (!xhpast_is_available()) {
      echo xhpast_get_build_instructions();
      exit(1);
    }
    $process = proc_open(xhpast_get_binary_path(), $descriptorspec, $pipes);
    fwrite($pipes[0], $php_source);
    fclose($pipes[0]);
    $ret = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    proc_close($process);
    $data = json_decode($ret);
    if ($data) {
      return new self($data->tree, $data->stream, $php_source);
    } else {
      throw new Exception($stderr);
    }
  }

  public static function newFromDataAndResolvedExecFuture(
    $php_source,
    array $resolved) {

    list($err, $stdout, $stderr) = $resolved;
    if ($err) {
      if ($err == 1) {
        $matches = null;
        $is_syntax = preg_match(
          '/^XHPAST Parse Error: (.*) on line (\d+)/',
          $stderr,
          $matches);
        if ($is_syntax) {
          throw new XHPASTSyntaxErrorException($matches[2], $stderr);
        }
      }
      throw new Exception("XHPAST failed to parse file data {$err}: {$stderr}");
    }

    $data = json_decode($stdout, true);
    if (!is_array($data)) {
      throw new Exception("XHPAST: failed to decode tree.");
    }

    return new XHPASTTree($data['tree'], $data['stream'], $php_source);
  }

  public function __construct(array $tree, array $stream, $source) {
    $ii = 0;
    $offset = 0;

    foreach ($stream as $token) {
      $this->stream[$ii] = new XHPASTToken(
        $ii,
        $token[0],
        substr($source, $offset, $token[1]),
        $offset,
        $this);
      $offset += $token[1];
      ++$ii;
    }

    $this->rawSource = $source;
    $this->buildTree(array($tree));
  }

  /**
   * Unlink internal datastructures so that PHP's will garbage collect the tree.
   * This renders the object useless.
   *
   * @return void
   */
  public function dispose() {
    unset($this->tree);
    unset($this->stream);
  }

  public function getRootNode() {
    return $this->tree[0];
  }

  protected function buildTree(array $tree) {
    $ii = count($this->tree);
    $nodes = array();
    foreach ($tree as $node) {
      $this->tree[$ii] = new XHPASTNode($ii, $node, $this);
      $nodes[$ii] = $node;
      ++$ii;
    }
    foreach ($nodes as $node_id => $node) {
      if (isset($node[3])) {
        $children = $this->buildTree($node[3]);
        foreach ($children as $child) {
          $child->parentNode = $this->tree[$node_id];
        }
        $this->tree[$node_id]->children = $children;
      }
    }

    $result = array();
    foreach ($nodes as $key => $node) {
      $result[$key] = $this->tree[$key];
    }

    return $result;
  }

  public function getRawTokenStream() {
    return $this->stream;
  }

  public function renderAsText() {
    return $this->executeRenderAsText(array($this->getRootNode()), 0);
  }

  protected function executeRenderAsText($list, $depth) {
    $return = '';
    foreach ($list as $node) {
      if ($depth) {
        $return .= str_repeat('  ', $depth);
      }
      $return .= $node->getDescription()."\n";
      $return .= $this->executeRenderAsText($node->getChildren(), $depth + 1);
    }
    return $return;
  }


  public static function evalStaticString($string) {
    $string = '<?php '.rtrim($string, ';').';';
    $tree = XHPASTTree::newFromData($string);
    $statements = $tree->getRootNode()->selectDescendantsOfType('n_STATEMENT');
    if (count($statements) != 1) {
      throw new Exception("String does not parse into exactly one statement!");
    }
    // Return the first one, trying to use reset() with iterators ends in tears.
    foreach ($statements as $statement) {
      return $statement->evalStatic();
    }
  }

  public function getOffsetToLineNumberMap() {
    if ($this->lineMap === null) {
      $src = $this->rawSource;
      $len = strlen($src);
      $lno = 1;
      $map = array();
      for ($ii = 0; $ii < $len; ++$ii) {
        $map[$ii] = $lno;
        if ($src[$ii] == "\n") {
          ++$lno;
        }
      }
      $this->lineMap = $map;
    }
    return $this->lineMap;
  }

}
