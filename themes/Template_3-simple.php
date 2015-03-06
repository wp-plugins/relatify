<?php
/*
 * Template: Simple
 */
?>
<script type="text/html" id='relatify_output'>
    <div  class="related-content" >
        <div class="related-content-title" >
            <%= items.title %>
        </div>
        <div class="full_box">
            <% _.each(items.posts,function(item,key,list){ %>
            <div class="single_relatified_item">
                <% if(item.image != "") { %>
                <a href="<%= item.permalink %>"><img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>" /></a>
                <% } %>
                <div class="single_relatified_post_title">
                    <a href="<%= item.permalink %>">
                        <p><%= item.post_title %></p>
                    </a>
                </div>
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
        border: 10px solid #efefef;
    }
    .single_relatified_item:first-child {
    }
    .single_relatified_item img {
    }
    .single_relatified_post_title {
        background: none repeat scroll 0 0 #535353;
        height: 38px;
        padding: 2px 5px;
        position: relative;
    }
    .single_relatified_post_title p {
        color: #ffffff;
        font-size: 12px;
        height: 28px;
        line-height: 1.2;
        margin-bottom: 0 !important;
        overflow: hidden;
    }
    .full_box {
    }
    .related-content-title {
        margin-bottom: 10px;
    }
</style>