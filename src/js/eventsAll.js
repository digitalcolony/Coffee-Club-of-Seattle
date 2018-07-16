(function() {
    // Create the connector object
    var myConnector = tableau.makeConnector();

    // Define the schema
    myConnector.getSchema = function(schemaCallback) {
        var cols = [{
            id: "eventID",
            dataType: tableau.dataTypeEnum.string
        }, {
            id: "venueName",
            alias: "venue",
            dataType: tableau.dataTypeEnum.string
        }, {
            id: "lat",
            alias: "latitude",
            columnRole: "dimension",
            // Do not aggregate values as measures in Tableau--makes it easier to add to a map 
            dataType: tableau.dataTypeEnum.float
        }, {
            id: "lon",
            alias: "longitude",
            columnRole: "dimension",
            // Do not aggregate values as measures in Tableau--makes it easier to add to a map 
            dataType: tableau.dataTypeEnum.float
        }];

        var tableSchema = {
            id: "eventFeed",
            alias: "Coffee Club events",
            columns: cols
        };

        schemaCallback([tableSchema]);
    };

    // Download the data
    myConnector.getData = function(table, doneCallback) {
        $.getJSON("https://ineedcoffee.com/coffeeclub/jsonAllEvents.php", function(resp) {
            var feat = resp.features,
                tableData = [];

            // Iterate over the JSON object
            for (var i = 0, len = feat.length; i < len; i++) {
                tableData.push({
                    "eventID": feat[i].eventID,
                    "venueName": feat[i].properties.venueName,
                    "lon": feat[i].geometry.coordinates[0],
                    "lat": feat[i].geometry.coordinates[1]
                });
            }

            table.appendRows(tableData);
            doneCallback();
        });
    };

    tableau.registerConnector(myConnector);

    // Create event listeners for when the user submits the form
    $(document).ready(function() {
        $("#submitButton").click(function() {
            tableau.connectionName = "Coffee Club of Seattle Events Feed"; // This will be the data source name in Tableau
            tableau.submit(); // This sends the connector object to Tableau
        });
    });
})();
