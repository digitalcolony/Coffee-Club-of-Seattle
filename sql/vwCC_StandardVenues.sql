ALTER VIEW vwCC_StandardVenues AS
SELECT V.cc_venueID, V.venueName, V.city, V.lat, V.lon, 
V.venueStatus, 
 date_format(MIN(E.eventDate), '%m-%d-%Y') AS eventFirst,
 date_format(MAX(E.eventDate), '%m-%d-%y') AS eventLast,
 COUNT(E.eventID) AS eventTotal
FROM cc_venues V 
INNER JOIN cc_events E ON V.cc_venueID = E.cc_venueID
WHERE V.venueType = 'Standard'
GROUP BY V.cc_venueID, V.venueName

