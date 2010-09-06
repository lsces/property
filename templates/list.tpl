{strip}

<div class="floaticon">{bithelp}</div>

<div class="listing properties">
	<div class="header">
		<h1>{tr}Properties{/tr}</h1>
	</div>

	<div class="body">

		{include file="bitpackage:property/display_list_header.tpl"}

		<div class="navbar">
			<ul>
				<li>{biticon ipackage="icons" iname="emblem-symbolic-link" iexplain="sort by"}</li>
				<li>{smartlink ititle="Property Number" isort="content_id" idefault=1 iorder=desc ihash=$listInfo.ihash}</li>		
				<li>{smartlink ititle="Organisation" isort="organisation" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Address" isort="street" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Town" isort="town" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Postcode" isort="postcode" ihash=$listInfo.ihash}</li>
			</ul>
		</div>

		<ul class="clear data">
			{section name=content loop=$listproperties}
				<li class="item {cycle values='odd,even'}">
						<a href="display_property.php?property_id={$listproperties[content].property_id}" title="ci_{$listproperties[content].property_id}">
						{$listproperties[content].title} 
						</a>&nbsp;&nbsp;&nbsp;
						{if isset($listproperties[content].organisation) && ($listproperties[content].organisation <> '') }Company: {$listproperties[content].organisation}&nbsp;&nbsp;{/if} 
						{if isset($listproperties[content].dob) && ($listproperties[content].dob <> '')  }DOB: {$listproperties[content].dob}&nbsp;&nbsp;{/if}
						{if isset($listproperties[content].nino) && ($listproperties[content].nino <> '') }NI: {$listproperties[content].nino}&nbsp;&nbsp;{/if}
						
					<div class="footer">
						{if isset($listproperties[content].uprn) && ($listproperties[content].uprn <> '') }UPRN: {$listproperties[content].uprn}&nbsp;&nbsp;{/if}
						{tr}Address{/tr}: 
						{if isset($listproperties[content].sao) && ($listproperties[content].sao <> '') }
							{$listproperties[content].sao},&nbsp;{/if}
						{if isset($listproperties[content].pao) && ($listproperties[content].pao <> '') }
							{$listproperties[content].pao},&nbsp;{/if}
						{if isset($listproperties[content].number) && ($listproperties[content].number <> '') }
							{$listproperties[content].number},&nbsp;{/if}
						{if isset($listproperties[content].street) && ($listproperties[content].street <> '') }
							{$listproperties[content].street},&nbsp;{/if}
						{if isset($listproperties[content].locality) && ($listproperties[content].locality <> '') }
							{$listproperties[content].locality},&nbsp;{/if}
						{if isset($listproperties[content].town) && ($listproperties[content].town <> '') }
							{$listproperties[content].town},&nbsp;{/if}
						{if isset($listproperties[content].county) && ($listproperties[content].county <> '') }
							{$listproperties[content].county},&nbsp;{/if}
						{$listproperties[content].postcode}&nbsp;&nbsp;
						{tr}Links{/tr}: {$listproperties[content].links|default:0}
						{tr}Enquiries{/tr}: {$listproperties[content].enquiries|default:0}
					</div>

					<div class="clear"></div>
				</li>
			{sectionelse}
				<li class="item norecords">
					{tr}No records found{/tr}
				</li>
			{/section}
		</ul>

		{pagination}
	</div><!-- end .body -->
</div><!-- end .properties -->

{/strip}
