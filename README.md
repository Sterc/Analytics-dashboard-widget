Google Analytics Dashboard for MODX
==========================
Current version: 2.0.0-pl

Author: Sterc <modx@sterc.nl>

A custom MODX Dashboard Widget that will show data from Google Analytics. A Google Analytics account is required to use this plugin.


--------------------
Features
--------------------
- Show visitor, traffic sources, content and search queries
- Show website goals
- Realtime statistics!

--------------------
Installation
--------------------

For usage of the Google Analytics Dashboard Google Analytics Api credentials are required.
They can be generated at this page https://console.cloud.google.com.

If you have an previous installation of the Google Analytics Dashboard Widget:
- Remove current widgets from dashboards & delete widget from the widget list

- Download or update the Google analytics Dashboard. 
- Install the new Google Analytics Dashboard 2.*.*
- Uninstall and remove the Google Analytics Dashboard Widget 1.*.* from package management.
- Navigate to System Settings and search for "googleanalytics.client". Enter your Google Analytics API Client ID and API Key.
- Go to the Google Analytics CMP and click on authorize in the top right corner.
- Click the "Authorize" button on the top right to get an authorization code and authorize access to your analytics.
- Click the "Settings" button and select your account, property and profile where to fetch the data from.
- Costumize the view settings on the right and save the settings, data will be shown now in the Google Analytics CMP.
- Go to Dashboards and place Analytics realtime and Analytics visitors on the dashboard of choice.

That's it, all done!