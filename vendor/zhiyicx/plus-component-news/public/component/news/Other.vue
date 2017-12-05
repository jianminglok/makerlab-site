<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">

    <!-- 提示 -->
    <div class="alert alert-success" role="alert">
      待开发模块
    </div>
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const OtherComponent = {
  /**
   * The component state tree.
   *
   * @return {Object} state tree
   * @author Seven Du <shiweidu@outlook.com>
   */
  data: () => ({
    /**
     * is loadding.
     *
     * @type {Boolean}
     */
    loadding: true,
    error: null
  }),
  /**
   * methods.
   *
   * @type {Object}
   */
  methods: {
    dismisError () {
      this.error = null;
    }
  },
  /**
   * The component created run.
   *
   * @author Seven Du <shiweidu@outlook.com>
   */
  created () {
//    this.loadding = true;
//    request.get(
//      createRequestURI('roles'),
//      { validateStatus: status => status === 200 }
//    ).then(({ data }) => {
//      this.loadding = false;
//      this.roles = data;
//    }).catch(({ response: { data: { errors = ['加载失败,请刷新重试！'] } = {} } = {} }) => {
//      this.loadding = false;
//      this.error = lodash.values(errors).pop();
//    });
    this.loadding = false;
  }
};

export default OtherComponent;
</script>
