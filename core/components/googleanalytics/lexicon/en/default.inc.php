<?php

/**
 * Google Analytics
 *
 * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 *
 * Google Analytics is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Google Analytics is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Google Analytics; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

$_lang['googleanalytics']                                   = 'Google Analytics';
$_lang['googleanalytics.desc']                              = 'Google Analytics web analytics and reporting';
$_lang['googleanalytics.menu_desc']                         = 'To view the Google Analytics data.';

$_lang['area_googleanalytics']                              = 'Google Analytics';

$_lang['setting_googleanalytics.branding_url']              = 'Branding';
$_lang['setting_googleanalytics.branding_url_desc']         = 'The URL of the branding button. Empty? The branding button won\'t be shown.';
$_lang['setting_googleanalytics.branding_url_help']         = 'Branding (help)';
$_lang['setting_googleanalytics.branding_url_help_desc']    = 'The URL of the branding button. Empty? The branding button won\'t be shown.';
$_lang['setting_googleanalytics.account']                   = 'Google Analytics account';
$_lang['setting_googleanalytics.account_desc']              = 'The Google Analytics account data which should be displayed.';
$_lang['setting_googleanalytics.api_useragent']             = 'API useragent';
$_lang['setting_googleanalytics.api_useragent_desc']        = 'The useragent for the API\'s. Default is "GoogleAnalyticsOAuth v2.0.0".';
$_lang['setting_googleanalytics.client_id']                 = 'Google Analytics client ID';
$_lang['setting_googleanalytics.client_id_desc']            = 'The Google Analytics client ID, you can get this at https://console.developers.google.com/.';
$_lang['setting_googleanalytics.client_secret']             = 'Google Analytics client secret';
$_lang['setting_googleanalytics.client_secret_desc']        = 'The Google Analytics client secret, you can get this at https://console.developers.google.com/.';
$_lang['setting_googleanalytics.refresh_token']             = 'Google Analytics refresh token';
$_lang['setting_googleanalytics.refresh_token_desc']        = 'The Google Analytics refresh token, you can get this with oAuth with the minimum scope "https://www.googleapis.com/auth/analytics.readonly".';
$_lang['setting_googleanalytics.history']                   = 'Days';
$_lang['setting_googleanalytics.history_desc']              = 'The number of days to show (min: 7, max: 30 days.';
$_lang['setting_googleanalytics.panels']                    = 'Components';
$_lang['setting_googleanalytics.panels_desc']               = 'The components that should be shown. Separate components with a comma.';

$_lang['googleanalytics.getstarted_title']                  = 'Getting started';
$_lang['googleanalytics.getstarted_desc']                   = '<ol class="list-decimal"><li>First we need to create <a href="https://console.cloud.google.com/apis/api/analytics.googleapis.com/overview" target="_blank" rel="noopener">Google Analytics API credentials</a> to be able to get data from Google Analytics.</li><li>Navigate to System Settings and search for "googleanalytics.client". Enter your Google Analytics API Client ID and API Key.</li><li>Click the "Authorize" button on the top right to get an authorization code.</li><li>Click the "Settings" button and select your profile.</li><li>Add Analytics Widgets to your dashboard. There is a visitors and realtime widget available.</li></ol>';

$_lang['googleanalytics.widget_visitors']				    = 'Google Analytics visitors';
$_lang['googleanalytics.widget_visitors_desc']			    = 'Google Analytics visitors widgets shows the visitors stats from Google Analytics.';
$_lang['googleanalytics.widget_visitors_title']			    = 'Google Analytics visitors ([[+property]])';
$_lang['googleanalytics.widget_realtime']				    = 'Google Analytics realtime';
$_lang['googleanalytics.widget_realtime_desc']			    = 'Google Analytics realtime widget shows the realtime visitors of Google Analytics.';
$_lang['googleanalytics.widget_realtime_title']			    = 'Google Analytics realtime ([[+property]])';

$_lang['googleanalytics.label_code']                        = 'Authorization token';
$_lang['googleanalytics.label_code_desc']                   = 'Click the \'Get authorization token\' button to get a Google Analytics authorization token and copy/past that token here.';
$_lang['googleanalytics.label_account']					    = 'Google Analytics account';
$_lang['googleanalytics.label_account_desc']			    = 'The Google Analytics account whose data should be displayed.';
$_lang['googleanalytics.label_property']				    = 'Google Analytics property';
$_lang['googleanalytics.label_property_desc']			    = 'The Google Analytics property whose data should be displayed.';
$_lang['googleanalytics.label_profile']					    = 'Google Analytics profile';
$_lang['googleanalytics.label_profile_desc']			    = 'The Google Analytics profile what will be displayed as default.';
$_lang['googleanalytics.label_panels']				        = 'Sections';
$_lang['googleanalytics.label_panels_desc']			        = '';
$_lang['googleanalytics.label_history']                     = 'Number of days';
$_lang['googleanalytics.label_history_desc']                = 'Display data for this amount of days.';

$_lang['googleanalytics.stats_desc']					    = 'Google Analytics is a Google service to show statistics and trends of your website.';
$_lang['googleanalytics.open_googleanalytics']			    = 'Go to Google Analytics';
$_lang['googleanalytics.auth']						        = 'Authorize';
$_lang['googleanalytics.auth_code']						    = 'Get authorization token';
$_lang['googleanalytics.auth_revoke']				        = 'Revoke authorization';
$_lang['googleanalytics.auth_revoke_confirm']		        = 'Are you sure you want to revoke the authorization?';
$_lang['googleanalytics.settings']						    = 'Settings';
$_lang['googleanalytics.filter_account']				    = 'Choose an account';
$_lang['googleanalytics.filter_property']				    = 'Choose a property';
$_lang['googleanalytics.filter_profile']				    = 'Choose a profile';
$_lang['googleanalytics.online_now']					    = 'At this moment';
$_lang['googleanalytics.online_visitor']				    = 'active visitor';
$_lang['googleanalytics.online_visitors']				    = 'active visitors';
$_lang['googleanalytics.title_summary']					    = 'Summary';
$_lang['googleanalytics.title_visitors']				    = 'Visitors';
$_lang['googleanalytics.title_sources']					    = 'Traffic sources';
$_lang['googleanalytics.title_content']					    = 'Content';
$_lang['googleanalytics.title_content_search']			    = 'Search';
$_lang['googleanalytics.title_goals']				        = 'Goals';
$_lang['googleanalytics.title_block_summary']			    = 'Stats last [[+history]] days';
$_lang['googleanalytics.title_block_meta']				    = 'Stats compared last [[+history]] days';
$_lang['googleanalytics.title_block_visitors']			    = 'Visitors';
$_lang['googleanalytics.title_block_language']			    = 'Language';
$_lang['googleanalytics.title_block_country']			    = 'Countries';
$_lang['googleanalytics.title_block_devices']			    = 'Devices';
$_lang['googleanalytics.title_block_sources']			    = 'Traffic sources';
$_lang['googleanalytics.title_block_content_high']		    = 'Top 15 entrancepoints';
$_lang['googleanalytics.title_block_content_low']		    = 'Top 15 exitpoints';
$_lang['googleanalytics.title_block_content_search']	    = 'Searchqueries';
$_lang['googleanalytics.title_block_goals']                 = 'Completed goals';
$_lang['googleanalytics.visits']						    = 'Visits';
$_lang['googleanalytics.visits_on']						    = '[[+data]] visits at [[+date_long]]';
$_lang['googleanalytics.visits_new']					    = 'New visits';
$_lang['googleanalytics.visitors']						    = 'Visitors';
$_lang['googleanalytics.visitors_on']					    = '[[+data]] visitors at [[+date_long]]';
$_lang['googleanalytics.visitors_time']					    = 'Time on site';
$_lang['googleanalytics.pageviews']						    = 'Pageviews';
$_lang['googleanalytics.pageviews_on']					    = '[[+data]] pageviews at [[+date_long]]';
$_lang['googleanalytics.pageviews_unique']				    = 'Unique pageviews';
$_lang['googleanalytics.new_visitor']					    = 'New';
$_lang['googleanalytics.returning_visitor']				    = 'Returning';
$_lang['googleanalytics.bounces']						    = 'Bounces';
$_lang['googleanalytics.bouncerate']					    = 'Bounce rate';
$_lang['googleanalytics.tablet']						    = 'Tablet';
$_lang['googleanalytics.mobile']						    = 'Mobile';
$_lang['googleanalytics.desktop']						    = 'Desktop';
$_lang['googleanalytics.source']						    = 'Source';
$_lang['googleanalytics.source_search']					    = 'Search engine';
$_lang['googleanalytics.source_socialmedia']			    = 'Social media';
$_lang['googleanalytics.source_direct']					    = 'Direct traffic';
$_lang['googleanalytics.source_reference']				    = 'Referring traffic';
$_lang['googleanalytics.content']						    = 'Page';
$_lang['googleanalytics.entrances']						    = 'Entrance points';
$_lang['googleanalytics.exits']							    = 'Exitpoints';
$_lang['googleanalytics.exitrate']						    = 'Exitrate';
$_lang['googleanalytics.keyword']						    = 'Keyword';
$_lang['googleanalytics.location']                          = 'Page';
$_lang['googleanalytics.goal']                              = 'Completed goals';
$_lang['googleanalytics.history_1']                         = '7 days';
$_lang['googleanalytics.history_2']                         = '14 days';
$_lang['googleanalytics.history_3']                         = '21 days';
$_lang['googleanalytics.history_4']                         = '28 days';
$_lang['googleanalytics.auth_error']                        = 'An error has occurred while authorizing, please try again.';
$_lang['googleanalytics.auth_error_save']                   = 'An error has occurred while saving the authorization token, please try again.';
$_lang['googleanalytics.view_more']                         = 'View more';

$_lang['setting_googleanalytics.user_name']                 = 'Your name';
$_lang['setting_googleanalytics.user_name_desc']            = 'Is used for the Sterc Extra\'s newsletter subscription. (optional)';
$_lang['setting_googleanalytics.user_email']                = 'Your emailaddress';
$_lang['setting_googleanalytics.user_email_desc']           = 'Is used for the Sterc Extra\'s newsletter subscription. (optional)';
