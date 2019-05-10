Google Analytics Dashboard for MODX
==========================
Current version: 2.0.1-pl

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

**Steps at Google**

For usage of the Google Analytics Dashboard Google Analytics API credentials are required.
Log in to your Google Account to which your Analytics are linked.

They can be generated at this page [https://console.cloud.google.com/apis/library](https://console.cloud.google.com/apis/library).

Search for 'Analytics API' in the library. Click on 'Analytics API'.
On the next page you could choose for 'Enable'.

Go to Credentials. Choose for Create credentials >  OAuth client ID.
- Application type: other.
- Enter a name and click on 'Create'.

Copy the 'Client ID' and a 'Client secret' to the system settings in MODX.


**System settings**
The following system settings are required for further setup. Go the system settings > Select the namespace 'googleanalytics'.
- `googleanalytics.client_id` (Client ID)
- `googleanalytics.client_secret` (Client secret)


**Extra**
Go to Extras > Google Analytics. Click on the button 'Authorize'.

Click on 'Get authorization token'. Select your Google account which is used for Analytics.
Read about the permissions, click on 'Allow' if you agree with them.

Copy the code to Authorization token field in MODX. Click on 'Authorize'.

A new button 'Settings' will be available. Click on 'Settings'.

*Settings*
In this modal you should select your:
- Google Analytics account
- Google Analytics property
- Google Analytics profile

Click on 'Save'.

Google Analytics is now successfully connected to the Google Analytics Dashboard.

Go to Dashboards and place Analytics realtime and Analytics visitors on the dashboard of choice.

That's it, all done!


_What should I do with previous installations of the Google Analytics Dashboard?_

- Remove current widgets from dashboards & delete widget from the widget list
- Download or update the Google Analytics Dashboard.
- Install the new Google Analytics Dashboard 2.*.*
- Uninstall and remove the Google Analytics Dashboard Widget 1.*.* from package management.
