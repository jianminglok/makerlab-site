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
          <div class="col-sm-12">编辑分类</div>
        </div>
      </div>
      <!-- 加载动画 -->
      <div v-if="loadding" class="loadding">
        <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
      </div>
      <div class="panel-body" v-else>
        <div class="form-horizontal">
          <div class="row">
            <div class="col-sm-12">
              <!-- 名称 -->
              <div class="form-group">
                <label for="title" class="col-sm-1 control-label">名称</label>
                <div class="col-sm-11">
                  <input ref='catesName' type="text" class="form-control" id="title" aria-describedby="title-help-block" placeholder="请输入名称" v-model="_opt.name" />
                </div>
              </div>
            </div>
          </div>
          <!-- Button -->
          <div class="form-group">
            <div class="col-sm-offset-1 col-sm-11">
              <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              </button>
              <button v-else type="button" class="btn btn-primary" @click="ManageCates">{{_opt.tips}}分类</button>
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

export default {
  name: 'add_categories',
  props: ['dataItem'],
  data: () => ({
    loadding: false,
    adding: false,
    tips: {
      error: '',
      success: ''
    }
  }),
  computed: {
    // 计算属性 _opt
    _opt() {
      let tips = '添加',
        id = null,
        name = null;
      if (this.dataItem && this.dataItem.id) {
        id = this.dataItem.id;
        name = this.dataItem.name;
        tips = "修改";
      }
      return {
        id,
        name,
        tips
      }
    }
  },
  mounted() {
    // 初始化
    this.$nextTick(function() {
      this.$on('init', () => {
        this.init();
      })
    })
  },
  methods: {
    // 清除错误
    dismisError() {
      this.tips.error = null;
      this.tips.success = null;
    },

    // 初始化
    init() {
      this.adding = false;
      this.tips.error = '';
      this.loadding = false;
      this.tips.success = '';
    },

    // 编辑分类
    ManageCates() {
      this.dismisError();
      let {
        id,
        name
      } = this._opt;
      if (name.length > 6) return this.tips.error = '分类名最多6个字！';
      if (name) {
        this.adding = true;
        request.post('/news/admin/news/handle_cate', {
          cate_id: id,
          name
        }).then(response => {
          if (response.data.status) {
            this.adding = false;
            this.$emit('updata');
            this.tips.error = null;
            this.tips.success = this._opt.tips + '成功！';
          } else {
            this.adding = false;
            this.tips.success = '';
            this.tips.error = response.data.message;
          }
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {
          this.loadding = false;
        });
      } else {
        this.tips.error = '请输入分类名称！';
      }
    }
  },
  created() {
    this.init();
  },
};
</script>
