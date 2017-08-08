/** Load Vue based dependencies */
import Vue from 'vue';
import VueRouter from 'vue-router';
/** Load Project components needed by routes */
import Home from './components/dashboard/Home';
import Projects from './components/project/Projects';
import Timesheets from './components/timesheet/Timesheets';
import Users from './components/user/Users';

/** Register router with Vue */
Vue.use(VueRouter);

export default new VueRouter({
	routes: [
		{
			path: '/',
			name: 'Home',
			component: Home
		},

		{
			path: '/projects',
			name: 'Projects',
			component: Projects
		},

		{
			path: '/timesheets',
			name: 'Timesheets',
			component: Timesheets
		},

		{
			path: '/users',
			name: 'Users',
			component: Users
		}
	]
});
