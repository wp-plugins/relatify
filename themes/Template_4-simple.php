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
                <div class="single_relatified_post_title">
                    <a href="<%= item.permalink %>">
                        <p><%= item.post_title %></p>
                    </a>
                </div>
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
        width: 24%;
        margin-right: 5px;
    margin-bottom: 4px;
    }

    .single_relatified_item:first-child {

    }

    .single_relatified_item img {
    }

    .single_relatified_post_title {
        padding: 5px;
        position: relative;
        border: 1px solid #e14938;
        height: 45px;
        margin-top: 2px;
    }

    .single_relatified_post_title p {
        color: #535353;
        font-size: 12px;
        font-weight: bold;
        line-height: 1.4;
        margin-bottom: 0 !important;
        padding-left: 5px;
    }

    .full_box {
    }

    .related-content-title {
        margin-bottom: 10px;
    }
</style>