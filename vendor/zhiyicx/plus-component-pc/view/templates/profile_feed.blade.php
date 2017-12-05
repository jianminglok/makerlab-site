@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp

@if(!$feeds->isEmpty())
@foreach($feeds as $key => $post)
<div class="feed_item" id="feed{{$post->id}}">

    <span class="feed_time">
        @if(date('Y-m-d') == date('Y-m-d', strtotime($post->created_at)))
            今天
        @else
            <a href="{{ route('pc:feedread', $post->id) }}">
            <span class="profile_time">
                <sup style="font-size:90%">{{date('m', strtotime($post->created_at))}}</sup>
                <sub style="font-size:60%">{{date('d', strtotime($post->created_at))}} </sub>
            </span>
            </a>
        @endif
    </span>

    <div class="feed_body">
        <p class="feed_text">{!! formatContent($post->feed_content) !!}</p>

        @include('pcview::templates.feed_images')
    </div>

    <div class="feed_bottom">
        <div class="feed_datas">
            <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->like_count}}" status="{{(int) $post->has_like}}">
                @if($post->has_like)
                <a href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg> <font>{{$post->like_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$post->id}}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg> <font>{{$post->like_count}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}"> {{$post->feed_comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg> {{$post->feed_view_count}}
            </span>
            <span class="options">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    <li id="J-collect{{$post->id}}" rel="0" status="{{(int) $post->has_collect}}">
                        @if($post->has_collect)
                        <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                    <li>
                        <a href="javascript:;" onclick="profile.delete({{$post->id}}, 'feeds');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                        </a>
                    </li>
                    @endif
                </ul>
                <img src="{{ asset('zhiyicx/plus-component-pc/images/triangle.png') }}" class="triangle" />
            </div>
        </div>

        <div class="comment_box" style="display: none;">
            <div class="comment_line">
                <img src="{{ asset('zhiyicx/plus-component-pc/images/line.png') }}" />
            </div>
            <div class="comment_body" id="comment_box{{$post->id}}">
                <div class="comment_textarea">
                    <textarea class="comment-editor" id="J-editor{{$post->id}}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a class="btn btn-primary fr" id="J-button{{$post->id}}" onclick="profile.addComment({{$post->id}}, 1, 'feeds')"> 评 论 </a>
                    </div>
                </div>
                
                @if($post->feed_comment_count)
                <div class="comit_all font12"><a href="{{Route('pc:feedread', $post->id)}}">查看全部评论</a></div>
                @endif
            </div>
        </div>
        <div class="feed_line"></div>
    </div>
</div>
<script type="text/javascript">
    layer.photos({
      photos: '#layer-photos-demo{{$post->id}}'
      ,anim: 0
      ,move: false
      ,img: '.per_image'
    });
</script>
@endforeach
@endif
