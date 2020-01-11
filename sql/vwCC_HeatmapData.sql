CREATE VIEW vwCC_HeatmapData AS

SELECT
  unix_timestamp(eventDate) AS epochDate,
  COUNT(0) AS eventCount
FROM
    cc_events
GROUP BY
  unix_timestamp(eventDate)