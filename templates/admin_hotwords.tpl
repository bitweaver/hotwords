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
					{formhelp note='The word you want to look for and point to a given URL e.g.: google'}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="URL" for="url"}
				{forminput}
					<input type="text" name="url" id="url" />
					{formhelp note='The URL you wish to point the above word to e.g.: http://www.google.com/'}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="add" value="{tr}Add{/tr}" />
			</div>
		{/form}

		{minifind}

		<table class="data">
			<tr>
				<th>{smartlink isort=word ititle="Word"}</th>
				<th>{smartlink isort=url  ititle="URL"}</th>
				<th>{tr}Action{/tr}</th>
			</tr>
			{foreach from=$words item=word}
				<tr class="{cycle values="odd,even"}">
					<td>{$word.word}</td>
					<td>{$word.url}</td>
					<td class="actionicon"><a href="{$smarty.const.HOTWORDS_PKG_URL}admin/index.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$word.word}" onclick="return confirm('{tr}Are you sure you want to delete this hotword?{/tr}')" title="Click here to delete this hotword">{biticon ipackage="icons" iname="edit-delete" iexplain="remove"}</a>&nbsp;&nbsp;</td>
				</tr>
			{foreachelse}
				<tr class="norecords"><td colspan="3">{tr}No records found{/tr}</td></tr>
			{/foreach}
		</table>

		{pagination}

	</div><!-- end .body -->
</div><!-- end .hotwords -->
