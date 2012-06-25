data.selector = (fieldIndex, filterFunc = (dp) -> true) ->
    rawData = data.dataPoints.filter filterFunc
    rawData.map (dp) -> dp[fieldIndex]

