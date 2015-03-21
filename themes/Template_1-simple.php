<?php
/*
 * Template: Simple
 */
?>
<script type="text/html" id='relatify_output'>
    <div  class="full_box" >
        <div class="related-content-title" >
            <%= items.title %> <%= items.width %>
        </div>
        <div class="content-list-wrapper">
            <% _.each(items.posts,function(item,key,list){ %>
            <div class="single_relatified_item" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %>">
                <a href="<%= item.permalink %>">
                    <% if(item.image != "") { %>
<div style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %> border: 2px solid #d7d7d7;">
                    <img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>"/>
</div>
                         <% } %>
                         <div class="single_relatified_post_title" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %>">
                        <p><%= item.post_title %></p>
                    </div>
                </a>
            </div>
            <% }); %>
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
    .single_relatified_item {
        float: left;
        width: 25%;
    }
    .single_relatified_item img {
        border-radius: 0;        
        width: 100%;
        height: 100px;
	padding: 0px !important;
	border: 0 none !important;
    }
    .single_relatified_item > a {
        border-bottom: 0 none !important;
        text-decoration: none !important;
    }    
    .single_relatified_item:first-child {
        margin-left: 0px !important;
    }
    .single_relatified_post_title {
        background-color: #3d3d3d;
        height: 38px;
        position: relative;
	border: 2px solid #d7d7d7;
    }
    .content-list-wrapper {
        float: left;
        width: 100%;
    }
    .relatify_love {
        float: right;
        font-size: 12px;
        padding-top: 5px;
    }
	.single_relatified_post_title p {
  	color: #fff;
  	font-size: 12px !important;
  	font-weight: normal !important;
  	height: 30px;
  line-height: 1.2;
  margin: 0 !important;
  overflow: hidden;
  padding: 5px !important;
}
.full_box {
  overflow: auto;
}
    .related-content-title {
        margin-bottom: 10px;
    }
</style>