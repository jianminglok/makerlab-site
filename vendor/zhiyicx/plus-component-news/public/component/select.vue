<style lang="css">
  #v-sel {
    width: 100%;
    height: auto;
    vertical-align: middle;
  }
  #v-sel .v-input {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    text-align: center;
    margin: 0;
    padding: 0;
    margin-left: 0;
  }
  #v-sel .v-input li {
    cursor: pointer;
    margin: 0 5px 5px 0;
    transition: all .3s;
    height: 36px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.6;
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccd0d2;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
  }
  #v-sel .v-input li:hover{
    opacity: .8;
    color: #3097D1;
    border-color: #3097D1;
  }
  #v-sel .v-input li.active{
    color:#fff;
    background-color: #3097D1;
    border: 1px solid rgba(48, 151, 209, 0.50);
  }
</style>
<template>
  <div id="v-sel">
    <ul class="v-input">
      <li v-for='opt in opt' :key='opt.id' @click='changeValue(opt.id)' :class="(_value && _value.has(opt.id))? 'active' : ''">{{opt.name}}</li>
    </ul>
  </div>
</template>


<script>
  export default {
    name: 'v-select',
    props: ['value', 'opt'],
    data: () => ({}),
    computed:{
      _value(){
        return new Set(this.value);
      }
    },
    methods:{
      changeValue(opt_id){
        this.$emit('change',opt_id);
      }
    }
  }

</script>
