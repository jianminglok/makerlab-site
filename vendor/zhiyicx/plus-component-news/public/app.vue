<style lang="scss">
@import "./style/app";

/*  @import "./style/animate.min.css";*/

// 旋转动画
@keyframes TurnAround {
  from {
    transform: rotate(1deg);
  }
  to {
    transform: rotate(360deg);
  }
}

// 设置滚动条样式
::-webkit-scrollbar {
  width: 2px;
  height: 2px;
}

::-webkit-scrollbar-thumb {
  background-color: #33BBBA !important;
}

::-webkit-scrollbar-track {
  background-color: transparent !important
}

#app {
  padding: 0;
  margin: 0;
}
</style>
<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/newslist" tag="li" active-class="active">
        <a href="#">资讯列表</a>
      </router-link>
      <router-link to="/categories" tag="li" active-class="active">
        <a href="#">资讯分类列表</a>
      </router-link>
      <router-link to="/recommend" tag="li" active-class="active">
        <a href="#">Banner列表</a>
      </router-link>
      <!--
      <router-link to="/manage" tag="li" active-class="active">
        <a href="#">编辑资讯</a>
      </router-link>
-->
      <!--
      <router-link to="/news/add_categories" tag="li" active-class="active">
        <a href="#">添加资讯分类</a>
      </router-link>
-->
      <!--
      <router-link to="/news/add_recommend" tag="li" active-class="active">
        <a href="#">添加推荐</a>
      </router-link>
-->
      <!--       <router-link to="/other" tag="li" active-class="active">
        <a href="#">待开发</a>
      </router-link> -->
    </ul>
    <router-view />
  </div>
</template>
<script>
export default {
  name: 'News'
}
</script>
