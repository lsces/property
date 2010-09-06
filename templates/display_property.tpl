<div class="body">
	<div class="content">

		{if isset($propertyInfo.usn) && ($propertyInfo.usn <> '') }
		<div class="row">
			{formlabel label="USN" for="usn"}
			{forminput}
				{$propertyInfo.usn|escape} 
			{/forminput}
		</div>
		{/if}
		{if isset($propertyInfo.organisation) && ($propertyInfo.organisation <> '') }
		<div class="row">
			{formlabel label="Organisation" for="organisation"}
			{forminput}
				{$propertyInfo.organisation|escape} 
			{/forminput}
		</div>
		{/if}
		{if isset($propertyInfo.dob) && ($propertyInfo.dob <> '') }
		<div class="row">
			{formlabel label="Date of Birth" for="dob"}
			{forminput}
				{$propertyInfo.dob|bit_long_date}
			{/forminput}
		</div>
		{/if}
		{if isset($propertyInfo.nino) && ($propertyInfo.nino <> '') }
		<div class="row">
			{formlabel label="National Insurance Number" for="nino"}
			{forminput}
				{$propertyInfo.nino|escape}
			{/forminput}
		</div>
		{/if}
		{include file="bitpackage:property/display_address.tpl"}
		{jstabs}
			{include file="bitpackage:property/list_xref.tpl"}
			{include file="bitpackage:property/list_tasks.tpl"}
			{include file="bitpackage:property/list_contacts.tpl"}
		{/jstabs}
	</div><!-- end .content -->
</div><!-- end .body -->
