import VueRouter from 'vue-router'

let routes = [
	{
		path: '/',
        name: 'home',
		components: require('./components/Home.vue')
	},
	{
		path: '/login',
        name: 'login',
		components: require('./components/login/login.vue')
	}
];

export default new VueRouter({
	routes
})
