/*
 * Copyright 2012 Massive//Media.
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

#include "ast.hpp"
#include <vector>
#include <string>
#include <iostream>
#include <sstream>
#include <fstream>
using namespace std;

int xhpastparse(void*, xhpast::Node **);

/**
 * Simple container for result of syntax check
 */
struct SyntaxCheckResult {
    bool success;
    int line_number;
    string error;
    SyntaxCheckResult(bool success, int line_number=0, string error=""): success(success), line_number(line_number), error(error) {}
};

/**
 * Check the given string for correct PHP syntax
 *
 * @return bool success or failure?
 */
SyntaxCheckResult php_syntax_check_string(std::string &in) {

  char *buffer;
  in.reserve(in.size() + 1);
  buffer = const_cast<char*>(in.c_str());
  buffer[in.size() + 1] = 0; // need double NULL for scan_buffer

  void* scanner;
  yy_extra_type extra;
  extra.idx_expr = true;//flags.idx_expr;
  extra.include_debug = true;//flags.include_debug;
  extra.insert_token = 0;//flags.eval ? T_OPEN_TAG_FAKE : 0;
  extra.short_tags = true;//flags.short_tags;
  extra.asp_tags = false;//flags.asp_tags;

  xhpast::Node *root = NULL;

  xhpastlex_init(&scanner);
  xhpastset_extra(&extra, scanner);
  xhpast_scan_buffer(buffer, in.size() + 2, scanner);
  xhpastparse(scanner, &root);
  xhpastlex_destroy(scanner);

  if (extra.terminated) {
    return SyntaxCheckResult(false, (int)extra.lineno, extra.error);
  }

  return SyntaxCheckResult(true);
}

/**
 * Check the given file for correct PHP syntax
 *
 * @return bool success or failure?
 */
SyntaxCheckResult php_syntax_check_file(const std::string &filename) {
  ifstream inputFile;
  inputFile.open(filename.c_str());
  std::stringbuf sb;
  inputFile >> noskipws >> &sb;
  inputFile.close();
  std::string buffer = sb.str();

  return php_syntax_check_string(buffer);
}

int main(int argc, char* argv[]) {

  if (argc <= 1) {
    cerr << "Warning: php-syntaxcheck requires at least one argument" << endl;
    return 0;
  }

  string filename(argv[1]);
  SyntaxCheckResult result = php_syntax_check_file(filename);

  if (result.success) {
    cout << filename << ": " << "ok" << endl;
  }
  else {
    cout << filename << ": " << "XHPAST Parse Error \"" << result.error << "\" on line " << result.line_number << endl;
  }

}


