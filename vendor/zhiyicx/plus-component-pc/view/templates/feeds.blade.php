@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
@endphp

@php
$conn = new mysqli('localhost', 'root', '', 'plus');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


@endphp

@if(!$feeds->isEmpty())
@foreach($feeds as $key => $post)
@php
$sql = "SELECT * from comments WHERE commentable_id = $post->id";
$result = $conn->query($sql);
@endphp
<div class="feed_item" id="feed{{$post->id}}">
    <div class="feed_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
            <img class="avatar" src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" width="50" />
            @if($post->user->verified)
            <img class="role-icon" src="{{ $post->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="feed_uname font14">{{ $post->user->name }}</span>
        </a>

        @if ($post->paid_node && $post->paid_node['paid'] == false)
        <a class="date" href="javascript:;">
            <span class="feed_time font12">{{ getTime($post->created_at) }}</span>
            <span class="feed_pay_text feed_time font12 hide" data-amount="{{ $post->paid_node['amount'] }}" data-node="{{ $post->paid_node['node'] }}" data-url="{{ route('pc:feedread', $post->id) }}">查看详情</span>
        </a>
        @else
        <a class="date" href="{{ route('pc:feedread', $post->id) }}">
            <span class="feed_time font12">{{ getTime($post->created_at) }}</span>
            <span class="feed_time font12 hide">查看详情</span>
        </a>
        @endif
    </div>

    <div class="feed_body">
        {{-- 文字付费 --}}
        @if ($post->paid_node && $post->paid_node['paid'] == false)
        <p class="feed_text feed_pay_text" data-amount="{{ $post->paid_node['amount'] }}" data-node="{{ $post->paid_node['node'] }}">{!! formatContent($post->feed_content) !!}</p>
        @else
        <p class="feed_text">{!! formatContent($post->feed_content) !!}</p>
        @endif

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
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg> <font class="cs{{$post->id}}">{{$post->feed_comment_count}}</font>
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
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    {{--@if(!empty($TS['id']) && $post->user_id != $TS['id'])
                    <li><a href="javascript:;" onclick="weibo.denounce(this);" feed_id="{{$post->id}}" to_uid="{{$post->user_id}}">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jubao-copy1"></use></svg>举报</a></li>
                    @endif --}}
                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                    <li>
                        <a href="javascript:;" onclick="weibo.delFeed({{$post->id}});">
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
                        <a class="btn btn-primary fr" id="J-button{{$post->id}}" onclick="weibo.addComment({{$post->id}}, 1)"> 评 论 </a>
                    </div>
                </div>
                <div id="J-commentbox{{ $post->id }}">
                    @if($post->feed_comment_count)
                    
                    @php
                    while($row = $result->fetch_assoc()) {
                    $test = $row['user_id'];
                    $sql = "SELECT * from users WHERE id = $test";
                    $result2 = $conn->query($sql);
                    $row2 = mysqli_fetch_array($result2);
                    @endphp
                        <p class="comment_con" id="comment{{$row['id']}}">
                            <span class="tcolor">{{ $row2['name'] }}：</span>
                            @if ($row['reply_user'] != 0)
                                @php
                                    $user = getUserInfo($row['reply_user']);
                                @endphp
                                回复{{ '@'.$user->name }}：
                            @endif

                            {{ $row['body'] }}
                            @if($row['user_id'] != $TS['id'])
                                <a onclick="comment.reply('{{$TS['id']}}', {{$row['commentable_id']}}, '{{$TS['name']}}')">回复</a>
                            @else
                                <a class="comment_del" onclick="comment.delete('{{$row['commentable_type']}}', {{$row['commentable_id']}}, {{$row['id']}});">删除</a>
                            @endif
                        </p>
                    @php
                    }
                    @endphp
                    @endif
                </div>
                @if($post->feed_comment_count >= 5)
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
