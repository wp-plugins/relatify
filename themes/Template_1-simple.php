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
                    <img src="<%= item.image %>" />
                    <% } %>
                    <div class="single_relatified_post_title">
                        <p><%= item.post_title %></p>
                    </div>
                </a>
            </div>
            <% }); %>
        </div>
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
        border: 2px solid #d7d7d7;
	width: 100%;
    }

    .single_relatified_item:first-child {
        margin-left: 0px !important;
    }

    .single_relatified_post_title {
        margin: 2px;
        padding: 3px;
        position: relative;
        top: -42px;
        background-color:rgba(63,63,63,0.5);
        height: 38px;
    }

    .full_box {
    }

    .single_relatified_post_title p {
        color: #ffffff;
        font-size: 12px !important;
        font-weight: bold;
        line-height: 1.4;
        margin-bottom: 0 !important;
        padding-left: 5px;
    }

    .related-content-title {
        margin-bottom: 10px;
    }
</style>