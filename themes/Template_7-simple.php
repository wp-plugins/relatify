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
                <li class="single_related_item">
                    <% if(item.image != "") { %>
                    <div class="rel_thumb" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %>">
                        <img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>" />
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
        <% if( items.love == 1 ) { %>
        <div class="relatify_love">
            <p>Related contents by <a href="<%= items.love_url %>" target="_blank">Relatify</a></p>
        </div>
        <% } %>
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
        float: left;
        padding-right: 10px;
    }
    .rel_content{
    }
    .rel_content a {
  	border-bottom: 0 none !important;
    text-decoration: none !important;
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
    .single_relatified_post_title .single_relatified_excerpt{
        font-weight: normal !important;
    }
    .single_related_item{
	border-bottom: 1px solid #d7d7d7;
  	padding-top: 10px;
    }
    .single_relatified_item > a {
        border-bottom: 0 none !important;
        text-decoration: none !important;
    }

    .single_related_item:first-child {
  	border-top: 1px solid #d7d7d7;
  	padding-top: 10px;
	margin-top: 20px;
    padding-bottom: 10px;
    }
    .relatify_love {
  	float: right;
  	font-size: 12px;
    }
    .relatify_love a {
  	border-bottom: 0 none;
    }
</style>