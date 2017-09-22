/** Load Vue based dependencies */
import Vue from 'vue';
import VueRouter from 'vue-router';
/** Load Project components needed by routes */
import Home from './components/dashboard/Home';
import YourInvoices from './components/invoice/Your-invoices';
import CrewInvoices from './components/invoice/Crew-invoices';
import Projects from './components/project/Projects';
import ProjectView from './components/project/Project-view'
import InvoiceView from './components/invoice/Invoice-view';
import Users from './components/user/Users';
import UserView from './components/user/User-view';
import UserSettings from './components/user/User-settings';

/** Register router with Vue */
Vue.use(VueRouter);

export default new VueRouter({
	routes: [
		{
			path: '/dashboard',
			name: 'Home',
			component: Home
		},
		{
			path: '/your-invoices',
			name: 'YourInvoices',
			component: YourInvoices
		},
		{
			path: '/your-invoices/:id/view',
			name: 'YourInvoiceView',
			component: InvoiceView,
			props: (route) => (
				{ id: route.params.id, invoice_state: 'full' }
			)
		},		
		{
			path: '/crew-invoices',
			name: 'CrewInvoices',
			component: CrewInvoices
		},
		{
			path: '/crew-invoices/:id/view',
			name: 'CrewInvoiceView',
			component: InvoiceView,
			props: (route) => (
				{ id: route.params.id, invoice_state: 'readonly' }
			)
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
			path: '/invoices/:id/view',
			name: 'InvoiceView',
			component: InvoiceView,
			props: true
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
