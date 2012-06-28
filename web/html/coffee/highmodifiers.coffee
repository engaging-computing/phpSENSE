###
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
###

###
Selects an array of data from the given field index.
if 'nans' is true then datapoints with NaN values in the given field will be included.
'filterFunc' is a boolean filter that must be passed (true) for a datapoint to be included.
###
data.selector = (fieldIndex, nans = false, filterFunc = ((dp) -> true)) ->
    newFilterFunc = if nans
        filterFunc
    else 
        (dp) -> (filterFunc dp) and (not isNaN dp[fieldIndex]) and (dp[fieldIndex] isnt null)

    rawData = data.dataPoints.filter newFilterFunc
    rawData.map (dp) -> dp[fieldIndex]

###
Gets the maximum (numeric) value for the given field index.
All included datapoints must pass the given filter (defaults to all datapoints).
###
data.getMax = (fieldIndex, filterFunc = (dp) -> true) ->
    rawData = data.selector(fieldIndex, filterFunc)
    rawData.reduce (a,b) -> Math.max(a,b)

###
Gets the minimum (numeric) value for the given field index.
All included datapoints must pass the given filter (defaults to all datapoints).
###
data.getMin = (fieldIndex, filterFunc = (dp) -> true) ->
    rawData = data.selector(fieldIndex, filterFunc)
    rawData.reduce (a,b) -> Math.min(a,b)

###
Gets the mean (numeric) value for the given field index.
All included datapoints must pass the given filter (defaults to all datapoints).
###
data.getMean = (fieldIndex, filterFunc = (dp) -> true) ->
    rawData = data.selector(fieldIndex, filterFunc)
    (rawData.reduce (a,b) -> a + b) / rawData.length

###
Gets the median (numeric) value for the given field index.
All included datapoints must pass the given filter (defaults to all datapoints).
###
data.getMedian = (fieldIndex, filterFunc = (dp) -> true) ->
    rawData = data.selector(fieldIndex, filterFunc)
    rawData.sort()
    
    mid = Math.floor (rawData.length / 2)
    
    if rawData.length % 2
        return rawData[mid]
    else
        return (rawData[mid - 1] + rawData[mid]) / 2.0