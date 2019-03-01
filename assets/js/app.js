import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'highlight.js/styles/atom-one-dark.css'
import '../css/app.sass'

import Vue from 'vue'
import hljs from 'highlight.js'
import InstantSearch from 'vue-instantsearch'
import BootstrapVue from 'bootstrap-vue'
import Editor from './components/Editor.vue'
import CreateComment from './components/CreateComment'
import Search from './components/Search'
import BlogDate from './components/BlogDate'

const delimiters = ['${', '}']

Vue.config.delimiters = delimiters
Vue.use(BootstrapVue)
Vue.use(InstantSearch)
window.vm = init()

document.addEventListener('DOMContentLoaded', (event) => {
  document.querySelectorAll('pre code').forEach(i => {
    hljs.highlightBlock(i)
    i.classList.remove('hljs')
  });
});

function init () {
  const addComponent = (name, params) => {
    Vue.component(name, params)
  }

  addComponent('editor', Editor)
  addComponent('create-comment', CreateComment)
  addComponent('search', Search)
  addComponent('blog-date', BlogDate)

  return initVue()
}

function initVue () {
  new Vue({
    el: '#app',
    delimiters: delimiters,
    data: () => ({
      ...vueExtend.data
    }),
    methods: {
      ...vueExtend.methods
    },
    watch: {
      ...vueExtend.watch
    },
    created () {
      vueExtend.created()
    }
  });
}
