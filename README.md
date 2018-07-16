# Coffee Club of Seattle

Documentation and code for the Meetup group Coffee Club of Seattle.

![Coffee Club of Seattle](coffee-club-logo.jpeg "Coffee Club of Seattle")

## GitBook

We have a GitBook with all our documention at:
[coffee-club-of-seattle.gitbooks.io/pages/](https://coffee-club-of-seattle.gitbooks.io/pages/)

## Code

Unlike most Meetup groups, historical data is very important to the Coffee Club of Seattle Meetup. Since our mission to to visit many different coffee venues, our organizers need a way to know where we have been in the past.

Unfortunately, Meetup makes it next to impossible to gather that historical data for more than a few months, let alone data going back to 2006. The good news is Meetup has an API available that lets us query our historical data, which we use for reporting.

### Meetup API and Cron Job

At 1 AM daily, a cron job loads a webpage that talks to Meetup API to see if any new events have occurred and if so, did they occur at a new or existing venue. If we receive new data, our MySQL database is updated.

### Database Structure

There are 5 tables in our CoffeeClub Database.

1.  events
2.  venues
3.  eventsclean
4.  venuesclean
5.  venuesmapped

The events and venues table are pulled directly from Meetup and if they had a clean dataset, that is all we would need. But, the Meetup database is full of duplicate entries for venues and incomplete data. To resolve this issue, we maintain a clean version of the venue data with additional fields. The venuesmapped table solves the duplicate entry problem by pointing all copies of a venue to a single entry. The eventsclean table is rarely edited.

### Venue Report

The Venue Report can be viewed at
[coffeeclub.app](https://coffeeclub.app/).

Each column is sortable both ascending and descending. Coffee venues known to be Inactive are not visible by default, but can be added to the report by clicking the Show Inactive button.

Clicking on a Venue Name will load the Detail Report for that venue.

### Detail Report

The Detail Report lists all Meetup events for that venue with links to the page on Meetup. Organizers can use the "copy" feature once on Meetup to recreate a new event from that venue.

### Map

The Map uses the latitude and longitude on the venue, which is returned by the Meetup API and stored in the venues and venuesclean tables. Our map pulls from the venuesclean table. The number on the map marker for the venue indicates the number of Meetups the Coffee Club has held at that venue. Inactive and non-coffee venues are not displayed on the map.

### Stats Report

The Stats Report has a few reports events by Day of the Week, Month, and year. We also have an Activity Map
broken down by month. Each month square links to a Monthly Report.

The activity map uses D3.js. It needs version 3.5.6, as version 5.5 breaks it.

### Monthly Report

A monthly detail report accessed via the Activity Heatmap on the Stats Report.

### Leads Report

This report returns new businesses in the area from YELP that use "coffee" as a defined category. Not every business will be a coffee shop. The code that generates the JSON file comes from my [cafe-informat](https://github.com/digitalcolony/cafe-informant) repo.
