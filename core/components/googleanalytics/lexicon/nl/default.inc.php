<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$_lang['googleanalytics']                                       = 'Google Analytics';
$_lang['googleanalytics.desc']                                  = 'Bekijk de Google Analytics data.';

$_lang['area_googleanalytics']                                  = 'Google Analytics';

$_lang['setting_googleanalytics.branding_url']                  = 'Branding';
$_lang['setting_googleanalytics.branding_url_desc']             = 'De URL waar de branding knop heen verwijst, indien leeg wordt de branding knop niet getoond.';
$_lang['setting_googleanalytics.branding_url_help']             = 'Branding (help)';
$_lang['setting_googleanalytics.branding_url_help_desc']        = 'De URL waar de branding help knop heen verwijst, indien leeg wordt de branding help knop niet getoond.';
$_lang['setting_googleanalytics.account']                       = 'Google Analytics account';
$_lang['setting_googleanalytics.account_desc']                  = 'Het Google Analytics account waarvan de gegevens getoond worden.';
$_lang['setting_googleanalytics.client_id']                     = 'Google Analytics client ID';
$_lang['setting_googleanalytics.client_id_desc']                = 'De Google Analytics client ID, deze is te verkrijgen via https://console.developers.google.com/.';
$_lang['setting_googleanalytics.client_secret']                 = 'Google Analytics client secret';
$_lang['setting_googleanalytics.client_secret_desc']            = 'De Google Analytics client secret, deze is te verkrijgen via https://console.developers.google.com/.';
$_lang['setting_googleanalytics.refresh_token']                 = 'Google Analytics refresh token';
$_lang['setting_googleanalytics.refresh_token_desc']            = 'De Google Analytics refresh token, deze is te verkrijgen via oAuth met de minimale scope "https://www.googleapis.com/auth/analytics.readonly".';
$_lang['setting_googleanalytics.history']                       = 'Dagen';
$_lang['setting_googleanalytics.history_desc']                  = 'Het aantal dagen om te tonen (min: 7, max: 30 dagen). Standaard is "14".';
$_lang['setting_googleanalytics.cache_lifetime']                = 'Cachen';
$_lang['setting_googleanalytics.cache_lifetime_desc']           = 'De tijd dat de statistieken lokaal gecached worden, na deze tijd worden ze weer vernieuwd. Standaard is "1800".';
$_lang['setting_googleanalytics.panels']                        = 'Onderdelen';
$_lang['setting_googleanalytics.panels_desc']                   = 'De onderdelen waarvan de statistieken getoond moeten worden. Onderdelen scheiden met een komma.';

$_lang['googleanalytics.widget_visitors']                       = 'Google Analytics bezoekers';
$_lang['googleanalytics.widget_visitors_desc']                  = 'Google Analytics bezoekers widget toont de bezoekers statistieken van Google Analytics.';
$_lang['googleanalytics.widget_visitors_title']                 = 'Google Analytics bezoekers ([[+property]])';
$_lang['googleanalytics.widget_realtime']                       = 'Google Analytics realtime';
$_lang['googleanalytics.widget_realtime_desc']                  = 'Google Analytics realtime widget toont de realtime bezoekers van Google Analytics.';
$_lang['googleanalytics.widget_realtime_title']                 = 'Google Analytics realtime ([[+property]])';

$_lang['googleanalytics.label_code']                            = 'Autorisatie code';
$_lang['googleanalytics.label_code_desc']                       = 'De autorisatie code om toegang te krijgen tot de Google Analytics gegevens.';
$_lang['googleanalytics.label_get_code']                        = 'Autorisatie code aanvragen';
$_lang['googleanalytics.label_account']                         = 'Google Analytics account';
$_lang['googleanalytics.label_account_desc']                    = 'Het Google Analytics account waarvan de gegevens getoond moeten worden.';
$_lang['googleanalytics.label_property']                        = 'Google Analytics property';
$_lang['googleanalytics.label_property_desc']                   = 'Het Google Analytics property waarvan de gegevens getoond moeten worden.';
$_lang['googleanalytics.label_profile']                         = 'Google Analytics profiel';
$_lang['googleanalytics.label_profile_desc']                    = 'Het Google Analytics profiel wat standaard getoond moet worden.';
$_lang['googleanalytics.label_panels']                          = 'Secties';
$_lang['googleanalytics.label_panels_desc']                     = '';
$_lang['googleanalytics.label_history']                         = 'Aantal dagen';
$_lang['googleanalytics.label_history_desc']                    = 'Het aantal dagen waarvan de statistieken getoond moeten worden. Standaard is "14".';
$_lang['googleanalytics.label_cache_lifetime']                  = 'Cachen';
$_lang['googleanalytics.label_cache_lifetime_desc']             = 'De tijd dat de statistieken lokaal gecached worden, na deze tijd worden ze weer vernieuwd. Standaard is "1800".';

$_lang['googleanalytics.stats_desc']                            = 'De Google Analytics statistieken geven je meer inzicht in het gedrag en de trends van bezoekers. Deze gegevens worden standaard een 30 minuten gecached.';
$_lang['googleanalytics.open_googleanalytics']                  = 'Google Analytics';
$_lang['googleanalytics.auth_create']                           = 'Autoriseren';
$_lang['googleanalytics.auth_create_desc']                      = 'Klik op de knop \'' . $_lang['googleanalytics.label_get_code'] . '\' om een Google Analytics autorisatie code aan te vragen. Deze code geeft je toegang tot de Google Analytics statistieken en deze kopieer/plak je vervolgens in het tekstveld hieronder.';
$_lang['googleanalytics.auth_remove']                           = 'Autorisatie intrekken';
$_lang['googleanalytics.auth_remove_confirm']                   = 'Weet je zeker dat je de autorisatie wilt intrekken?';
$_lang['googleanalytics.refresh_settings']                      = 'Vernieuwen';
$_lang['googleanalytics.refresh_settings_confirm']              = 'De Google Analytics statistieken worden standaard een 30 minuten gecached, weet je zeker dat je de statistieken wilt vernieuwen?';

$_lang['googleanalytics.settings']                              = 'Instellingen';
$_lang['googleanalytics.filter_account']                        = 'Kies een account';
$_lang['googleanalytics.filter_property']                       = 'Kies een property';
$_lang['googleanalytics.filter_profile']                        = 'Kies een weergave';
$_lang['googleanalytics.online_now']                            = 'Op dit moment';
$_lang['googleanalytics.online_visitor']                        = 'actieve bezoeker';
$_lang['googleanalytics.online_visitors']                       = 'actieve bezoekers';
$_lang['googleanalytics.title_summary']                         = 'Overzicht';
$_lang['googleanalytics.title_visitors']                        = 'Bezoekers';
$_lang['googleanalytics.title_sources']                         = 'Verkeersbronnen';
$_lang['googleanalytics.title_content']                         = 'Pagina\'s';
$_lang['googleanalytics.title_content_search']                  = 'Zoekopdrachten';
$_lang['googleanalytics.title_goals']                           = 'Doelen';
$_lang['googleanalytics.title_block_summary']                   = 'Statistieken van [[+date_1]] tot en met [[+date_2]]';
$_lang['googleanalytics.title_block_meta']                      = 'Statistieken vergeleken met de vorige [[+history]] dagen';
$_lang['googleanalytics.title_block_speed']                     = 'Site snelheid afgelopen [[+history]] dagen';
$_lang['googleanalytics.title_block_visitors']                  = 'Bezoekers';
$_lang['googleanalytics.title_block_language']                  = 'Taal';
$_lang['googleanalytics.title_block_country']                   = 'Herkomst';
$_lang['googleanalytics.title_block_devices']                   = 'Apparaten';
$_lang['googleanalytics.title_block_sources']                   = 'Verkeersbronnen';
$_lang['googleanalytics.title_block_content_high']              = 'Top 15 instappagina\'s';
$_lang['googleanalytics.title_block_content_low']               = 'Top 15 uitstappagina\'s';
$_lang['googleanalytics.title_block_content_search']            = 'Zoekopdrachten';
$_lang['googleanalytics.title_block_goals']                     = 'Behaalde doelen';
$_lang['googleanalytics.date']                                  = 'Datum';
$_lang['googleanalytics.visits']                                = 'Bezoeken';
$_lang['googleanalytics.visits_on']                             = '[[+data]] bezoeken op [[+date_long]]';
$_lang['googleanalytics.visits_new']                            = 'Nieuwe bezoeken';
$_lang['googleanalytics.visitors']                              = 'Bezoekers';
$_lang['googleanalytics.visitors_on']                           = '[[+data]] bezoekers op [[+date_long]]';
$_lang['googleanalytics.visitors_time']                         = 'Bezoekduur';
$_lang['googleanalytics.pageviews']                             = 'Paginaweergaven';
$_lang['googleanalytics.pageviews_on']                          = '[[+data]] paginaweergaven op [[+date_long]]';
$_lang['googleanalytics.pageviews_unique']                      = 'Unieke paginaweergaven';
$_lang['googleanalytics.new_visitor']                           = 'Nieuw';
$_lang['googleanalytics.returning_visitor']                     = 'Terugkerend';
$_lang['googleanalytics.bounces']                               = 'Bounces';
$_lang['googleanalytics.bouncerate']                            = 'Bouncepercentage';
$_lang['googleanalytics.page_load_time']                        = 'Gem. laadtijd van de pagina (sec)';
$_lang['googleanalytics.domain_lookup_time']                    = 'Gem. domeinopzoektijd (sec)';
$_lang['googleanalytics.page_download_time']                    = 'Gem. downloadtijd van pagina (sec)';
$_lang['googleanalytics.redirection_time']                      = 'Gem. omleidingstijd (sec)';
$_lang['googleanalytics.server_connection_time']                = 'Gem. serververbindingstijd (sec)';
$_lang['googleanalytics.server_response_time']                  = 'Gem. serverreactietijd (sec)';
$_lang['googleanalytics.tablet']                                = 'Tablet';
$_lang['googleanalytics.mobile']                                = 'Mobiel';
$_lang['googleanalytics.desktop']                               = 'Desktop';
$_lang['googleanalytics.source']                                = 'Verkeersbron';
$_lang['googleanalytics.source_search']                         = 'Zoekmachine';
$_lang['googleanalytics.source_socialmedia']                    = 'Social media';
$_lang['googleanalytics.source_direct']                         = 'Direct verkeer';
$_lang['googleanalytics.source_reference']                      = 'Verwijzend verkeer';
$_lang['googleanalytics.content']                               = 'Pagina';
$_lang['googleanalytics.entrances']                             = 'Instappunten';
$_lang['googleanalytics.exits']                                 = 'Uitstappunten';
$_lang['googleanalytics.exitrate']                              = 'Uitstappercentage';
$_lang['googleanalytics.keyword']                               = 'Zoekopdracht';
$_lang['googleanalytics.location']                              = 'Lokatie';
$_lang['googleanalytics.goal']                                  = 'Behaalde doel';
$_lang['googleanalytics.history_1']                             = '7 dagen';
$_lang['googleanalytics.history_2']                             = '14 dagen';
$_lang['googleanalytics.history_3']                             = '21 dagen';
$_lang['googleanalytics.history_4']                             = '28 dagen';
$_lang['googleanalytics.auth_error']                            = 'Er is een fout opgetreden tijdens het autoriseren, probeer het opnieuw.';
$_lang['googleanalytics.auth_error_save']                       = 'Er is een fout opgetreden tijdens het opslaan van de autorisatie code, probeer het opnieuw.';
$_lang['googleanalytics.view_more']                             = 'Bekijk meer';
$_lang['googleanalytics.no_oauth']                              = 'MODx heeft geen toegang tot je Google Analytics account, zorg er voor dat MODx toegang heeft om de statistieken te kunnen tonen.';

$_lang['googleanalytics.no_oauth_title']                        = 'Google Analytics autoriseren';
$_lang['googleanalytics.no_oauth_content']                      = '<ol class="x-list-decimal"><li>Als eerste moeten we <a href="https://console.cloud.google.com/apis/api/analytics.googleapis.com/overview" target="_blank" rel="noopener">Google Analytics API gegevens</a> aanmaken om data uit Google Analytics te kunnen opvragen.</li><li>Navigeer naar Systeem Instellingen en zoek op "googleanalytics.client". Vul hier de Google Analytics API Client ID en API sleutel in.</li><li>Klik rechtsboven op \'Autoriseren\' voor het verkrijgen van een autorisatie code. Je hebt een ingesteld Google Analytics account nodig.</li><li>Klik op \'Instellingen\' en selecteer het een profiel dat je wil tonen.</li><li>Voeg de Google Analytics Widgets toe aan je dashboard. Er is een bezoekers- en realtime widget beschikbaar.</li></ol>';
