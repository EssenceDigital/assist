/** Load Vue based dependencies */
import Vue from 'vue';
import VueRouter from 'vue-router';
/** Load Project components needed by routes */
import Home from './components/dashboard/Home';
import Projects from './components/project/Projects';
import ProjectView from './components/project/Project-view'
import Timesheets from './components/timesheet/Timesheets';
import ProjectTimesheets from './components/timesheet/Project-timesheets';
import Users from './components/user/Users';
import UserView from './components/user/User-view';
import UserSettings from './components/user/User-settings';

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
			path: '/projects/:id/view',
			name: 'ProjectView',
			component: ProjectView,
			props: true
		},	
		{
			path: '/projects/:id/timesheets',
			name: 'ProjectTimesheets',
			component: ProjectTimesheets,
			props: true
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
		},
		{
			path: '/users/:id/view',
			name: 'UserView',
			component: UserView,
			props: true
		},	
		{
			path: '/user-settings',
			name: 'UserProfile',
			component: UserSettings
		},			
	]
});
