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
    <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
          <div class="col-sm-12">编辑资讯</div>
        </div>
      </div>
      <!-- 加载动画 -->
      <div v-if="loadding" class="loadding">
        <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
      </div>
      <div class="panel-body" v-else>
        <div class="form-horizontal">
          <!-- 标题 -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="title" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" @blur="test(_opt.title,'请输入资讯标题！')" aria-describedby="title-help-block" placeholder="请输入标题" v-model="_opt.title">
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <span class="tips">请输入文章标题。</span>
            </div>
          </div>
          <!-- 摘要 -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="summary" class="col-sm-2 control-label">摘要</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="summary" aria-describedby="summary-help-block" placeholder="请输入文章摘要" v-model="_opt.subject">
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <span class="tips">请输入文章摘要,默认截取文章前60字。</span>
            </div>
          </div>
          <!-- 文章来源 -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group row">
                <label for="source" class="col-sm-2 control-label">来源</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="source" @blur="test(_opt.source,'请输入资讯来源！')" aria-describedby="source-help-block" placeholder="请输入文章来源" v-model="_opt.source">
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <span class="tips">请输入文章来源。</span>
            </div>
          </div>
          <!-- 选择文章分类 -->
          <div class="form-group row">
            <label for="categories" class="col-sm-1 control-label">分类</label>
            <div class="col-sm-11">
              <Vselect :value='_link' :opt='cates' @change='changeCates'></Vselect>
            </div>
          </div>
          <!-- 选择缩略图 -->
          <div class="form-group row">
            <label for="thumbnails" class="col-sm-1 control-label">配图</label>
            <div class="col-sm-11">
              <Upload :imgs='_opt.storage' @getTask_id="getTask_id" @updata='updataImg' />
            </div>
          </div>
          <!-- 文章内容 -->
          <div class="form-group row">
            <label for="content" class="col-sm-1 control-label">正文</label>
            <div class="col-sm-11">
              <Editor :input-content="_opt.content" :upload-url="uploadUrl" v-model="_opt.content"></Editor>
            </div>
          </div>
          <!-- Button -->
          <div class="form-group">
            <div class="col-sm-offset-1 col-sm-11">
              <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              </button>
              <button v-else type="button" class="btn btn-primary" @click="ManageNews">{{_opt.tips}}</button>
              <button type="button" onClick="window.history.go(-1)" class="btn btn-default">返回</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import request, {
  createRequestURI
} from '../../util/request';

import Editor from '../Editor';
import Upload from '../Upload_v2';
import Vselect from '../select';

export default {
  name: 'manage',
  components: {
    Editor,
    Upload,
    Vselect
  },
  data: () => ({
    loadding: false,
    adding: false,
    tips: {
      error: null,
      success: null
    },
    old_news: null,
    cates: [],
    storage: '',
    link: new Set(),
    outputContent: '',
    uploadUrl: ''
  }),
  computed: {
    // 计算属性 _opt
    _opt() {
      let tips = '添加资讯',
        id = '',
        title = '',
        links = '',
        subject = '',
        storage = '',
        content = '',
        source = '';
      if (this.old_news && this.old_news.id) {
        tips = "保存修改";
        id = this.old_news.id;
        title = this.old_news.title;
        links = this.old_news.links;
        subject = this.old_news.subject;
        storage = this.old_news.storage;
        content = this.old_news.content;
        source = this.old_news.from;
        this.link = new Set(this.old_news.links.map((item) => (item.cate_id)));
      }
      return {
        id,
        title,
        links,
        subject,
        storage: this.storage || storage,
        content,
        source,
        tips
      }
    },
    _link() {
      return Array.from(this.link);
    }
  },
  methods: {
    // 清除错误
    dismisError() {
      this.tips.error = null;
      this.tips.success = null;
    },
    // 获取分类列表
    getCatesList(callback) {
      request.get('/news/admin/news/cates')
        .then(response => {
          callback();
          if (!response.data.status) return this.tips.error = '获取分类列表失败！';
          let {
            data = []
          } = response.data;
          this.cates = data;
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {});
    },
    getNewsById(id) {
      request.get(`/news/admin/news/info/${id}`, {
        validateStatus: status => status === 200
      }).then(response => {
        if (!response.data.status) return this.tips.error = '获取资讯详情失败！';
        let {
          data
        } = response.data;

        if (data.id) {
          this.old_news = data;
        }
      }).catch(({
        response: {
          data: {
            errors = ['获取资讯详情失败！']
          } = {}
        } = {}
      }) => {});
    },

    // 提交修改时的错误提示
    test(v, error) {
      if (v) return true;
      this.tips.error = error;
      return false;
    },

    // 编辑文章
    ManageNews() {
      this.tips.success = null;
      let {
        id,
        title,
        subject,
        content,
        source,
        storage
      } = this._opt;
      let r = this.test(title, '请输入资讯标题！') &&
        this.test(source, '请输入资讯来源！') &&
        this.test(this.link, '请至少选择一个资讯分类！') &&
        this.test(content, '资讯内容不能为空！');
      if (r) {
        this.adding = true;
        this.tips.error = null;
        request.post('/news/admin/news/handle_news', {
          links: Array.from(this.link),
          news_id: id,
          title,
          subject,
          content,
          storage_id: storage,
          source
        }).then(response => {
          this.adding = false;
          if (!response.data.status) return this.tips.error = response.data.message;
          this.tips.success = '操作成功！';
        }).catch(({
          response: {
            data: {
              errors = ['操作失败']
            } = {}
          } = {}
        }) => {
          this.loadding = false;
        });
      }
    },

    // 图片上传

    // 获取图片ID || 图片上传任务ID
    getTask_id(task_id) {
      this.storage = task_id;
    },

    // 清除图片ID || 任务ID
    updataImg() {
      this.storage = null;
      if (this.old_news) {
        this.old_news.storage = null;
      }
    },

    // 改变当前分类
    changeCates(v) {
      // 临时存储 vue.data 相当于 vue.set(...)
      let t = this.link;
      this.link = '';
      if (t.has(v)) {
        t.delete(v);
      } else if (t.size < 3) {
        t.add(v);
      } else {
        this.tips.error = '最多选择三个分类';
      }
      this.link = t;
    }
  },
  created() {
    // 获取分类列表
    this.getCatesList(() => {
      // 通过id查询资讯详情
      if (this.$route.params.newsId) {
        this.getNewsById(this.$route.params.newsId);
      }
    });
  },
};
</script>
<style>
textarea#content {
  height: 180px;
}

.tips {
  opacity: 0.8;
  height: 36px;
  line-height: 36px;
  vertical-align: middle;
}
</style>
