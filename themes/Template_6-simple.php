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
            <div class="single_rel_wrap">
                <div class="single_relatified_item">
                    <a href="<%= item.permalink %>">
                        <% if(item.image != "") { %>
                        <img src="<%= item.image %>" style="<% if(item.width != ""){ %>width:<%= item.width %>px; <% } %><% if(item.height != ""){ %>height:<%= item.height %>px;<% } %>" />
                        <% } %>
                        <div class="single_relatified_post_title">
                            <p><%= item.post_title %></p>
                            <div class="single_relatified_excerpt">
                                <%= item.post_content.substr(0, 100).replace( /<.*?>/g, '' ) %> ...
                            </div>
                        </div>
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
    .full_box{
        margin: 20px 0 35px;
        overflow: hidden;
    }
    .single_rel_wrap {
        float: left;
        width: 45%;
        margin: 0 2% 20px;
    }
    .single_relatified_item img {
        width: 100%;
        border-radius: 0;
        -webkit-transition: all 1s ease; /* Safari and Chrome */
        -moz-transition: all 1s ease; /* Firefox */
        -ms-transition: all 1s ease; /* IE 9 */
        -o-transition: all 1s ease; /* Opera */
        transition: all 1s ease;
    }
    .single_relatified_item:hover img {
        -webkit-transform:scale(1.25) rotate(-20deg); /* Safari and Chrome */
        -moz-transform:scale(1.25) rotate(-20deg); /* Firefox */
        -ms-transform:scale(1.25) rotate(-20deg); /* IE 9 */
        -o-transform:scale(1.25) rotate(-20deg); /* Opera */
        transform:scale(1.25) rotate(-20deg);
    }
    .single_relatified_item a{
        display: block;
    }
    .single_relatified_item{
        position: relative;
        height: 285px;
        overflow: hidden;
    }
    .single_relatified_post_title {
        padding: 3px;
        position: absolute;
        bottom: -60px;
        background-color:rgba(63,63,63,0.7);
        height: 105px;
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
        margin-bottom: 10px !important;
        padding: 5px;
        text-decoration: underline;
    }
    .single_relatified_post_title .single_relatified_excerpt {
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
<script type="text/javascript">
    jQuery(function ($) {
        $(document).on('mouseenter', '.single_relatified_item', function () {
            $(this).find('.single_relatified_post_title').animate({
                bottom: '0'
            }, 300);
        });
        $(document).on('mouseleave', '.single_relatified_item', function () {
            $(this).find('.single_relatified_post_title').animate({
                bottom: '-60px'
            }, 300);
        });
    });
</script>