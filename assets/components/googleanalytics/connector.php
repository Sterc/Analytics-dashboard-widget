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
	
	require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
	
	require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
	require_once MODX_CONNECTORS_PATH.'index.php';
	
	$instance = $modx->getService('googleanalytics', 'GoogleAnalytics', $modx->getOption('googleanalytics.core_path', null, $modx->getOption('core_path').'components/googleanalytics/').'model/googleanalytics/');

	if ($instance instanceOf GoogleAnalytics) {
		$modx->request->handleRequest(array(
			'processors_path' 	=> $instance->config['processors_path'],
			'location' 			=> ''
		));
	}

?>