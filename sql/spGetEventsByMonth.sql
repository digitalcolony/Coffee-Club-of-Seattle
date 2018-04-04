CREATE PROCEDURE `spGetEventsByMonth` (
	IN p_EventMonth DATETIME)
BEGIN
	SELECT eventLink, eventName, DATE_FORMAT(eventDate,'%m-%d-%Y') AS eventDateFormat
	FROM vwAllEvents 
	WHERE eventDate BETWEEN p_EventMonth AND DATE_ADD(p_EventMonth, INTERVAL 1 MONTH)
	 ORDER BY eventDate DESC;	
END
