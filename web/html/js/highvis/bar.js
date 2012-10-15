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
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  window.Bar = (function(_super) {

    __extends(Bar, _super);

    function Bar(canvas) {
      this.canvas = canvas;
    }

    Bar.prototype.ANALYSISTYPE_MAX = 0;

    Bar.prototype.ANALYSISTYPE_MIN = 1;

    Bar.prototype.ANALYSISTYPE_MEAN = 2;

    Bar.prototype.ANALYSISTYPE_MEDIAN = 3;

    Bar.prototype.ANALYSISTYPE_COUNT = 4;

    Bar.prototype.ANALYSISTYPE_TOTAL = 5;

    Bar.prototype.analysisTypeNames = ["Max", "Min", "Mean", "Median", "Count", "Total"];

    Bar.prototype.analysisType = 0;

    Bar.prototype.sortField = null;

    Bar.prototype.buildOptions = function() {
      var self;
      Bar.__super__.buildOptions.call(this);
      self = this;
      this.chartOptions;
      return $.extend(true, this.chartOptions, {
        chart: {
          type: "column"
        },
        title: {
          text: ""
        },
        legend: {
          symbolWidth: 0
        },
        tooltip: {
          formatter: function() {
            var str;
            str = "<div style='width:100%;text-align:center;color:" + this.series.color + ";margin-bottom:5px'> " + this.point.name + "</div>";
            str += "<table>";
            str += "<tr><td>" + this.x + " (" + self.analysisTypeNames[self.analysisType] + "):</td><td><strong>" + this.y + "</strong></td></tr>";
            return str += "</table>";
          },
          useHTML: true
        },
        yAxis: {
          type: globals.logY === 1 ? 'logarithmic' : 'linear'
        }
      });
    };

    Bar.prototype.update = function() {
      var fieldIndex, fieldSortedGroupIDValuePairs, fieldSortedGroupIDs, groupID, groupIndex, groupName, groupValue, options, order, ret, selection, tempGroupIDValuePairs, visibleCategories, _i, _len;
      Bar.__super__.update.call(this);
      visibleCategories = (function() {
        var _i, _len, _ref, _results;
        _ref = data.normalFields;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          selection = _ref[_i];
          if (__indexOf.call(globals.fieldSelection, selection) >= 0) {
            _results.push(data.fields[selection].fieldName);
          }
        }
        return _results;
      })();
      this.chart.xAxis[0].setCategories(visibleCategories, false);
      while (this.chart.series.length > data.normalFields.length) {
        this.chart.series[this.chart.series.length - 1].remove(false);
      }
      /* ---
      */

      tempGroupIDValuePairs = (function() {
        var _i, _len, _ref, _results;
        _ref = data.groups;
        _results = [];
        for (groupIndex = _i = 0, _len = _ref.length; _i < _len; groupIndex = ++_i) {
          groupName = _ref[groupIndex];
          if (__indexOf.call(globals.groupSelection, groupIndex) >= 0) {
            switch (this.analysisType) {
              case this.ANALYSISTYPE_MAX:
                _results.push([groupIndex, data.getMax(this.sortField, groupIndex)]);
                break;
              case this.ANALYSISTYPE_MIN:
                _results.push([groupIndex, data.getMin(this.sortField, groupIndex)]);
                break;
              case this.ANALYSISTYPE_MEAN:
                _results.push([groupIndex, data.getMean(this.sortField, groupIndex)]);
                break;
              case this.ANALYSISTYPE_MEDIAN:
                _results.push([groupIndex, data.getMedian(this.sortField, groupIndex)]);
                break;
              case this.ANALYSISTYPE_COUNT:
                _results.push([groupIndex, data.getCount(this.sortField, groupIndex)]);
                break;
              case this.ANALYSISTYPE_TOTAL:
                _results.push([groupIndex, data.getTotal(this.sortField, groupIndex)]);
                break;
              default:
                _results.push(void 0);
            }
          }
        }
        return _results;
      }).call(this);
      if (this.sortField !== null) {
        fieldSortedGroupIDValuePairs = tempGroupIDValuePairs.sort(function(a, b) {
          return a[1] - b[1];
        });
        fieldSortedGroupIDs = (function() {
          var _i, _len, _ref, _results;
          _results = [];
          for (_i = 0, _len = fieldSortedGroupIDValuePairs.length; _i < _len; _i++) {
            _ref = fieldSortedGroupIDValuePairs[_i], groupID = _ref[0], groupValue = _ref[1];
            _results.push(groupID);
          }
          return _results;
        })();
      } else {
        fieldSortedGroupIDs = (function() {
          var _i, _len, _ref, _results;
          _ref = data.groups;
          _results = [];
          for (groupID = _i = 0, _len = _ref.length; _i < _len; groupID = ++_i) {
            groupName = _ref[groupID];
            _results.push(groupID);
          }
          return _results;
        })();
      }
      /* ---
      */

      for (order = _i = 0, _len = fieldSortedGroupIDs.length; _i < _len; order = ++_i) {
        groupIndex = fieldSortedGroupIDs[order];
        if (!(__indexOf.call(globals.groupSelection, groupIndex) >= 0)) {
          continue;
        }
        options = {
          showInLegend: false,
          color: globals.colors[groupIndex % globals.colors.length],
          name: data.groups[groupIndex],
          index: order
        };
        options.data = (function() {
          var _j, _len1, _ref, _results;
          _ref = data.normalFields;
          _results = [];
          for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
            fieldIndex = _ref[_j];
            if (__indexOf.call(globals.fieldSelection, fieldIndex) >= 0) {
              switch (this.analysisType) {
                case this.ANALYSISTYPE_MAX:
                  _results.push(ret = {
                    y: data.getMax(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                case this.ANALYSISTYPE_MIN:
                  _results.push(ret = {
                    y: data.getMin(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                case this.ANALYSISTYPE_MEAN:
                  _results.push(ret = {
                    y: data.getMean(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                case this.ANALYSISTYPE_MEDIAN:
                  _results.push(ret = {
                    y: data.getMedian(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                case this.ANALYSISTYPE_COUNT:
                  _results.push(ret = {
                    y: data.getCount(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                case this.ANALYSISTYPE_TOTAL:
                  _results.push(ret = {
                    y: data.getTotal(fieldIndex, groupIndex),
                    name: data.groups[groupIndex]
                  });
                  break;
                default:
                  _results.push(void 0);
              }
            }
          }
          return _results;
        }).call(this);
        this.chart.addSeries(options, false);
      }
      return this.chart.redraw();
    };

    Bar.prototype.buildLegendSeries = function() {
      var count, dummy, field, fieldIndex, _i, _len, _ref, _results;
      count = -1;
      _ref = data.fields;
      _results = [];
      for (fieldIndex = _i = 0, _len = _ref.length; _i < _len; fieldIndex = ++_i) {
        field = _ref[fieldIndex];
        if (!(__indexOf.call(data.normalFields, fieldIndex) >= 0)) {
          continue;
        }
        count += 1;
        _results.push(dummy = {
          legendIndex: fieldIndex,
          data: [],
          color: '#000',
          visible: __indexOf.call(globals.fieldSelection, fieldIndex) >= 0 ? true : false,
          name: field.fieldName,
          type: 'area',
          xAxis: 1
        });
      }
      return _results;
    };

    Bar.prototype.drawToolControls = function() {
      var controls, fieldID, fieldName, tempFields, type, typestring, _i, _j, _len, _len1, _ref, _ref1, _ref2,
        _this = this;
      controls = '<div id="toolControl" class="vis_controls">';
      controls += "<h3 class='clean_shrink'><a href='#'>Tools:</a></h3>";
      controls += "<div class='outer_control_div'>";
      controls += "<div class='inner_control_div'>";
      controls += 'Sort by: <select class="sortField control_select">';
      tempFields = (function() {
        var _i, _len, _ref, _results;
        _ref = data.normalFields;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          fieldID = _ref[_i];
          _results.push([fieldID, data.fields[fieldID].fieldName]);
        }
        return _results;
      })();
      tempFields = [].concat([[null, 'Group Name']], tempFields);
      for (_i = 0, _len = tempFields.length; _i < _len; _i++) {
        _ref = tempFields[_i], fieldID = _ref[0], fieldName = _ref[1];
        controls += "<option value='" + fieldID + "'" + (this.sortField === fieldID ? ' selected' : '') + ">" + fieldName + "</option>";
      }
      controls += '</select></div><br>';
      controls += "<h4 class='clean_shrink'>Analysis Type</h4>";
      _ref1 = this.analysisTypeNames;
      for (type = _j = 0, _len1 = _ref1.length; _j < _len1; type = ++_j) {
        typestring = _ref1[type];
        controls += '<div class="inner_control_div">';
        controls += "<input class='analysisType' type='radio' name='analysisTypeSelector' value='" + type + "' " + (type === this.analysisType ? 'checked' : '') + "> " + typestring + "</input><br>";
        controls += '</div>';
      }
      controls += "<h4 class='clean_shrink'>Other</h4>";
      if (data.logSafe === 1) {
        controls += '<div class="inner_control_div">';
        controls += "<input class='logY_box' type='checkbox' name='tooltip_selector' " + (globals.logY === 1 ? 'checked' : '') + "/> Logarithmic Y Axis ";
        controls += "</div>";
      }
      controls += '</div></div>';
      /* ---
      */

      ($('#controldiv')).append(controls);
      ($('.analysisType')).change(function(e) {
        _this.analysisType = Number(e.target.value);
        return _this.delayedUpdate();
      });
      ($('.sortField')).change(function(e) {
        _this.sortField = Number(e.target.value);
        return _this.delayedUpdate();
      });
      ($('.logY_box')).click(function(e) {
        globals.logY = (globals.logY + 1) % 2;
        return _this.start();
      });
      if ((_ref2 = globals.toolsOpen) == null) {
        globals.toolsOpen = 0;
      }
      ($('#toolControl')).accordion({
        collapsible: true,
        active: globals.toolsOpen
      });
      return ($('#toolControl > h3')).click(function() {
        return globals.toolsOpen = (globals.toolsOpen + 1) % 2;
      });
    };

    Bar.prototype.drawControls = function() {
      Bar.__super__.drawControls.call(this);
      this.drawGroupControls();
      this.drawToolControls();
      return this.drawSaveControls();
    };

    return Bar;

  })(BaseHighVis);

  if (__indexOf.call(data.relVis, "Bar") >= 0) {
    globals.bar = new Bar('bar_canvas');
  } else {
    globals.bar = new DisabledVis("bar_canvas");
  }

}).call(this);
