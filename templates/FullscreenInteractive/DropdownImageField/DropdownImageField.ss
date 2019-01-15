<select $AttributesHTML>
<% loop $Options %>
	<option data-img-src="$Image.Link" value="$Value.XML"<% if $Selected %> selected="selected"<% end_if %><% if $Disabled %> disabled="disabled"<% end_if %>><% if $Title = "--nbsp" %>&nbsp;<% else %>$Title.XML<% end_if %></option>
<% end_loop %>
</select>
