<form $FormAttributes>
    <%--Check for a message--%>
    <% if $Message %>
        <p id="{$FormName}_error" class="message $MessageType">$Message</p>
    <% else %>
        <p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
    <% end_if %>
    <fieldset>
        <%--Display all the fields--%>
        <% loop $Fields %>
            $FieldHolder
        <% end_loop %>

        <%--Display all the actions--%>
        <% loop $Actions %>
            $Field
        <% end_loop %>
    </fieldset>
</form>
