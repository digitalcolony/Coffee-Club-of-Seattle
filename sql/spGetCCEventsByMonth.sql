CREATE PROCEDURE spGetCCEventsByMonth (
    IN p_EventMonth DATETIME
) 
BEGIN
SELECT
  cc_venueID,
  venueName,
  eventLink,
  eventName,
  DATE_FORMAT(eventDate, '%m-%d-%Y') AS eventDateFormat
FROM
  vwCC_AllEvents
WHERE
  eventDate BETWEEN p_EventMonth
  AND DATE_ADD(p_EventMonth, INTERVAL 1 MONTH)
ORDER BY
  eventDate ASC;
END