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

###
Clips data array arr using the Cohen-Sutherland algorithm
See http://en.wikipedia.org/wiki/Cohen%E2%80%93Sutherland
###
globals.clip = (arr, xBounds, yBounds) ->

    if arr.length <= 1
      return arr

    LEFT   = 1
    RIGHT  = 2
    BOTTOM = 4
    TOP    = 8

    # Encode an xy coordinate to one of the 9 clipping rects
    coder = (x, y) ->
        code = 0

        if x < xBounds.min
            code |= LEFT
        else if x > xBounds.max
            code |= RIGHT

        if y < yBounds.min
            code |= BOTTOM
        else if y > yBounds.max
            code |= TOP

        code

    # Test to see if a line segment passes through the visible rect
    test = (x1, y1, x2, y2) ->

        code1 = coder x1, y1
        code2 = coder x2, y2

        while true

            if not (code1 | code2)
                return true
            else if code1 & code2
                return false
            else
                x = y = 0

                outcode = if code1 then code1 else code2

                if outcode & TOP
                    x = x1 + (x2 - x1) * (yBounds.max - y1) / (y2 - y1)
                    y = yBounds.max
                else if outcode & BOTTOM
                    x = x1 + (x2 - x1) * (yBounds.min - y1) / (y2 - y1)
                    y = yBounds.min
                else if outcode & RIGHT
                    y = y1 + (y2 - y1) * (xBounds.max - x1) / (x2 - x1)
                    x = xBounds.max
                else if outcode & LEFT
                    y = y1 + (y2 - y1) * (xBounds.min - x1) / (x2 - x1)
                    x = xBounds.min

                if outcode is code1
                    x1 = x
                    y1 = y
                    code1 = coder x1, y1
                else
                    x2 = x
                    y2 = y
                    code2 = coder x2, y2

    #Remove points that are not connected to any valid line segments
    prev = false
    for index in [1...arr.length]
        
        cur = test arr[index - 1].x, arr[index - 1].y, arr[index].x, arr[index].y

        if (not prev) and (not cur)
            arr[index].delete = true

        prev = cur

    if not prev
        arr[arr.length - 1].delete = true

    arr.filter (dataPoint) -> not dataPoint.delete?
                    
###
Reduces data based on a first-to-enter grid cell approach.

Based on Curran Kelleher's Quadstream algorithm, see
https://github.com/curran/quadstream
###
globals.dataReduce = (arr, xBounds, yBounds, xCells, yCells, target) ->

    arr = globals.clip arr, xBounds, yBounds

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

    res = arr.filter (dataPoint) -> not dataPoint.delete?

    if res.length > target
        return globals.dataReduce res, xBounds, yBounds, (xCells / 2), (yCells / 2), target
    
    res
            