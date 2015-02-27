<?php
/*
 * Template: Simple
 */
?>
<script type="text/html" id='relatify_output'>
    <div  class="full_box" >
        <div class="related-content-title" >
            <%= items.title %>
        </div>
        <div class="content-list-wrapper">
            <ul>
                <% _.each(items.posts,function(item,key,list){ %>
                <li>
                    <% if(item.image != "") { %>
                    <div class="rel_thumb">
                        <img src="<%= item.image %>" />
                    </div>
                    <% } %>
                    <div class="rel_content">
                        <p><b><a href="<%= item.permalink %>"><%= item.post_title %></a></b></p>
                        <div class="rel_excerpt">
                            <%= item.post_content.substr(0, 250).replace( /<.*?>/g, '' ) %> ...
                        </div>
                    </div>
                </li>
                <% }); %>
            </ul>
        </div>
    </div>
</script>

<!-- Create your target -->
<div id="relatify_output_target"></div>

<style>
    .full_box{
        margin: 20px 0 35px;
        overflow: hidden;
    }
    .rel_thumb{
        width: 25%;
        float: left;
    }
    .rel_content{
        float: right;
        width: 72%;
    }
    .content-list-wrapper li{
        overflow: hidden;
        margin: 0 0 15px;
        padding: 0;
    }
    .content-list-wrapper ul{
        margin: 0;
        padding: 0;
        list-style-type: none;
    }
    .content-list-wrapper p{
        margin-bottom: 10px;
    }
</style>