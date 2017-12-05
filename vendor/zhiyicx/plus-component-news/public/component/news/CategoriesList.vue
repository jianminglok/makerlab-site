<template>
<div class="component-container container-fluid">
  <!-- 错误提示 -->
  <transition name="fade">
    <div v-if='!!tips.error || !!tips.success || false' class="alert alert-dismissible" :class="tips.success?'alert-success':'alert-danger'">
      <button type="button" class="close" @click.prevent="dismisError">
          <span aria-hidden="true">&times;</span>
        </button> {{ tips.error || tips.success }}
    </div>
  </transition>

  <!-- 分类列表面板 -->
  <div class="panel panel-default" style="min-width:1235px;">
    <div class="well well-sm mb0">
      <span class="btn btn-link btn-xs" data-toggle="modal" data-target="#pop_layer" @click="addCates" role="button">
          <span class="glyphicon glyphicon-plus"></span> 添加分类
      </span>
    </div>
<!--
    <div class="panel-heading">
      <div class="row">
        <div class="col-xs-4 text-left">
          <Page :current='current_page' :last='last_page' @pageGo='pageGo'></Page>
        </div>
      </div>
    </div>
-->
    <!-- 加载动画 -->
    <div v-if="loadding" class="loadding">
      <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
    </div>
    <div v-else class="panel-body">
      <!-- Table -->
      <table class="table table-hove text-center table-striped table-bordered">
        <thead>
          <tr>
            <th>分类名称</th>
            <th>资讯数量</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cates in cates" :key="cates.id">
            <td>{{ cates.name }}</td>
            <td>{{ cates.news_count }}</td>
            <td>
              <!-- 编辑 -->
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#pop_layer" @click='editCates(cates)'>编辑</button>
              <!-- 删除 -->
              <button v-if='deleteID === cates.id' type="button" class="btn btn-danger btn-sm" disabled="disabled">
                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              </button>
              <button type="button" class="btn btn-danger btn-sm" v-else @click="deleteCates(cates.id)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- 弹出层  -->
  <PopLayer :currentView='currentView' :dataItem='catesItem' @updata='getCatesList' />
</div>
</template>

<script>
  import request, {
    createRequestURI
  } from '../../util/request';
  import AddCategories from './AddCategories.vue';
  import PopLayer from '../PopLayer.vue';
  import Page from '../Page.vue';


  export default {
    name: 'categories_list',
    components: {
      PopLayer,
      Page
    },
    data: () => ({
      current_page: 1,
      last_page: 0,
      tips: {
        error: null,
        success: null
      },
      cates: [],
      catesItem: null,
      deleteID: null,
      loadding: false,
      currentView: AddCategories
    }),
    methods: {
      
      // 清除错误
      dismisError() {
        this.tips.error = null;
        this.tips.success = null;
      },

      // 分页获取分类列表
      getCatesList(page) {
        this.cates = [];
        this.loadding = true;
        request.get('/news/admin/news/cates', {
          params: {
            //            page: page || this.current_page
          }
        }).then(response => {
          if (!response.status) return this.tips.error = '获取数据失败！';
          const {
            last_page = 0,
              data = [],
              current_page = 1,
          } = response.data;
          //          } = response.data.data;
          this.loadding = false;
          this.cates = data;
          this.last_page = last_page;
          this.current_page = current_page;
          if (!!!data) return this.tips.error = '暂无数据！';
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {});
      },

      // 按ID删除分类
      deleteCates(_id) {
        this.deleteID = _id;
        request.delete(`/news/admin/news/del/${_id}/cate`)
          .then(response => {
            this.deleteID = null;
            this.getCatesList();
            if (!response.data.status) return this.tips.error = '删除分类失败！';
          })
          .catch(({
            response: {
              data: {
                errors = ['删除失败']
              } = {}
            } = {}
          }) => {
            this.deleteID = null;
          });
      },

      // 弹出层操作
      editCates(cates) {
        this.catesItem = cates;
      },
      addCates() {
        this.catesItem = null;
      },

      // 分页功能
      pageGo(page) {
        this.current_page = page;
        this.getCatesList();
      }
    },
    created() {
      // 获取分类列表
      this.getCatesList();
    },
  }

</script>
