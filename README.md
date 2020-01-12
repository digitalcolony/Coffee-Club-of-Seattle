# Coffee Club of Seattle

Documentation and code for the Meetup group Coffee Club of Seattle.

![Coffee Club of Seattle](src/img/coffee-club-logo.jpeg "Coffee Club of Seattle")

## GitBook

We have a GitBook with all our documentation at:
[coffee-club-of-seattle.gitbooks.io/pages/](https://coffee-club-of-seattle.gitbooks.io/pages/)

## Code

Unlike most Meetup groups, historical data is very important to the Coffee Club of Seattle Meetup. Since our mission to visit many different coffee venues, our organizers need a way to know where we have been in the past.

Unfortunately, Meetup makes it next to impossible to gather that historical data for more than a few months, let alone data going back to 2006. 

### Meetup API and Cron Job

UPDATE (September 2019): Meetup is no longer allowing access to their API from non-PRO accounts. You need
a PRO account and to have your OAuth request approved.

So now we web-scrape the data we need from Meetup. See this repo for more details:
[scrape-meetup](https://github.com/digitalcolony/scrape-meetup)

### Database Structure

TODO: Add detail on new database structure.

### Venue Report

The Venue Report can be viewed at
[coffeeclub.app](https://coffeeclub.app/).

Each column is sortable both ascending and descending. It also has a filter option at the top. Coffee venues
that are known to be out of business will appear with a gary background. Non-coffee venues are removed from
the report. 

Clicking on a Venue Name will load the Detail Report for that venue.

### Detail Report

The Detail Report lists all Meetup events for that venue with links to the page on Meetup. Organizers can use the "copy" feature once on Meetup to recreate a new event from that venue.

### Map

The Map uses the latitude and longitude on the venue. The number on the map marker for the venue indicates the number of Meetups the Coffee Club has held at that venue. Inactive and non-coffee venues are not displayed on the map.

### Stats Report

The Stats Report has a few reports events by Day of the Week, Month, and year. We also have an Activity Map
broken down by month. Each month square links to a Monthly Report.

The activity map uses D3.js. It needs version 3.5.6, as version 5.5 breaks it.

### Monthly Report

A monthly detail report accessed via the Activity Heatmap on the Stats Report.

### Leads Report

This report returns new businesses in the area from YELP that use "coffee" as a defined category. Not every business will be a coffee shop. The code that generates the JSON file comes from my [cafe-informat](https://github.com/digitalcolony/cafe-informant) repo.
