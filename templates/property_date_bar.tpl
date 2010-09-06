<div class="floaticon">
  {if $lock}
    {biticon ipackage="icons" iname="locked" iexplain="locked"}{$info.editor|userlink}
  {/if}
  {if $print_page ne 'y'}
    {if !$lock}
      {if $gBitUser->hasPermission('p_edit_property')}
		<a href="edit.php?content_id={$propertyInfo.content_id}" {if $beingEdited eq 'y'}{popup_init src="`$gBitLoc.THEMES_PKG_URL`overlib.js"}{popup text="$semUser" width="-1"}{/if}>{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
      {/if}
    {/if}
    <a title="{tr}print{/tr}" href="print.php?content_id={$propertyInfo.content_id}">{biticon ipackage="icons" iname="document-print" iexplain="print"}</a>
      {if $gBitUser->hasPermission('p_remove_property')}
        <a title="{tr}remove this property{/tr}" href="remove_property.php?content_id={$propertyInfo.content_id}">{biticon ipackage="icons" iname="edit-delete" iexplain="delete"}</a>
      {/if}
  {/if} {* end print_page *}
</div> {*end .floaticon *}
<div class="date">
	{tr}Created by{/tr} {displayname user=$propertyInfo.creator_user user_id=$propertyInfo.user_id real_name=$propertyInfo.creator_real_name}, {tr}Last modification by{/tr} {displayname user=$propertyInfo.modifier_user user_id=$propertyInfo.modifier_user_id real_name=$propertyInfo.modifier_real_name} on {$propertyInfo.last_modified|bit_long_datetime}
</div>
