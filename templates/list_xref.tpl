
		{assign var=xrefcnt value=$propertyInfo.xref|@count}
		{jstab title="Information ($xrefcnt)"}
		{legend legend="Information References"}
		<div class="row">
			{formlabel label="Cross reference" for="xref"}
			{forminput}
			<table>
				<caption>{tr}List of linked references{/tr}</caption>
				<thead>
					<tr>
						<th>Information</th>
						<th>Data</th>
						<th>Updated</th>
						<th>Reference</th>
					</tr>
				</thead>
				<tbody>
					{section name=xref loop=$propertyInfo.xref}
						<tr class="{cycle values="even,odd"}" title="{$list[county].title|escape}">
							<td>
								{$propertyInfo.xref[xref].source_title|escape}
							</td>
							<td>
								{$propertyInfo.xref[xref].data|escape}
							</td>
							<td>
								{$propertyInfo.xref[xref].last_update_date|bit_long_date}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_xref.php" ibiticon="icons/view-fullscreen" content_id=$propertyInfo.content_id source=$propertyInfo.xref[xref].source xorder=$propertyInfo.xref[xref].xorder}
								</span>
								<span class="actionicon">
									{smartlink ititle="Edit" ifile="edit_xref.php" ibiticon="icons/accessories-text-editor" content_id=$propertyInfo.content_id source=$propertyInfo.xref[xref].source xorder=$propertyInfo.xref[xref].xorder}
								</span>
								<span class="actionicon">
									{smartlink ititle="View" ifile="../contact/display_contact.php" ibiticon="icons/face-plain" contact_id=$propertyInfo.xref[xref].cross_reference}
								</span>
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
