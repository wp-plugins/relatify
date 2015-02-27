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
        width: 23.5%;
        margin: 0 0.4%;
        position: relative;
    }

    .single_relatified_item img {
        width: 100%;
        border-radius: 0;
    }

    .single_relatified_item:first-child {

    }

    .single_relatified_post_title {
        padding: 3px;
        position: absolute;
        bottom: 7px;
        background-color:rgba(63,63,63,0.7);
        height: 45px;
        width: 100%;
        box-sizing: border-box;
        color: #fff;
    }

    .full_box {
    }

    .single_relatified_post_title p {
        color: #fff;
        font-weight: bold;
        font-size: 12px !important;
        line-height: 1.4;
        margin-bottom: 0 !important;
        padding: 5px;
    }

    .related-content-title {
        margin-bottom: 10px;
    }
</style>