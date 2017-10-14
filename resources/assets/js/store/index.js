import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';
import Helpers from './helpers';

Vue.use(Vuex);

/** 
 * Holds state that can be used accross all components
*/
export const store = new Vuex.Store({
	/** 
	 * The cache values to be centralized 
	*/
	state: {
		// Used by some methods to log data to the console
		debug: true,
		// The logged in user
		authUser: {},
		// The logged in users notifications
		notifications: [],
		// A user who has been selected to view/edit
		currentUser: false,
		// Users returned by the server
		users: [],
		// Projects returned by the server
		projects: [],
		// A project who has been selected to view/edit
		currentProject: false,
		// The current project search filter
		projectsFilter: { 
			province: '',
			client: '',
			location: '',
			invoice: ''
		},
		// 'My 'Invoices returned by the server
		invoices: [],
		// Crew Invoices returned by the server
		crewInvoices: [],
		// An invoice that has been selected to view/edit
		currentInvoice: false,
		// The current invoice search filter
		invoicesFilter: {
			user: '',
			from_date: '',
			to_date: '',
			invoice: ''
		},
		// All companies found within the project table
		clients: []
	},

	/**
	 * Methods that directly change the state cache.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	mutations: {
		/*
		 * Notification mutations
		*/
		updateNotifications (state, payload) {
			return state.notifications = payload;
		},

		deleteNotification (state, payload) {
			// Use helper to get index
			var index = Helpers.pluckObjectById(state.notifications, 'id', payload);
			// Remove from store
			return state.notifications.splice(index, 1);
		},

		/*
		 * Project based mutations
		*/
		updateProjects (state, payload) {
			return state.projects = payload;
		},

		updateCurrentProject (state, payload) {
			return state.currentProject = payload;
		},

		updateProjectsProvinceFilter(state, payload) {
			return state.projectsFilter.province = payload;
		},

		updateProjectsClientFilter(state, payload) {
			return state.projectsFilter.client = payload;
		},

		updateProjectsLocationFilter(state, payload) {
			return state.projectsFilter.location = payload;
		},

		updateProjectsInvoiceFilter(state, payload) {
			return state.projectsFilter.invoice = payload;
		},

		updateProjectsFilter (state, payload) {
			return state.projectsFilter = payload;
		},

		resetProjectsFilter (state, payload) {
			for(var i in state.projectsFilter){
				state.projectsFilter[i] = '';
			}
		},

		addProjectComment (state, payload) {
			return state.currentProject.comments.push(payload);
		},

		deleteProjectComment (state, payload) {
			// Use helper to get index
			var index = Helpers.pluckObjectById(state.currentProject.comments, 'id', payload);
			// Remove from store
			return state.currentProject.comments.splice(index, 1);
		},

		addProjectCrew (state, payload) {
			return state.currentProject.users.push(payload);
		},

		deleteProjectCrew (state, payload) {
			// Use helper to get index
			var index = Helpers.pluckObjectById(state.currentProject.users, 'id', payload);
			// Remove from store
			return state.currentProject.users.splice(index, 1);	
		},

		updateCurrentProjectTimeline (state, payload) {
			return state.currentProject.timeline = payload;
		},

		/*
		 * Invoice based mutations
		*/
		updateInvoices (state, payload) {
			return state.invoices = payload;
		},

		publishInvoice (state, payload) {
			return state.currentInvoice.is_published = 1;	
		},

		deleteInvoice (state, payload) {
			return state.currentInvoice = false;
		},

		markInvoicesPaid (state, payload) {
			// Itterate through each invoice
			payload.forEach((id) => {
				var invoice = state.crewInvoices.find(elem => elem.id === id),
						invoiceIndex = state.crewInvoices.indexOf(invoice);
				// Mark paid
				return state.crewInvoices[invoiceIndex].is_paid = 1;
			});
		},

		updateCrewInvoices (state, payload) {
			return state.crewInvoices = payload;
		},

		updateCurrentInvoice (state, payload) {
			return state.currentInvoice = payload;
		},

		resetInvoicesFilter (state, payload) {
			for(var i in state.invoicesFilter){
				state.invoicesFilter[i] = '';
			}
		},

		updateInvoicesUserFilter(state, payload) {
			return state.invoicesFilter.user = payload;
		},		

		updateInvoicesInvoiceFilter(state, payload) {
			return state.invoicesFilter.invoice = payload;
		},		

		updateInvoicesFromDateFilter(state, payload) {
			return state.invoicesFilter.from_date = payload;
		},		

		updateInvoicesToDateFilter(state, payload) {
			return state.invoicesFilter.to_date = payload;
		},	

		addWorkItem (state, payload) {
			return state.currentInvoice.work_items.push(payload);
		},

		updateWorkItem (state, payload) {
			// Use helper to get index
			var index = Helpers.pluckObjectById(state.currentInvoice.work_items, 'id', payload);
			// FIRST, remove item that was updated from store 
			state.currentInvoice.work_items.splice(index, 1);	
			// NEXT, add the updated work item back to the invoice 
			// We do it this way so the computed property in the component will trigger an update
			return state.currentInvoice.work_items.push(payload);
		},

		deleteWorkItem (state, payload) {
			// Use helper to get index
			var index = Helpers.pluckObjectById(state.currentInvoice.work_items, 'id', payload);
			// Remove item from cache 
			return state.currentInvoice.work_items.splice(index, 1)	
		},

		/*
		 * User based mutations (Search and auth)
		*/		
		updateUsers (state, payload) {
			return state.users = payload;
		},

		// Add a user to store
		addUser (state, payload) {
			return state.users.push(payload);
		},

		updateCurrentUser (state, payload) {
			return state.currentUser = payload;
		},

		setAuthUser (state, payload) {
			return state.authUser = {
				id: AUTH_ID,
				first: AUTH_FIRST,
				last: AUTH_LAST,
				name: AUTH_FIRST + ' ' + AUTH_LAST,
				permissions: AUTH_PERMISSIONS,
				email: AUTH_EMAIL,
				company: AUTH_COMPANY,
				gst_number: AUTH_GST_NO,
				hourly: AUTH_HOURLY
			};
		},

		clearAuthUser (state, payload) {
			state.authUser = { };
		},

		/*
		 * Misc mutations
		*/
		updateClients (state, payload) {
			return state.clients = payload;
		}
	},

	/** 
	 * Actions that send API server calls and manipulate the database.
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.
	*/
	actions: {

		/* 
		 * Notification actions
		*/
		getNotifications (context, payload) {
			return api.getAction(context, '/notifications', 'updateNotifications');
		},

		deleteNotification (context, payload) {
			return api.deleteAction(context, '/notifications/delete/'+payload.id, 'deleteNotification');
		},

		/* 
		 * Project based actions
		*/
		getProjects (context, payload) {
			var url = '/projects'
			// Create payload 
			if(payload){
				// Add client to string
				if(payload.client != '') url += '/' + payload.client;
					else url += '/' + 0;
				// Add province to string
				if(payload.province != '') url += '/' + payload.province;
					else url += '/' + 0;
				// Add province to string
				if(payload.location != '') url += '/' + payload.location;
					else url += '/' + 0;					
				// Add invoice status to filter
				if(payload.invoice != '') url += '/' + payload.invoice;
					else url += '/' + 'any';
			}
			// Use api to send the request
			return api.getAction(context, url, 'updateProjects');
		},

		// Projects the authenticated user is a part of
		getUsersProjects (context, payload) {
			 return api.getAction(context, '/projects/auth-users', 'updateProjects');
		},

		getProject (context, payload) {
			// Use api to send request
			return api.getAction(context, '/projects/'+payload, 'updateCurrentProject');
		},

		addProject (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/start', 'updateCurrentProject');
		},

		updateProjectField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/update-field', 'updateCurrentProject');
		},

		addProjectComment (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/add-comment', 'addProjectComment');
		},

		deleteProjectComment (context, payload) {
			// Use api to send request
			return api.deleteAction(context, '/projects/delete-comment/'+payload.id, 'deleteProjectComment');
		},

		addProjectCrew (context, payload) {
			return api.postAction(context, payload, '/projects/add-crew', 'addProjectCrew');
		},

		deleteProjectCrew (context, payload) {
			return api.deleteAction(context, '/projects/'+payload.project_id+'/delete-crew/'+ payload.id, 'deleteProjectCrew');
		},

		updateTimelineField (context, payload) {
			return api.postAction(context, payload, '/timelines/update-field', 'updateCurrentProjectTimeline');
		},

		/* 
		 * Invoice based actions
		*/
		getAllInvoices (context, payload) {
			var url = '/invoices';
			// Create payload 
			if(payload){
				// Add user to string
				if(payload.user != '') url += '/' + payload.user;
					else url += '/' + 0;								
				// Add from date to string
				if(payload.from_date != '') url += '/' + payload.from_date;
					else url += '/' + 0;	
				// Add date to string
				if(payload.to_date != '') url += '/' + payload.to_date;
					else url += '/' + 0;				
				// Add invoice status to string
				if(payload.invoice != '') url += '/' + payload.invoice;
					else url += '/' + 0;																
			}
			// Use api to send request		
			return api.getAction(context, url, 'updateCrewInvoices');
		},

		// Invoices created by the authenticated user
		getUsersInvoices (context, payload) {
			 return api.getAction(context, '/invoices/auth-users', 'updateInvoices');
		},
	
		addInvoice (context, payload) {
			return api.postAction(context, payload, '/invoices/add', 'updateCurrentInvoice');
		},

		getInvoice (context, payload) {
			return api.getAction(context, '/invoices/'+payload, 'updateCurrentInvoice');
		},

		// User, Admin only
		publishInvoice (context, payload) {
			return api.postAction(context, payload, '/invoices/publish', 'publishInvoice');
		},

		// Super only
		markInvoicesPaid (context, payload) {
			return api.postAction(context, payload, 'invoices/mark-paid', 'markInvoicesPaid');
		},

		deleteInvoice (context, payload) {
			return api.deleteAction(context, '/invoices/delete/'+ payload, 'deleteInvoice');
		},	

		addWorkItem (context, payload) {
			return api.postAction(context, payload, '/invoices/add-item', 'addWorkItem');
		},

		updateWorkItem (context, payload) {
			return api.postAction(context, payload, '/invoices/edit-item', 'updateWorkItem');
		},	

		deleteWorkItem (context, payload) {
			return api.deleteAction(context, '/invoices/delete-item/'+ payload, 'deleteWorkItem');
		},				

		/* 
		 * User based actions
		*/
		getUsers (context, payload) {
			return api.getAction(context, '/users', 'updateUsers');
		},

		getUser (context, payload) {
			// Use api to send request
			return api.getAction(context, '/users/'+payload, 'updateCurrentUser');
		},

		addUser (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/add', 'addUser');
		},	

		updateUserField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/update-field', 'updateCurrentUser');
		},

		// Super, admin only
		changeUserPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-password');
		},

		changePersonalPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-personal-password');
		},

		/* 
		 * Misc actions
		*/
		getClients (context, payload) {
			// Use api to send request
			return api.getAction(context, '/projects/clients', 'updateClients');
		},

	},

	/** 
	 * Getters that access the state directly (For components)
	 *
	 * Method names are self descriptive so comments are only added where clarity is needed.	 
	*/
	getters: {
		// Return the debug state
		debug (state) {
			return state.debug;
		},

		notifications (state) {
			return state.notifications;
		},

		projects (state) {
			return state.projects;
		},

		currentProject (state) {
			return state.currentProject;
		},

		projectsFilter (state) {
			return state.projectsFilter;
		},

		// Return the clients formatted for a vuetify select list
		projectsSelectList (state) {
			// Cache users
			var projects = state.projects,
          projectsSelect = [{ text: "Project...", value: "" }];
      // Create client select array
      projects.forEach(function(project){
        projectsSelect.push({ text: project.id+' | '+project.location, value: project.id });
      });		
      return projectsSelect;	
		},

		invoices (state) {
			return state.invoices;
		},

		crewInvoices (state) {
			return state.crewInvoices;
		},

		currentInvoice (state) {
			return state.currentInvoice;
		},

		invoicesFilter (state) {
			return state.invoicesFilter;
		},

		users (state) {
			return state.users;
		},

		// Return the authenticated user
		user (state) {
			return state.authUser;
		},

		currentUser (state) {
			return state.currentUser;
		},

		// Return the clients formatted for a vuetify select list
		usersSelectList (state) {
			// Cache users
			var users = state.users,
          usersSelect = [{ text: "User...", value: "" }];
      // Create client select array
      users.forEach(function(user){
        usersSelect.push({ text: user.first+' '+user.last, value: user.id });
      });		
      return usersSelect;	
		},

		// Return the usrs formatted for a vuetify select list with user name as value
		usersSelectListNameBased (state) {
			// Cache users
			var users = state.users,
          usersSelect = [{ text: "User...", value: "" }];
      // Create client select array
      users.forEach(function(user){
        usersSelect.push({ text: user.first+' '+user.last, value: user.first+' '+user.last});
      });		
      return usersSelect;	
		},

		clients (state) {
			return state.clients;
		},

		clientsSelectList (state) {
			// Cache clients
			var clients = state.clients,
          clientsSelect = [{ text: "Client...", value: "" }];
      // Create client select array
      clients.forEach(function(client){
        clientsSelect.push({ text: client, value: client });
      });		
      return clientsSelect;	
		}
		
	}

});