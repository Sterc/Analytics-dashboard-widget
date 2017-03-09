<div id="ga-panel-home-div">
	<p>{$_langs.auth_false}</p>
	<p>{$_langs.auth_auth}<a class="login" href="{$authUrl}" target="_blank">{$_langs.auth_get}</a></p>
	<br>
	<form name="access" action="[[+redirect_url]]" method="post">
		<label for="auth_code">{$_langs.auth_paste}:</label>
		<br>
		<div class="x-form-item">
			<input type="text" class="x-form-text x-form-field" id="auth_code" name="auth_code" size="105" autocomplete="off">
			<span class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon" unselectable="on" >
				<em><button type="submit" class="x-btn-text" name="authorize">{$_langs.auth}</button></em>
			</span>
		</div>
	</form>
	{if $error}<p style="color:red;"><br/>{$error}</p>{/if}

</div>