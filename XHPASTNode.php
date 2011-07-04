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
class XHPASTNode {

  protected $id;
  protected $l;
  protected $r;
  protected $typeID;
  protected $tree;

  // These are public only as a microoptimization to make tree construction
  // faster; do not access them directly.
  public $children = array();
  public $parentNode;

  public static $NODES = array(
    9000 => 'n_PROGRAM',
    9001 => 'n_SYMBOL_NAME',
    9002 => 'n_HALT_COMPILER',
    9003 => 'n_NAMESPACE',
    9004 => 'n_STATEMENT',
    9005 => 'n_EMPTY',
    9006 => 'n_STATEMENT_LIST',
    9007 => 'n_OPEN_TAG',
    9008 => 'n_CLOSE_TAG',
    9009 => 'n_USE_LIST',
    9010 => 'n_USE',
    9011 => 'n_CONSTANT_DECLARATION_LIST',
    9012 => 'n_CONSTANT_DECLARATION',
    9013 => 'n_STRING',
    9014 => 'n_LABEL',
    9015 => 'n_CONDITION_LIST',
    9016 => 'n_CONTROL_CONDITION',
    9017 => 'n_IF',
    9018 => 'n_ELSEIF',
    9019 => 'n_ELSE',
    9020 => 'n_WHILE',
    9021 => 'n_DO_WHILE',
    9022 => 'n_FOR',
    9023 => 'n_FOR_EXPRESSION',
    9024 => 'n_SWITCH',
    9025 => 'n_BREAK',
    9026 => 'n_CONTINUE',
    9027 => 'n_RETURN',
    9028 => 'n_GLOBAL_DECLARATION_LIST',
    9029 => 'n_GLOBAL_DECLARATION',
    9030 => 'n_STATIC_DECLARATION_LIST',
    9031 => 'n_STATIC_DECLARATION',
    9032 => 'n_ECHO_LIST',
    9033 => 'n_ECHO',
    9034 => 'n_INLINE_HTML',
    9035 => 'n_UNSET_LIST',
    9036 => 'n_UNSET',
    9037 => 'n_FOREACH',
    9038 => 'n_FOREACH_EXPRESSION',
    9039 => 'n_THROW',
    9040 => 'n_GOTO',
    9041 => 'n_TRY',
    9042 => 'n_CATCH_LIST',
    9043 => 'n_CATCH',
    9044 => 'n_DECLARE',
    9045 => 'n_DECLARE_DECLARATION_LIST',
    9046 => 'n_DECLARE_DECLARATION',
    9047 => 'n_VARIABLE',
    9048 => 'n_REFERENCE',
    9049 => 'n_VARIABLE_REFERENCE',
    9050 => 'n_FUNCTION_DECLARATION',
    9051 => 'n_CLASS_DECLARATION',
    9052 => 'n_CLASS_ATTRIBUTES',
    9053 => 'n_EXTENDS',
    9054 => 'n_EXTENDS_LIST',
    9055 => 'n_IMPLEMENTS_LIST',
    9056 => 'n_INTERFACE_DECLARATION',
    9057 => 'n_CASE',
    9058 => 'n_DEFAULT',
    9059 => 'n_DECLARATION_PARAMETER_LIST',
    9060 => 'n_DECLARATION_PARAMETER',
    9061 => 'n_TYPE_NAME',
    9062 => 'n_VARIABLE_VARIABLE',
    9063 => 'n_CLASS_MEMBER_DECLARATION_LIST',
    9064 => 'n_CLASS_MEMBER_DECLARATION',
    9065 => 'n_CLASS_CONSTANT_DECLARATION_LIST',
    9066 => 'n_CLASS_CONSTANT_DECLARATION',
    9067 => 'n_METHOD_DECLARATION',
    9068 => 'n_METHOD_MODIFIER_LIST',
    9069 => 'n_FUNCTION_MODIFIER_LIST',
    9070 => 'n_CLASS_MEMBER_MODIFIER_LIST',
    9071 => 'n_EXPRESSION_LIST',
    9072 => 'n_LIST',
    9073 => 'n_ASSIGNMENT',
    9074 => 'n_NEW',
    9075 => 'n_UNARY_PREFIX_EXPRESSION',
    9076 => 'n_UNARY_POSTFIX_EXPRESSION',
    9077 => 'n_BINARY_EXPRESSION',
    9078 => 'n_TERNARY_EXPRESSION',
    9079 => 'n_CAST_EXPRESSION',
    9080 => 'n_CAST',
    9081 => 'n_OPERATOR',
    9082 => 'n_ARRAY_LITERAL',
    9083 => 'n_EXIT_EXPRESSION',
    9084 => 'n_BACKTICKS_EXPRESSION',
    9085 => 'n_LEXICAL_VARIABLE_LIST',
    9086 => 'n_NUMERIC_SCALAR',
    9087 => 'n_STRING_SCALAR',
    9088 => 'n_MAGIC_SCALAR',
    9089 => 'n_CLASS_STATIC_ACCESS',
    9090 => 'n_CLASS_NAME',
    9091 => 'n_MAGIC_CLASS_KEYWORD',
    9092 => 'n_OBJECT_PROPERTY_ACCESS',
    9093 => 'n_ARRAY_VALUE_LIST',
    9094 => 'n_ARRAY_VALUE',
    9095 => 'n_CALL_PARAMETER_LIST',
    9096 => 'n_VARIABLE_EXPRESSION',
    9097 => 'n_INCLUDE_FILE',
    9098 => 'n_HEREDOC',
    9099 => 'n_FUNCTION_CALL',
    9100 => 'n_INDEX_ACCESS',
    9101 => 'n_ASSIGNMENT_LIST',
    9102 => 'n_METHOD_CALL',
    9103 => 'n_XHP_TAG',
    9104 => 'n_XHP_TAG_OPEN',
    9105 => 'n_XHP_TAG_CLOSE',
    9106 => 'n_XHP_TEXT',
    9107 => 'n_XHP_EXPRESSION',
    9108 => 'n_XHP_ATTRIBUTE_LIST',
    9109 => 'n_XHP_ATTRIBUTE',
    9110 => 'n_XHP_LITERAL',
    9111 => 'n_XHP_ATTRIBUTE_LITERAL',
    9112 => 'n_XHP_ATTRIBUTE_EXPRESSION',
    9113 => 'n_XHP_NODE_LIST',
    9114 => 'n_CONCATENATION_LIST',
    9115 => 'n_PARENTHETICAL_EXPRESSION',
    9116 => 'n_YIELD',
    9117 => 'n_YIELD_EXPRESSION',
  );

  public function __construct($id, array $data, XHPASTTree $tree) {
    $this->id = $id;
    $this->typeID = $data[0];
    if (isset($data[1])) {
      $this->l = $data[1];
    } else {
      $this->l = -1;
    }
    if (isset($data[2])) {
      $this->r = $data[2];
    } else {
      $this->r = -1;
    }
    $this->tree = $tree;
  }

  public function getParentNode() {
    return $this->parentNode;
  }

  public function getID() {
    return $this->id;
  }

  public function getTypeID() {
    return $this->typeID;
  }

  public function getTypeName() {
    $type_id = $this->getTypeID();
    if (empty(self::$NODES[$type_id])) {
      throw new Exception("No type name for node type ID '{$type_id}'.");
    }

    return self::$NODES[$type_id];
  }

  public function getChildren() {
    return $this->children;
  }

  public function getChildOfType($index, $type) {
    $child = $this->getChildByIndex($index);
    if ($child->getTypeName() != $type) {
      throw new Exception(
        "Child in position '{$index}' is not of type '{$type}': ".
        $this->getDescription());
    }

    return $child;
  }

  public function getChildByIndex($index) {
    $child = idx(array_values($this->children), $index);
    if (!$child) {
      throw new Exception(
        "No child with index '{$index}'.");
    }
    return $child;
  }

  public function selectDescendantsOfType($type_name) {
    $type = $this->getTypeIDFromTypeName($type_name);
    return XHPASTNodeList::newFromTreeAndNodes(
      $this->tree,
      $this->executeSelectDescendantsOfType($this, $type));
  }

  protected function executeSelectDescendantsOfType($node, $type) {
    $results = array();
    foreach ($node->getChildren() as $id => $child) {
      if ($child->getTypeID() == $type) {
        $results[$id] = $child;
      }
      $results += $this->executeSelectDescendantsOfType($child, $type);
    }
    return $results;
  }

  public function getTokens() {
    if ($this->l == -1 || $this->r == -1) {
      return array();
    }
    $tokens = $this->tree->getRawTokenStream();
    $result = array();
    foreach (range($this->l, $this->r) as $token_id) {
      $result[$token_id] = $tokens[$token_id];
    }
    return $result;
  }

  public function getConcreteString() {
    $values = array();
    foreach ($this->getTokens() as $token) {
      $values[] = $token->getValue();
    }
    return implode('', $values);
  }

  public function getSemanticString() {
    $tokens = $this->getTokens();
    foreach ($tokens as $id => $token) {
      if ($token->isComment()) {
        unset($tokens[$id]);
      }
    }
    return implode('', mpull($tokens, 'getValue'));
  }

  public function getDescription() {
    $concrete = $this->getConcreteString();
    if (strlen($concrete) > 75) {
      $concrete = substr($concrete, 0, 36).'...'.substr($concrete, -36);
    }

    $concrete = addcslashes($concrete, "\\\n\"");

    return 'a node of type '.$this->getTypeName().': "'.$concrete.'"';
  }

  protected function getTypeIDFromTypeName($type_name) {
    static $node_types;
    if (empty($node_types)) {
      $node_types = xhp_parser_node_constants();
      $node_types = array_flip($node_types);
    }

    if (empty($node_types[$type_name])) {
      throw new Exception("Unknown XHPAST Node type name '{$type_name}'!");
    }

    return $node_types[$type_name];
  }

  public function getOffset() {
    $first_token = idx($this->tree->getRawTokenStream(), $this->l);
    if (!$first_token) {
      return null;
    }
    return $first_token->getOffset();
  }

  public function isStaticScalar() {
    return ($this->getTypeName() == 'n_STRING_SCALAR' ||
            $this->getTypeName() == 'n_NUMERIC_SCALAR');
  }

  public function getSurroundingNonsemanticTokens() {
    $before = array();
    $after  = array();

    $tokens = $this->tree->getRawTokenStream();

    if ($this->l != -1) {
      $before = $tokens[$this->l]->getNonsemanticTokensBefore();
    }

    if ($this->r != -1) {
      $after = $tokens[$this->r]->getNonsemanticTokensAfter();
    }

    return array($before, $after);
  }

  public function getDocblockToken() {
    if ($this->l == -1) {
      return null;
    }
    $tokens = $this->tree->getRawTokenStream();

    for ($ii = $this->l - 1; $ii >= 0; $ii--) {
      if ($tokens[$ii]->getTypeName() == 'T_DOC_COMMENT') {
        return $tokens[$ii];
      }
      if (!$tokens[$ii]->isAnyWhitespace()) {
        return null;
      }
    }

    return null;
  }

  public function evalStatic() {
    switch ($this->getTypeName()) {
      case 'n_STATEMENT':
        return $this->getChildByIndex(0)->evalStatic();
        break;
      case 'n_STRING_SCALAR':
        return (string)$this->getStringLiteralValue();
      case 'n_NUMERIC_SCALAR':
        $value = $this->getSemanticString();
        if (preg_match('/^0x/i', $value)) {
          // Hex
          return (int)base_convert(substr($value, 2), 16, 10);
        } else if (preg_match('/^0\d+$/i', $value)) {
          // Octal
          return (int)base_convert(substr($value, 1),  8, 10);
        } else if (preg_match('/^\d+$/', $value)) {
          return (int)$value;
        } else {
          return (double)$value;
        }
        break;
      case 'n_SYMBOL_NAME':
        $value = $this->getSemanticString();
        if ($value == 'INF') {
          return INF;
        }
        switch (strtolower($value)) {
          case 'true':
            return true;
          case 'false':
            return false;
          case 'null':
            return null;
          default:
            throw new Exception('Unrecognized symbol name.');
        }
        break;
      case 'n_UNARY_PREFIX_EXPRESSION':
        $operator = $this->getChildOfType(0, 'n_OPERATOR');
        $operand = $this->getChildByIndex(1);
        switch ($operator->getSemanticString()) {
          case '-':
            return -$operand->evalStatic();
            break;
          case '+':
            return $operand->evalStatic();
            break;
          default:
            throw new Exception("Unexpected operator in static expression.");
        }
        break;
      case 'n_ARRAY_LITERAL':
        $result = array();
        $values = $this->getChildOfType(0, 'n_ARRAY_VALUE_LIST');
        foreach ($values->getChildren() as $child) {
          $key = $child->getChildByIndex(0);
          $val = $child->getChildByIndex(1);
          if ($key->getTypeName() == 'n_EMPTY') {
            $result[] = $val->evalStatic();
          } else {
            $result[$key->evalStatic()] = $val->evalStatic();
          }
        }
        return $result;
      default:
        throw new Exception("Unexpected node.");
    }
  }

  public function getStringLiteralValue() {
    if ($this->getTypeName() != 'n_STRING_SCALAR') {
      return null;
    }

    $value = $this->getSemanticString();
    $type  = $value[0];
    $value = substr($value, 1, -1);
    $esc   = false;
    $len   = strlen($value);
    $out   = '';

    if ($type == "'") {
      // Single quoted strings treat everything as a literal except "\\" and
      // "\'".
      return str_replace(
        array('\\\\', '\\\''),
        array('\\',   "'"),
        $value);
    }

    // Double quoted strings treat "\X" as a literal if X isn't specifically
    // a character which needs to be escaped -- e.g., "\q" and "\'" are
    // literally "\q" and "\'". stripcslashes() is too aggressive, so find
    // all these under-escaped backslashes and escape them.

    for ($ii = 0; $ii < $len; $ii++) {
      $c = $value[$ii];
      if ($esc) {
        $esc = false;
        switch ($c) {
          case 'x':
            $u = isset($value[$ii + 1]) ? $value[$ii + 1] : null;
            if (!preg_match('/^[a-z0-9]/i', $u)) {
              // PHP treats \x followed by anything which is not a hex digit
              // as a literal \x.
              $out .= '\\\\'.$c;
              break;
            }
          case 'n':
          case 'r':
          case 'f':
          case 'v':
          case '"':
          case '$':
          case 't':
          case '0':
          case '1':
          case '2':
          case '3':
          case '4':
          case '5':
          case '6':
          case '7':
            $out .= '\\'.$c;
            break;
          default:
            $out .= '\\\\'.$c;
            break;
        }
      } else if ($c == '\\') {
        $esc = true;
      } else {
        $out .= $c;
      }
    }

    return stripcslashes($out);
  }

  public function getLineNumber() {
    return idx($this->tree->getOffsetToLineNumberMap(), $this->getOffset());
  }


}
