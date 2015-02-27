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
                <a href="<%= item.permalink %>"><img src="<%= item.image %>"></a>
                <% } %>
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
        width: 25%;
        border: 10px solid #efefef;
    }

    .single_relatified_item:first-child {

    }

    .single_relatified_item img {
    }

    .single_relatified_post_title {
        padding: 5px;
        position: relative;
        background: none repeat scroll 0 0 #535353;
        height: 38px;
    }

    .single_relatified_post_title p {
        font-size: 12px;
        line-height: 1.2;
        color: #ffffff;
        margin-bottom: 0 !important;
    }

    .full_box {
    }

    .related-content-title {
        margin-bottom: 10px;
    }
</style>