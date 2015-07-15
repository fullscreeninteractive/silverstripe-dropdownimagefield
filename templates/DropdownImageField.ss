<select $AttributesHTML>
<% loop $Options %>
	<option data-img-src="$Image.Link" value="$Value.XML"<% if $Selected %> selected="selected"<% end_if %><% if $Disabled %> disabled="disabled"<% end_if %>>$Title.XML</option>
<% end_loop %>
</select>
