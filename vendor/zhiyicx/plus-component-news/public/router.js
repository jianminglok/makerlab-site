import Vue from 'vue';
import VueRouter from 'vue-router';


// components.
import NewsList from './component/news/NewsList.vue';
import CategoriesList from './component/news/CategoriesList.vue';
import RecommendList from './component/news/RecommendList.vue';
import Manage from './component/news/Manage.vue';
import AddCategories from './component/news/AddCategories.vue';
import AddRecommend from './component/news/AddRecommend.vue';
import NewsManage from './component/news/NewsManage.vue';
import Other from './component/news/Other.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  routes: [
    // root.
    {
      path: '/',
      redirect: 'newslist'
    },
    // Setting router.
    {
      path: '/newslist',
      component: NewsList
    }, {
      path: '/categories',
      component: CategoriesList
    }, {
      path: '/recommend',
      component: RecommendList
    }, {
      path: '/manage/:newsId',
      component: Manage
    }, {
      path: '/manage',
      component: Manage
    },
    /*{
          path: '/add_categories',
          component: AddCategories
        },*/
    {
      path: '/rec',
      component: AddRecommend
    }, {
      path: '/rec/:rid',
      component: AddRecommend
    }, {
      path: '/other',
      component: Other
    }]
});

export default router;
