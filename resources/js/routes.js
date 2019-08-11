import VueRouter from 'vue-router'

let routes = [
	{
		path: '/',
		components: require('./components/Home.vue')
	}
]

export default new VueRouter({
	routes
})