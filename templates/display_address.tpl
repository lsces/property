		<div class="row">
			{formlabel label="Address" for="lpi"}
			{forminput}
				{if isset($propertyInfo.sao) && ($propertyInfo.sao <> '') }
					{$propertyInfo.sao},&nbsp;{/if}
				{if isset($propertyInfo.pao) && ($propertyInfo.pao <> '') }
					{$propertyInfo.pao},<br />{/if}
				{if isset($propertyInfo.number) && ($propertyInfo.number <> '') }
					{$propertyInfo.number},<br />{/if}
				{if isset($propertyInfo.street) && ($propertyInfo.street <> '') }
					{$propertyInfo.street},<br />{/if}
				{if isset($propertyInfo.locality) && ($propertyInfo.locality <> '') }
					{$propertyInfo.locality},&nbsp;{/if}
				{if isset($propertyInfo.town) && ($propertyInfo.town <> '') }
					{$propertyInfo.town},&nbsp;{/if}
				{if isset($propertyInfo.county) && ($propertyInfo.county <> '') }
					{$propertyInfo.county},&nbsp;{/if}
				{$propertyInfo.postcode}&nbsp;&nbsp;
			{/forminput}
		</div>
		{if isset($propertyInfo.x_coordinate) && ($propertyInfo.x_coordinate <> '') }
		<div class="row">
			{formlabel label="Visual Centre Coordinates" for="street_start_x"}
			{forminput}
				Easting: {$propertyInfo.x_coordinate|escape} Northing: {$propertyInfo.y_coordinate|escape}
				&nbsp;&lt;<a href="http://www.multimap.com/maps/?map={$propertyInfo.prop_lat},{$propertyInfo.prop_lng}|17|4&loc=GB:{$propertyInfo.prop_lat}:{$propertyInfo.prop_lng}:17" title="{$propertyInfo.title}">
					Multimap
				</a>&gt;<br />
				{$propertyInfo.rpa|escape}
			{/forminput}
		</div>
		{/if}
		
