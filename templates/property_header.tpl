<div class="header">
{if $is_categorized eq 'y' and $gBitSystem->isFeatureActive('package_categories') and $gBitSystem->isFeatureActive('feature_categorypath')}
<div class="category">
  <div class="path">{$display_catpath}</div>
</div> {* end category *}
{/if}

	<h1>{$propertyInfo.property_id}&nbsp;-&nbsp;
		{if isset($propertyInfo.organisation) && ($propertyInfo.organisation <> '') }
		{$propertyInfo.organisation}
		{else}
		{$propertyInfo.prefix}&nbsp;
		{$propertyInfo.forename}&nbsp;
		{$propertyInfo.surname}
		{/if}</h1>
	<div class="description">{$propertyInfo.description}</div>

</div> {* end .header *}
