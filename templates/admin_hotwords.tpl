<div class="floaticon">{bithelp}</div>

<div class="admin hotwords">
	<div class="header">
		<h1>{tr}Admin Hotwords{/tr}</h1>
	</div>

	<div class="body">

		{form legend="Add Hotword"}
			<div class="row">
				{formlabel label="Word" for="word"}
				{forminput}
					<input type="text" name="word" id="word" />
					{formhelp note=''}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="URL" for="url"}
				{forminput}
					<input type="text" name="url" id="url" />
					{formhelp note=''}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="add" value="{tr}Add{/tr}" />
			</div>
		{/form}

		{minifind}

		<table class="data">
			<tr>
				<th><a href="{$gBitLoc.HOTWORDS_PKG_URL}admin/index.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'word_desc'}word_asc{else}word_desc{/if}">{tr}Word{/tr}</a></th>
				<th><a href="{$gBitLoc.HOTWORDS_PKG_URL}admin/index.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'url_desc'}url_asc{else}url_desc{/if}">{tr}URL{/tr}</a></th>
				<th>{tr}action{/tr}</th>
			</tr>
				{section name=user loop=$words}
					<tr class="{cycle values="odd,even"}">
						<td>{$words[user].word}</td>
						<td>{$words[user].url}</td>
						<td class="actionicon"><a href="{$gBitLoc.HOTWORDS_PKG_URL}admin/index.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$words[user].word}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this hotword?{/tr}')" title="Click here to delete this hotword">{biticon ipackage=liberty iname="delete" iexplain="remove"}</a>&nbsp;&nbsp;</td>
					</tr>
				{sectionelse}
					<tr class="norecords"><td colspan="3">
						{tr}No records found{/tr}
					</td></tr>
				{/section}
		</table>

		{pagination}

	</div><!-- end .body -->
</div><!-- end .hotwords -->
