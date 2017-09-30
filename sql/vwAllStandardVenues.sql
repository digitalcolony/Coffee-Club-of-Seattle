CREATE 
VIEW `vwAllStandardVenues` AS
    SELECT 
        `VC`.`venueName` AS `venue`,
        `VC`.`venueID` AS `venueID`,
        `VC`.`city` AS `city`,
        `VC`.`lat` AS `lat`,
        `VC`.`lon` AS `lon`,
        `VC`.`venueStatus` AS `status`,
        DATE_FORMAT(MIN(`E`.`eventDate`), '%m-%d-%Y') AS `first`,
        DATE_FORMAT(MAX(`E`.`eventDate`), '%m-%d-%Y') AS `last`,
        COUNT(0) AS `total`
    FROM
        ((`venuesclean` `VC`
        JOIN `venuesmapped` `VM` ON ((`VC`.`venueID` = `VM`.`venueIDmapped`)))
        JOIN `eventsclean` `E` ON ((`E`.`venueID` = `VM`.`venueID`)))
    WHERE
        (`VC`.`venueType` = 'Standard')
    GROUP BY `VC`.`venueName`
    ORDER BY COUNT(0) DESC