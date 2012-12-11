// Generated by CoffeeScript 1.3.3

/*
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 *
*/


(function() {
  var _ref;

  if ((_ref = window.globals) == null) {
    window.globals = {};
  }

  globals.curvatureAnalysis = function(arr, num) {
    var curve, curves, i, index, point, postSlope, preSlope, result, _i, _j, _k, _len, _len1, _ref1, _ref2;
    curves = new Array(arr.length);
    for (i = _i = 1, _ref1 = arr.length - 1; 1 <= _ref1 ? _i < _ref1 : _i > _ref1; i = 1 <= _ref1 ? ++_i : --_i) {
      postSlope = (arr[i + 1].y - arr[i].y) / (arr[i + 1].x - arr[i].x);
      preSlope = (arr[i].y - arr[i - 1].y) / (arr[i].x - arr[i - 1].x);
      curves[i] = {
        curve: Math.abs(postSlope - preSlope),
        index: i
      };
    }
    curves.sort(function(a, b) {
      return a.curve - b.curve;
    });
    _ref2 = curves.slice(0, arr.length - num);
    for (_j = 0, _len = _ref2.length; _j < _len; _j++) {
      curve = _ref2[_j];
      arr[curve.index].marked = true;
    }
    result = [];
    for (index = _k = 0, _len1 = arr.length; _k < _len1; index = ++_k) {
      point = arr[index];
      if (!(point.marked != null)) {
        result.push(point);
      }
    }
    return result;
  };

  globals.blur = function(arr, w) {
    var blurFunc, i, j, range, res, sumFunc, window, windowSize, _i, _ref1;
    range = arr[arr.length - 1].x - arr[0].x;
    windowSize = (range / arr.length) * w;
    res = [];
    globals.extendObject(res, arr);
    window = [];
    sumFunc = function(a, b) {
      return a + b.y;
    };
    blurFunc = function(win, center) {
      var i, item, result, weights, ws, _i, _j, _len, _ref1;
      weights = [];
      for (_i = 0, _len = win.length; _i < _len; _i++) {
        item = win[_i];
        weights.push(1.0 - (Math.abs(item.x - center) / windowSize));
      }
      ws = weights.reduce(function(a, b) {
        return a + b;
      });
      result = 0;
      for (i = _j = 0, _ref1 = win.length; 0 <= _ref1 ? _j < _ref1 : _j > _ref1; i = 0 <= _ref1 ? ++_j : --_j) {
        result += (win[i].y * weights[i]) / ws;
      }
      return result;
    };
    j = 0;
    for (i = _i = 0, _ref1 = arr.length; 0 <= _ref1 ? _i < _ref1 : _i > _ref1; i = 0 <= _ref1 ? ++_i : --_i) {
      while (j < arr.length && (arr[j].x - arr[i].x) <= windowSize) {
        window.push(arr[j]);
        j += 1;
      }
      while ((arr[i].x - window[0].x) > windowSize) {
        window.shift();
      }
      res[i].y = blurFunc(window, arr[i].x);
    }
    return res;
  };

  /*
  Clips data array arr using the Cohen-Sutherland algorithm
  See http://en.wikipedia.org/wiki/Cohen%E2%80%93Sutherland
  */


  globals.clip = function(arr, xBounds, yBounds) {
    var BOTTOM, LEFT, RIGHT, TOP, coder, cur, index, prev, test, _i, _ref1;
    LEFT = 1;
    RIGHT = 2;
    BOTTOM = 4;
    TOP = 8;
    coder = function(x, y) {
      var code;
      code = 0;
      if (x < xBounds.min) {
        code |= LEFT;
      } else if (x > xBounds.max) {
        code |= RIGHT;
      }
      if (y < yBounds.min) {
        code |= BOTTOM;
      } else if (y > yBounds.max) {
        code |= TOP;
      }
      return code;
    };
    test = function(x1, y1, x2, y2) {
      var code1, code2, outcode, x, y;
      code1 = coder(x1, y1);
      code2 = coder(x2, y2);
      while (true) {
        if (!(code1 | code2)) {
          return true;
        } else if (code1 & code2) {
          return false;
        } else {
          x = y = 0;
          outcode = code1 ? code1 : code2;
          if (outcode & TOP) {
            x = x1 + (x2 - x1) * (yBounds.max - y1) / (y2 - y1);
            y = yBounds.max;
          } else if (outcode & BOTTOM) {
            x = x1 + (x2 - x1) * (yBounds.min - y1) / (y2 - y1);
            y = yBounds.min;
          } else if (outcode & RIGHT) {
            y = y1 + (y2 - y1) * (xBounds.max - x1) / (x2 - x1);
            x = xBounds.max;
          } else if (outcode & LEFT) {
            y = y1 + (y2 - y1) * (xBounds.min - x1) / (x2 - x1);
            x = xBounds.min;
          }
          if (outcode === code1) {
            x1 = x;
            y1 = y;
            code1 = coder(x1, y1);
          } else {
            x2 = x;
            y2 = y;
            code2 = coder(x2, y2);
          }
        }
      }
    };
    prev = false;
    for (index = _i = 1, _ref1 = arr.length; 1 <= _ref1 ? _i < _ref1 : _i > _ref1; index = 1 <= _ref1 ? ++_i : --_i) {
      cur = test(arr[index - 1].x, arr[index - 1].y, arr[index].x, arr[index].y);
      if ((!prev) && (!cur)) {
        arr[index]["delete"] = true;
      }
      prev = cur;
    }
    if (!prev) {
      arr[arr.length - 1]["delete"] = true;
    }
    return arr.filter(function(dataPoint) {
      return !(dataPoint["delete"] != null);
    });
  };

  globals.dataReduce = function(arr, xBounds, yBounds, xCells, yCells, target) {
    var cells, dataPoint, index, res, x, xRange, xStep, y, yRange, yStep, _i, _len, _ref1;
    arr = globals.clip(arr, xBounds, yBounds);
    xRange = xBounds.max - xBounds.min;
    yRange = yBounds.max - yBounds.min;
    xStep = xRange / xCells;
    yStep = yRange / yCells;
    cells = {};
    for (index = _i = 0, _len = arr.length; _i < _len; index = ++_i) {
      dataPoint = arr[index];
      x = Math.round((dataPoint.x - xBounds.min) / xStep);
      y = Math.round((dataPoint.y - yBounds.min) / yStep);
      if (cells[x] === void 0 || cells[x][y] === void 0) {
        if ((_ref1 = cells[x]) == null) {
          cells[x] = {};
        }
        cells[x][y] = true;
      } else {
        arr[index]["delete"] = true;
      }
    }
    res = arr.filter(function(dataPoint) {
      return !(dataPoint["delete"] != null);
    });
    if (res.length > target) {
      return globals.dataReduce(res, xBounds, yBounds, xCells / 2, yCells / 2, target);
    }
    return res;
  };

}).call(this);
