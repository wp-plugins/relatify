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
            <div class="single_relatified_item" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %> />">
                <a href="<%= item.permalink %>">
                    <% if(item.image != "") { %>
<div style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %> padding: 2px;">
                    <img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>" />
</div>
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
    margin-bottom: 1px !important;
    float: left;
    position: relative;
    width: 23.5%;
  }
    .single_relatified_item > a {
        border-bottom: 0 none !important;
        text-decoration: none !important;
    }  
  .single_relatified_item img {
    border: 0 none !important;
    border-radius: 0;
    height: 100px;
    padding: 0 !important;
    width: 100%;
    max-width: none !important;
  }
  .single_relatified_item:first-child {
  }
  .single_relatified_post_title {
    background-color: rgba(63, 63, 63, 0.7);
    bottom: -2px;
    box-sizing: border-box;
    color: #fff;
    height: 45px;
    margin: 0px 2px;
    position: absolute;
    width: 100%;
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
    height: 40px;
    line-height: 1.4;
    margin: 0 !important;
    overflow: hidden;
    padding: 8px !important;
    font-weight: normal !important;
  }
  .full_box {
    overflow: auto;
  }
  .related-content-title {
    margin-bottom: 10px;
  }
</style>