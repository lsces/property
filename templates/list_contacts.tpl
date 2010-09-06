

		{assign var=propertycnt value=$propertiesInfo.property|@count}
		{jstab title="Contact ($propertycnt)"}
		{legend legend="Contact"}
		<div class="row">
			{formlabel label="Contacts" for="contact"}
			{forminput}
			<table>
				<caption>{tr}List of contact records{/tr}</caption>
				<thead>
					<tr>
						<th>Data</th>
						<th>Tag</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
					{section name=contact loop=$propertyInfo.contacts}
						<tr class="{cycle values="even,odd"}" title="{$propertyInfo.contacts[contact].title|escape}">
							<td>
								{$propertyInfo.contacts[contact].ticket_ref|bit_long_date} - {$propertyInfo.contacts[contact].ticket_no}
							</td>
							<td>
								{$propertyInfo.contacts[contact].tags|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_ticket.php" ibiticon="icons/accessories-text-editor" ticket_id=$propertyInfo.contacts[contact].ticket_id}
								</span>
								<label for="ev_{$propertyInfo.contacts[contact].ticket_no}">	
									{$propertyInfo.contacts[contact].staff_id}
								</label>
							</td>
						</tr>
					{sectionelse}
						<tr class="norecords">
							<td colspan="3">
								{tr}No records found{/tr}
							</td>
						</tr>
					{/section}
				</tbody>
			</table>
			{/forminput}
		</div>
			{/legend}
			{/jstab}
