<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsRequest
{
    const API_URL                       = 'https://www.googleapis.com/analytics/v3/';
    const API_OAUTH_URL                 = 'https://accounts.google.com/o/oauth2/auth';
    const API_OAUTH_TOKEN_URL           = 'https://accounts.google.com/o/oauth2/token';
    const API_OAUTH_TOKEN_STATUS_URL    = 'https://accounts.google.com/o/oauth2/tokeninfo';
    const API_OAUTH_REDIRECT_URL        = 'urn:ietf:wg:oauth:2.0:oob';

    /**
     * @access public.
     * @var Object.
     */
    public $modx;

    /**
     * @access public.
     * @var Object.
     */
    public $googleanalytics;

    /**
     * @access public.
     * @var Integer.
     */
    public $history = 7;

    /**
     * @access public.
     * @param modX $modx.
     * @param Object $googleanalytics.
     */
    public function __construct(modX &$modx, &$googleanalytics)
    {
        $this->modx =& $modx;
        $this->googleanalytics =& $googleanalytics;
    }

    /**
     * @access public.
     * @param Integer $history.
     */
    public function setHistory($history)
    {
        $this->history = $history;
    }

    /**
     * @access public.
     * @return Integer.
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @access public.
     * @return String.
     */
    public function getApiKey()
    {
        return $this->modx->getOption('googleanalytics.client_id');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getApiSecret()
    {
        return $this->modx->getOption('googleanalytics.client_secret');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getAuthorizeUrl()
    {
        return self::API_OAUTH_URL . '?' . http_build_query([
            'response_type' => 'code',
            'client_id'     => $this->getApiKey(),
            'redirect_uri'  => self::API_OAUTH_REDIRECT_URL,
            'scope'         => 'https://www.googleapis.com/auth/analytics.readonly'
        ]);
    }

    /**
     * @access public.
     * @param String $code.
     * @return Array.
     */
    public function getAuthorizeTokens($code)
    {
        return $this->requestApi(self::API_OAUTH_TOKEN_URL, [
            'code'          => $code,
            'client_id'     => $this->getApiKey(),
            'client_secret' => $this->getApiSecret(),
            'redirect_uri'  => self::API_OAUTH_REDIRECT_URL,
            'scope'         => 'https://www.googleapis.com/auth/analytics.readonly',
            'grant_type'    => 'authorization_code'
        ], 'POST');
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getAccessToken()
    {
        $token = $this->modx->getCacheManager()->get('googleanalytics/access_token');

        if (empty($token)) {
            $data = $this->requestApi(self::API_OAUTH_TOKEN_URL, [
                'refresh_token' => $this->modx->getOption('googleanalytics.refresh_token'),
                'client_id'     => $this->getApiKey(),
                'client_secret' => $this->getApiSecret(),
                'grant_type'    => 'refresh_token'
            ], 'POST');

            if ((int) $data['code'] === 200) {
                if (isset($data['data']['access_token'])) {
                    $this->modx->getCacheManager()->set('googleanalytics/access_token', $data['data']['access_token'], $data['data']['expires_in']);

                    $token = $data['data']['access_token'];
                }
            }
        }

        return $token;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function isAuthorized()
    {
        $data = $this->requestApi(self::API_OAUTH_TOKEN_STATUS_URL, [
            'access_token' => $this->getAccessToken()
        ]);

        if ((int) $data['code'] === 200) {
            return true;
        }

        return false;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getAccounts()
    {
        return $this->request('management/accounts');
    }

    /**
     * @access public.
     * @param String $account.
     * @return Array.
     */
    public function getAccountProperties($account)
    {
        return $this->request('management/accounts/' . $account . '/webproperties');
    }

    /**
     * @access public.
     * @param String $account.
     * @param String $property.
     * @return Array.
     */
    public function getAccountProfiles($account, $property)
    {
        return $this->request('management/accounts/' . $account . '/webproperties/' . $property . '/profiles');
    }

    /**
     * @access public.
     * @param String $account.
     * @param String $property.
     * @param String $profile.
     * @return Array.
     */
    public function getAccountProfile($account, $property, $profile)
    {
        return $this->request('management/accounts/' . $account . '/webproperties/' . $property . '/profiles/' . $profile);
    }

    /**
     * @access public.
     * @param String $type.
     * @param Array $parameters.
     * @return Array.
     */
    public function getData($type, array $parameters = [])
    {
        $profile = $this->googleanalytics->getAuthorizedProfile();

        if ($profile) {
            if (strpos('realtime', $type) !== false) {
                $url    = 'data/realtime';
                $cache  = false;
            } else {
                $url    = 'data/ga';
                $cache  = true;
            }

            if ($cache) {
                $data = $this->modx->cacheManager->get('googleanalytics/' . $profile['property_id_int'] . '/' . $type);

                if ($data) {
                    return $this->getResponse(200, $data);
                }
            }

            $parameters = array_merge([
                'ids'           => 'ga:' . $profile['id'],
                'start-date'    => date('Y-m-d', strtotime('-' . ($this->getHistory() - 1) . ' days')),
                'end-date'      => date('Y-m-d')
            ], $parameters);

            switch ($type) {
                case 'meta':
                case 'meta-summary':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:visitors,ga:pageviews,ga:uniquePageviews,ga:percentNewVisits,ga:exitRate,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:visitBounceRate',
                        'dimensions'    => 'ga:date',
                        'sort'          => 'ga:date'
                    ]);

                    break;
                case 'visits':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:visitors,ga:pageviews,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
                        'dimensions'    => 'ga:date',
                        'sort'          => 'ga:date'
                    ]);

                    break;
                case 'visiters':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits',
                        'dimensions'    => 'ga:visitorType',
                        'sort'          => 'ga:visits'
                    ]);

                    break;
                case 'language':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:visitors,ga:pageviews',
                        'dimensions'    => 'ga:language',
                        'sort'          => '-ga:visits'
                    ]);

                    break;
                case 'country':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:visitors,ga:pageviews',
                        'dimensions'    => 'ga:country',
                        'sort'          => '-ga:visits'
                    ]);

                    break;
                case 'devices':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits',
                        'dimensions'    => 'ga:deviceCategory',
                        'sort'          => 'ga:visits'
                    ]);

                    break;
                case 'sources':
                case 'sources-summary':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:visitors,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
                        'dimensions'    => 'ga:source',
                        'sort'          => '-ga:visits'
                    ]);

                    break;
                case 'content-high':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:entrances,ga:bounces,ga:entranceBounceRate,ga:exits',
                        'dimensions'    => 'ga:pagePath',
                        'sort'          => '-ga:entrances'
                    ]);

                    break;
                case 'content-low':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:exits,ga:bounces,ga:pageviews,ga:exitRate',
                        'dimensions'    => 'ga:pagePath',
                        'sort'          => '-ga:exits'
                    ]);

                    break;
                case 'content-search':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:visits,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
                        'dimensions'    => 'ga:keyword',
                        'sort'          => '-ga:visits'
                    ]);

                    break;
                case 'goals':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:goalStartsAll,ga:goalCompletionsAll',
                        'dimensions'    => 'ga:goalCompletionLocation',
                        'sort'          => '-ga:goalCompletionsAll'
                    ]);

                    break;
                case 'speed':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'ga:avgPageLoadTime,ga:avgDomainLookupTime,ga:avgPageDownloadTime,ga:avgRedirectionTime,ga:avgServerConnectionTime,ga:avgServerResponseTime',
                    ]);

                    break;
                case 'realtime':
                    $parameters = array_merge($parameters, [
                        'metrics'       => 'rt:activeUsers',
                        'dimensions'    => 'rt:userType'
                    ]);

                    break;
            }

            $data = $this->request($url, $parameters);

            if ((int) $data['code'] === 200) {
                $data = $this->parseData($data['data'], $type);

                if ($cache) {
                    $this->modx->getCacheManager()->set('googleanalytics/' . $profile['property_id_int'] . '/' . $type, $data, (int) $this->googleanalytics->getOption('cache_lifetime'));
                }

                return $this->getResponse(200, $data);
            }

            return $this->getResponse($data['code'], $data['message']);
        }

        return $this->getResponse(400, 'API returned incorrect profile.');
    }

    /**
     * @access pubic.
     * @param Array $data.
     * @param String $type.
     * @return Array.
     */
    public function parseData(array $data, $type)
    {
        $output = [];
        $totals = [];

        if (isset($data['totalsForAllResults'])) {
            $totals = $data['totalsForAllResults'];
        }

        if ($type === 'meta') {
            $output = $totals;
        } else if ($type === 'meta-summary') {
            $output = $this->parseDataMeta($totals);
        } else if ($type === 'speed') {
            $output = $this->parseDataSpeed($totals);
        } else {
            if (isset($data['rows'])) {
                foreach ((array) $data['rows'] as $value) {
                    $row = [];

                    foreach ((array) $value  as $key => $column) {
                        $key = substr($data['columnHeaders'][$key]['name'], 3, strlen($data['columnHeaders'][$key]['name']));

                        switch ($key) {
                            case 'date':
                                $row[$key] = date('Y-m-d', strtotime($column));

                                $row[$key . '_formatted'] = strftime('%A %d %B %Y', strtotime($column));
                                $row[$key . '_formatted_short'] = strftime('%d-%m-%Y', strtotime($column));

                                break;
                            case 'visitorType':
                                $row[$key] = $this->modx->lexicon('googleanalytics.' . str_replace(' ', '_', strtolower($column)));

                                break;
                            case 'deviceCategory':
                                $row[$key] = $this->modx->lexicon('googleanalytics.' . str_replace(' ', '_', strtolower($column)));

                                break;
                            case 'avgSessionDuration':
                                $row[$key] = $this->timeFormat($column);

                                break;
                            case 'goalStartsAll':
                            case 'goalCompletionsAll':
                                $row[$key] = (int) $column;

                                if (isset($totals['ga:' . $key])) {
                                    if ((int) $totals['ga:' . $key] === 0) {
                                        $row[$key . 'Percent'] = (int) 0;
                                    } else {
                                        $row[$key . 'Percent'] = (int) round((100 / (int)$totals['ga:' . $key]) * (int)$column, 0);
                                    }
                                }

                                break;
                            default:
                                if (is_numeric($column)) {
                                    $row[$key] = (float) $column;
                                } else {
                                    $row[$key] = $column;
                                }

                                break;
                        }
                    }

                    $output[] = $row;
                }
            }

            if ($type === 'sources-summary') {
                $output = $this->parseDataSources($output);
            }
        }

        return $output;
    }

    /**
     * @access public.
     * @param Array $data.
     * @return Array.
     */
    public function parseDataMeta(array $data = [])
    {
        $output = [];

        $newData = $data;
        $oldData = $this->getData('meta', [
            'start-date'    => date('Y-m-d', strtotime('-' . ($this->getHistory() * 2) . ' days')),
            'end-date'      => date('Y-m-d', strtotime('-' . $this->getHistory() . ' days'))
        ]);

        if ((int) $oldData['code'] === 200) {
            $oldData = $oldData['data'];
        }

        foreach($newData as $key => $value) {
            $data = [];

            switch (substr($key, 3, strlen($key))) {
                case 'visits':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.visits')
                    ];

                    break;
                case 'visitors':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.visitors')
                    ];

                    break;
                case 'pageviews':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.pageviews')
                    ];

                    break;
                case 'uniquePageviews':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.pageviews_unique')
                    ];

                    break;
                case 'percentNewVisits':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.visits_new'),
                        'value' => round($value, 0) . '%'
                    ];

                    break;
                case 'exitRate':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.exitrate'),
                        'value' => round($value, 0) . '%'
                    ];

                    break;
                case 'avgSessionDuration':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.visitors_time'),
                        'value' => $this->timeFormat($value)
                    ];

                    break;
                case 'visitBounceRate':
                    $data = [
                        'name'  => $this->modx->lexicon('googleanalytics.bouncerate'),
                        'value' => round($value, 0) . '%'
                    ];

                    break;
            }

            if (!empty($data)) {
                $output[] = array_merge([
                    'progress'  => round(((int) $value - (int) $oldData[$key]) / ((int) $value / 100), 1),
                    'value'     => round($value, 0)
                ], $data);
            }
        }

        if (!empty($output)) {
            return array_chunk($output, 4);
        }

        return [];
    }

    /**
     * @access public.
     * @param Array $data.
     * @return Array.
     */
    public function parseDataSpeed(array $data = [])
    {
        $output = [];

        foreach ($data as $key => $value) {
            switch (substr($key, 3, strlen($key))) {
                case 'avgPageLoadTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.page_load_time'),
                        'value' => $value
                    ];

                    break;
                case 'avgDomainLookupTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.domain_lookup_time'),
                        'value' => $value
                    ];

                    break;
                case 'avgPageDownloadTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.page_download_time'),
                        'value' => $value
                    ];

                    break;
                case 'avgRedirectionTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.redirection_time'),
                        'value' => $value
                    ];

                    break;
                case 'avgServerConnectionTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.server_connection_time'),
                        'value' => $value
                    ];

                    break;
                case 'avgServerResponseTime':
                    $output[] = [
                        'name'  => $this->modx->lexicon('googleanalytics.server_response_time'),
                        'value' => $value
                    ];

                    break;
            }
        }

        if (!empty($output)) {
            return array_chunk($output, 4);
        }

        return [];
    }

    /**
     * @access public.
     * @param Array $data.
     * @return Array.
     */
    public function parseDataSources(array $data = [])
    {
        $output = [
            'search'    => [
                'name'      => $this->modx->lexicon('googleanalytics.source_search'),
                'visits'    => 0
            ],
            'social'    => [
                'name'      => $this->modx->lexicon('googleanalytics.source_socialmedia'),
                'visits'    => 0
            ],
            'reference' => [
                'name'      => $this->modx->lexicon('googleanalytics.source_reference'),
                'visits'    => 0
            ],
            'direct'    => [
                'name'      => $this->modx->lexicon('googleanalytics.source_direct'),
                'visits'    => 0
            ]
        ];

        foreach ($data as $value) {
            if (strpos($value['source'], 'search') !== false) {
                $output['search']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'google') !== false) {
                $output['search']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'yahoo') !== false) {
                $output['search']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'bing') !== false) {
                $output['search']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'facebook') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'twitter') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'linkedin') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'pinterest') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'plus') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'instagram') !== false) {
                $output['social']['visits'] += (int) $value['visits'];
            } else if (strpos($value['source'], 'direct') !== false) {
                $output['direct']['visits'] += (int) $value['visits'];
            } else {
                $output['reference']['visits'] += (int) $value['visits'];
            }
        }

        return array_values($output);
    }

    /**
     * @access private.
     * @param Integer $timestamp
     * @return String
     */
    private function timeFormat($timestamp)
    {
        $minutes = floor($timestamp / 60);

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        $seconds = round(($timestamp / 60 - $minutes) * 60);

        if ($seconds < 10) {
            $seconds = '0' . $seconds;
        }

        return $minutes . ':' . $seconds;
    }

    /**
     * @access public.
     * @param String $url.
     * @param Array $parameters.
     * @param String $method.
     * @param Array $options.
     * @return Array.
     */
    public function request($url, array $parameters = [], $method = 'GET', array $options = [])
    {
        if (strrpos($url, 'https://') === false && strrpos($url, 'http://') === false) {
            $url = rtrim(self::API_URL, '/') . '/' . rtrim($url, '/');
        }

        $parameters = array_merge($parameters, [
            'access_token' => $this->getAccessToken()
        ]);

        return $this->requestApi($url, $parameters, $method, $options);
    }

    /**
     * @access public.
     * @param String $endpoint.
     * @param Array $parameters.
     * @param String $method.
     * @param Array $options.
     * @return Array.
     */
    public function requestApi($endpoint, $parameters = array(), $method = 'GET', $options = array())
    {
        $options += [
            CURLOPT_HEADER          => false,
            CURLOPT_USERAGENT       => 'Google Analytics v2.0.1',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 10
        ];

        if (strtoupper($method) === 'POST') {
            $options = [
                CURLOPT_URL          => $endpoint,
                CURLOPT_POSTFIELDS   => http_build_query($parameters)
            ] + $options;
        } else {
            $options = [
                CURLOPT_URL         => $endpoint . '?' . http_build_query($parameters)
            ] + $options;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response       = curl_exec($curl);
        $responseInfo   = curl_getinfo($curl);

        curl_close($curl);

        if (!isset($responseInfo['http_code']) || (int) $responseInfo['http_code'] !== 200) {
            $reponseError = json_decode($response, true);

            if ($reponseError) {
                if (isset($reponseError['error']['message'])) {
                    return $this->getResponse($responseInfo['http_code'], $reponseError['error']['message']);
                }
            }

            return $this->getResponse($responseInfo['http_code'], 'API returned incorrect HTTP code.');
        }

        return $this->getResponse(200, json_decode($response, true));
    }

    /**
     * @access public.
     * @param Integer $code.
     * @param Array|String $data.
     * @return Array.
     */
    public function getResponse($code, $data)
    {
        if ((int) $code === 200) {
            return [
                'code'  => (int) $code,
                'data'  => $data
            ];
        }

        return [
            'code'      => (int) $code,
            'message'   => $data
        ];
    }
}
