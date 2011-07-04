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
 */


/**
 * @group xhpast
 */
function xhpast_is_available() {
  static $available;
  if ($available === null) {
    $available = false;
    $bin = xhpast_get_binary_path();
    if (is_executable($bin)) {
      $ret = exec(escapeshellcmd($bin) . ' --version');
      if ($ret) {
        $version = trim($ret);
        if ($version === "xhpast version 0.59") {
          $available = true;
        }
      }
    }
  }
  return $available;
}


/**
 * @group xhpast
 */
function xhpast_get_binary_path() {
  return __DIR__ . '/xhpast';
}


/**
 * @group xhpast
 */
function xhpast_get_build_instructions() {
  $path = dirname(xhpast_get_binary_path()) . '/parser';
  return <<<EOHELP
Your version of 'xhpast' is unbuilt or out of date. Run this script to build it:

  \$ cd {$path} ; make install

EOHELP;
}


