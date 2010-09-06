{strip}
<ul>
	<li><a class="item" href="{$smarty.const.PROPERTY_PKG_URL}list.php">{biticon iname="format-justify-fill" iexplain="List Properties" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.PROPERTY_PKG_URL}list_postcodes.php">{biticon iname="format-justify-fill" iexplain="List Postcodes" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.NLLG_PKG_URL}list_county.php?list=county">{biticon iname="format-justify-fill" iexplain="List of Counties" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.NLPG_PKG_URL}list_county.php?list=local">{biticon iname="format-justify-fill" iexplain="List of Local Authorities" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.NLPG_PKG_URL}list_county.php?list=ward">{biticon iname="format-justify-fill" iexplain="List of Wards" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.NLPG_PKG_URL}list_county.php?list=parish">{biticon iname="format-justify-fill" iexplain="List of Parishes" ilocation=menu}</a></li>
	<li><a class="item" href="{$smarty.const.NLPG_PKG_URL}list_county.php?list=blpu_class">{biticon iname="format-justify-fill" iexplain="List of Property Classifications" ilocation=menu}</a></li>
	{if $gBitUser->hasPermission('p_contact_admin')}
		<li><a class="item" href="{$smarty.const.PROPERTY_PKG_URL}load_properties.php">{tr}Load Property List Dump{/tr}</a></li>
		<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=property">{tr}Admin contacts{/tr}</a></li>
	{/if}
</ul>
{/strip}
