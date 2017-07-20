<div id="ga-panel-home-div">
    <h2>Google Analytics {$_langs.settings}</h2>
    {if $userPermission}
    <form name="form" id="form" method="post" action="{$managerUrl}">
        <p>
            <label for='tableId'>{$_langs.site_select}</label>
            <select name="siteSelect" id="siteSelect">
            {foreach $profiles as $profile}
                <option value="{$profile.title}|{$profile.accountId}|{$profile.profileId}|{$profile.webPropertyId}">{$profile.title}</option>
            {/foreach}
            </select>
        </p>
        <p>
            <input type="submit" value="Submit" onClick="showMask('');"/>
            <script type="text/javascript">
            {literal}
            function showMask(){
                var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"{/literal}{$_langs.pleasewait}{literal}"});
                myMask.show();
            }
            {/literal}
            </script>
        </p>
    </form>
    {else}
        <p>{$_langs.settings_not_available}</p>
    {/if}
</div>