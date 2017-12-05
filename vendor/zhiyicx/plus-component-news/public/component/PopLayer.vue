<template>
  <div id="pop_layer" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click='close'><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">资讯管理</h4>
        </div>
        <div class="modal-body">
          <component :is="currentView" :dataItem='dataItem' @updata='updata' ref="child"></component>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" @click='close'>关闭面板</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'pop_layer',
    props: ['currentView','dataItem'],
    data: () => ({}),
    methods:{
      updata(){
        this.$emit("updata");
      },
      close(){
        this.$refs.child.init()
      }
    }
  }

</script>
