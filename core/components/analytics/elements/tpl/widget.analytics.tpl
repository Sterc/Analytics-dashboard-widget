<div id="analytics-panel-widget">
    <div id="tab1" class="x-hide-display">
        <div id="tab1-holder">
        <table class="classy" style="width:100%">
        
        <thead>
        <tr>
            <th>{$_langs.date}</th>
            <th>{$_langs.visits}</th>
            <th>{$_langs.unique_visitors}</th>
            <th>{$_langs.pageviews_visits}</th>
            <th>{$_langs.pageviews}</th>
            <th>{$_langs.site_time}</th>
            <th>{$_langs.new_visits}</th>
            <th>{$_langs.bounce_rate}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$visitsarr item=visits}
        <tr class="{cycle values=',odd'}">
                <td>{$visits.date}</td>
                <td>{$visits.visits}</td>
                <td>{$visits.visitors}</td>
                <td>{$visits.pageviewsPerVisit|number_format:2:",":"."}  %</td>
                <td>{$visits.pageviews}</td>
                <td>{$visits.vgTimeOnSite}</td>
                <td>{$visits.percentNewVisits|number_format:2:",":"."} %</td>
                <td>{$visits.visitBounceRate|number_format:2:",":"."} %</td>
        </tr>
        {foreachelse}
        <tr>
            <th colspan="5">{$_langs.connection_error}</th>
        </tr>
        {/foreach}
        <tr>
                <td>{$_langs.total}</td>
                <td>{$general.visits}</td>
                <td>{$general.visitors}</td>
                <td>{$general.pageviewsPerVisit|number_format:2:",":"."} %</td>
                <td>{$general.pageviews}</td>
                <td>{$general.vgTimeOnSite}</td>
                <td>{$general.percentNewVisits|number_format:2:",":"."} %</td>
                <td>{$general.visitBounceRate|number_format:2:",":"."} %</td>
        </tr>
        </tbody>
        </table>
        </div>
    </div>
    <div id="tab2" class="x-hide-display">
				
        <div id="tab2-holder">
            <h2>{$_langs.top_sources}</h2>
            <table class="classy" style="width: 48%; float:left; margin-right:2%;">
            
	            <thead>
	            <tr>
	                <th>{$_langs.sources}</th>
	                <th>{$_langs.visits}</th>
	                <th>% {$_langs.new_visits}</th>
	            </tr>
	            </thead>
	            <tbody>
	            {$i = 0}
	            {foreach from=$toptrafficsource item=toptraffic}
		            {if $i == 5}{break}{/if}
			            <tr class="{cycle values=',odd'}">
			                    <td>{$toptraffic.source}</td>
			                    <td>{$toptraffic.visits}</td>
			                    <td>{$toptraffic.percentNewVisits|number_format:2:",":"."} %</td>
			            </tr>
		            {$i = $i+1}
		            {foreachelse}
		            <tr>
		                <th colspan="5">{$_langs.connection_error}</th>
		            </tr>
	            {/foreach}
            </tbody>
            </table>
            <table class="classy" style="width: 48%; float:left; margin-right:2%;">
	            <thead>
	            <tr>
	                <th>{$_langs.keywords}</th>
	                <th>{$_langs.visits}</th>
	                <th>% {$_langs.new_visits}</th>
	            </tr>
	            </thead>
	            <tbody>

	            {$i = 0}
	            {foreach from=$keywords item=keyword}
		            {if $keyword.keyword != '(not set)'}
			            {if $i == 5}{break}{/if}
			            <tr class="{cycle values=',odd'}">
				                    <td>{$keyword.keyword}</td>
				                    <td>{$keyword.visits}</td>
				                    <td>{$keyword.percentNewVisits|number_format:2:",":"."} %</td>
				            </tr>
			            {$i = $i+1}
		            {/if}
	            {foreachelse}
	            <tr>
	                <th colspan="5">{$_langs.connection_error}</th>
	            </tr>
	            {/foreach}
            </tbody>
            </table>
            <p style="clear:both;"></p>
            <h2>{$_langs.referring_sites}</h2>
            <table class="classy" style="width: 100%;">
            
            <thead>
            <tr>
                <th>{$_langs.sources}</th>
                <th>{$_langs.visits}</th>
                <th>{$_langs.pages_visits}</th>
                <th>{$_langs.average_site_time}</th>
                <th>% {$_langs.new_visits}</th>
                <th>{$_langs.bounce_rate}</th>
            </tr>
            </thead>
            <tbody>
            {$i = 0}
            {foreach from=$toptrafficsource item=trafficreffered}
                 {if $trafficreffered.source != 'google' && $trafficreffered.source != '(direct)' && $trafficreffered.source != 'localhost' && $trafficreffered.source != 'bing' && $trafficreffered.source != 'google.nl'}
                 {if $i == 10}{break}{/if}

            <tr class="{cycle values=',odd'}">
                    <td>{$trafficreffered.source}</td>
                    <td>{$trafficreffered.visits}</td>
                    <td>{$trafficreffered.pageviewsPerVisit|number_format:2:",":"."} %</td>
                    <td>{$trafficreffered.vgTimeOnSite}</td>
                    <td>{$trafficreffered.percentNewVisits|number_format:2:",":"."} %</td>
                    <td>{$trafficreffered.visitBounceRate|number_format:2:",":"."} %</td>
            </tr>
            {$i = $i+1}

            {/if}
            {foreachelse}
            <tr>
                <th colspan="5">{$_langs.connection_error}</th>
            </tr>
            {/foreach}

            </tbody>
            </table>
        </div>
    </div>
    <div id="tab3" class="x-hide-display">
        <h2>{$_langs.top_landing_pages}</h2>
            <table class="classy" style="width: 100%;">
        <thead>
        <tr>
            <th style="width: 40%;">{$_langs.page}</th>
            <th style="width: 20%;">{$_langs.entrances}</th>
            <th style="width: 20%;">{$_langs.bounces}</th>
            <th style="width: 20%;">{$_langs.bounce_rate}</th>
        </tr>
        </thead>
        <tbody>
        {$i = 0}
        {foreach from=$toplandingspages item=toppage}
        {if $i == 10}{break}{/if}
        <tr class="{cycle values=',odd'}">
                <td>{$toppage.pagePath}</td>
                <td>{$toppage.entrances}</td>
                <td>{$toppage.bounces}</td>
                <td>{$toppage.entranceBounceRate|number_format:2:",":"."} %</td>

        </tr>
         {$i = $i+1}
        {foreachelse}
        <tr>
            <th colspan="5">{$_langs.connection_error}</th>
        </tr>
        {/foreach}
        </tbody>
        </table>
        <h2>{$_langs.top_exit_pages}</h2>
            <table class="classy" style="width: 100%;">
        <thead>
        <tr>
            <th style="width: 40%;">{$_langs.page}</th>
            <th style="width: 20%;">{$_langs.exits}</th>
            <th style="width: 20%;">{$_langs.pageviews}</th>
            <th style="width: 20%;">% {$_langs.exit}</th>
        </tr>
        </thead>
        <tbody>
        {$i = 0}
         {foreach from=$topexitpages item=exitpage}
        {if $i == 10}{break}{/if}
        <tr class="{cycle values=',odd'}">
                <td>{$exitpage.pagePath}</td>
                <td>{$exitpage.exits}</td>
                <td>{$exitpage.pageviews}</td>
                <td>{$exitpage.exitRate|number_format:2:",":"."} %</td>

        </tr>
         {$i = $i+1}
        {foreachelse}
        <tr>
            <th colspan="5">{$_langs.connection_error}</th>
        </tr>
        {/foreach}
        </tbody>
        </table>

    </div>
    <div id="tab4" class="x-hide-display">
        <div id="goals-holder">
        <h2>{$_langs.goals_part1} {$general.allGoals} {$_langs.goals_part2}</h2>
        <table class="classy" style="width: 48%;">
        <thead>
        <tr>
            <th>{$_langs.goals}</th>
            <th>{$_langs.conversions}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$goalstable item=goal}
        <tr class="{cycle values=',odd'}">
            <td>{$goal.goalname}</td>
            <td>{$goal.completions}</td>
        </tr>
         {/foreach}
        </tbody>
        </table>
        </div>

    </div>
    <div id="tab5" class="x-hide-display">
            <table class="classy" style="width: 100%;">
        <thead>
        <tr>
            <th>{$_langs.keywords}</th>
            <th>{$_langs.visits}</th>
            <th>{$_langs.pages_visits}</th>
            <th>{$_langs.average_site_time}</th>
            <th>% {$_langs.new_visits}</th>
            <th>{$_langs.bounce_rate}</th>
        </tr>
        </thead>
        <tbody>
        {$i = 0}
         {foreach from=$keywords item=keyword}
         {if $keyword.keyword != '(not set)'}
         {if $i == 20}{break}{/if}
        <tr class="{cycle values=',odd'}">
                <td>{$keyword.keyword}</td>
                <td>{$keyword.visits}</td>
                <td>{$keyword.pageviewsPerVisit|number_format:2:",":"."} %</td>
                <td>{$keyword.vgTimeOnSite}</td>
                <td>{$keyword.percentNewVisits|number_format:2:",":"."} %</td>
                <td>{$keyword.visitBounceRate|number_format:2:",":"."} %</td>
        </tr>
        {$i = $i+1}
        {/if}
        {foreachelse}
        <tr>
            <th colspan="5">{$_langs.connection_error}</th>
        </tr>
        {/foreach}
        </tbody>
        </table>

    </div>
    <div id="tab6" class="x-hide-display">
            <table class="classy" style="width: 100%;">
        <thead>
        <tr>
            <th>{$_langs.search_keyword}</th>
            <th>{$_langs.search_uniques}</th>
            <th>{$_langs.search_result_views}</th>
            <th>% {$_langs.search_exits}</th>
            <th>{$_langs.search_duration}</th>
            <th>{$_langs.search_depth}</th>
        </tr>
        </thead>
        <tbody>
        {$i = 0}
         {foreach from=$sitesearches item=sitesearch}
         {if $i == 20}{break}{/if}
        <tr class="{cycle values=',odd'}">
                <td>{$sitesearch.searchKeyword}</td>
                <td>{$sitesearch.searchUniques}</td>
                <td>{$sitesearch.searchResultViews}</td>
                <td>{$sitesearch.searchExitRate|number_format:2:",":"."}  %</td>
                <td>{$sitesearch.searchDuration}</td>
                <td>{$sitesearch.searchDepth}</td>
        </tr>
        {$i = $i+1}
        {foreachelse}
        <tr>
            <th colspan="5">{$_langs.no_result}</th>
        </tr>
        {/foreach}
        </tbody>
        </table>

    </div>
</div>
