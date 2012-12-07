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

window.globals ?= {}

globals.curvatureAnalysis = (arr, num) ->

    curves = new Array(arr.length)

    for i in [1...(arr.length - 1)]
        postSlope = (arr[i + 1].y - arr[i].y) / (arr[i + 1].x - arr[i].x)
        preSlope  = (arr[i].y - arr[i - 1].y) / (arr[i].x - arr[i - 1].x)

        curves[i] =
            curve: Math.abs(postSlope - preSlope)
            index: i

    curves.sort (a, b) -> (a.curve - b.curve)

    for curve in curves[0...(arr.length - num)]
        arr[curve.index].marked = true

    result = []

    for point, index in arr
        if not point.marked?
            result.push(point)
    
    result

globals.blur = (arr, w) ->

    range = arr[arr.length - 1].x - arr[0].x
    windowSize = (range / arr.length) * w

    res = []
    globals.extendObject(res, arr)
    window = []

    sumFunc = (a, b) -> a + b.y

    blurFunc = (win, center) ->
        weights = []

        for item in win
            weights.push 1.0 - (Math.abs(item.x - center) / windowSize)

        ws = weights.reduce (a, b) -> a + b
        result = 0

        for i in [0...win.length]
            result += (win[i].y * weights[i]) / ws
            
        result

    j = 0
    for i in [0...arr.length]

        while j < arr.length and (arr[j].x - arr[i].x) <= windowSize
            window.push arr[j]
            j += 1

        while (arr[i].x - window[0].x) > windowSize
            window.shift()
            
        res[i].y = blurFunc(window, arr[i].x)

    res

globals.dataReduce = (arr, xBounds, yBounds, xCells, yCells) ->

    xRange = xBounds.max - xBounds.min
    yRange = yBounds.max - yBounds.min

    xStep = xRange / xCells
    yStep = yRange / yCells

    cells = {}

    for dataPoint, index in arr

        x = Math.round((dataPoint.x - xBounds.min) / xStep)
        y = Math.round((dataPoint.y - yBounds.min) / yStep)

        if cells[x] is undefined or cells[x][y] is undefined
            cells[x] ?= {}
            cells[x][y] = true
        else
            arr[index].delete = true
            console.log 'del'

    res = arr.filter (dataPoint) -> not dataPoint.delete?

    console.log [xStep, yStep]
    console.log [arr.length, res.length]

    res
            