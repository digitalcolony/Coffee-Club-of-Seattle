CREATE 
VIEW `vwAllEvents` AS
    SELECT 
        `E`.`eventID` AS `eventID`,
        `E`.`eventLink` AS `eventLink`,
        `E`.`eventName` AS `eventName`,
        `E`.`eventDate` AS `eventDate`,
        `V`.`venueID` AS `venueID`,
        `V`.`venueName` AS `venueName`,
        `V`.`venueStatus` AS `venueStatus`,
        `V`.`venueType` AS `venueType`,
        `V`.`address_1` AS `address_1`,
        `V`.`address_2` AS `address_2`,
        `V`.`city` AS `city`,
        `V`.`state` AS `state`,
        `V`.`zip` AS `zip`,
        `V`.`country` AS `country`,
        `V`.`lat` AS `lat`,
        `V`.`lon` AS `lon`
    FROM
        ((`venuesclean` `V`
        JOIN `venuesmapped` `VM` ON ((`V`.`venueID` = `VM`.`venueIDmapped`)))
        JOIN `eventsclean` `E` ON ((`E`.`venueID` = `VM`.`venueID`)))
    ORDER BY `E`.`eventID`