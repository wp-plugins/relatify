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
            <% _.each(items.posts,function(item,key,list){ %>
            <div class="single_relatified_item">
                <a href="<%= item.permalink %>">
                    <% if(item.image != "") { %>
                    <img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>" />
                         <% } %>
                         <div class="single_relatified_post_title">
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
        border-bottom: 1px solid #fff;
        border-right: 1px solid #fff;
        float: left;
        position: relative;
        width: 23.5%;
    }
    .single_relatified_item img {
        width: 100%;
        border-radius: 0;
    }
    .single_relatified_item:first-child {
    }
    .single_relatified_post_title {
        background-color: rgba(63, 63, 63, 0.7);
        bottom: 0;
        box-sizing: border-box;
        color: #fff;
        height: 45px;
        position: absolute;
        width: 100%;
    }
    .full_box {
    }
    .single_relatified_post_title p {
        color: #fff;
        font-size: 12px !important;
        height: 40px;
        line-height: 1.4;
        margin-bottom: 0 !important;
        overflow: hidden;
        padding: 5px;
    }
    .related-content-title {
        margin-bottom: 10px;
    }
</style>