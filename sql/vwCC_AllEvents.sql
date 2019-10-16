CREATE VIEW vwCC_AllEvents AS

SELECT E.eventID, E.eventLink, E.eventName, E.eventDate,
    E.cc_venueID, V.venueName, V.venueStatus, V.venueType,
    V.address_1, V.address_2, V.city, V.state, V.zip, 
    V.country, V.lat, V.lon
FROM cc_events E
INNER JOIN cc_venues V ON E.cc_venueID = V.cc_venueID 
	