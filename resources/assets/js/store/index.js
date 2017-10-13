import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';
import Helpers from './helpers';

Vue.use(Vuex);

/** 
 * The Vuex store 
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
	 * Methods that directly change the state cache
	*/
	mutations: {
		updateNotifications (state, payload) {
			return state.notifications = payload;
		},

		deleteNotification (state, payload) {
			var index = Helpers.pluckObjectById(state.notifications, 'id', payload);
			// Remove from store
			state.notifications.splice(index, 1);
		},

		// Update projects
		updateProjects (state, payload) {
			return state.projects = payload;
		},
		// Update the current project
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

		// Push a freshly added comment into the current project
		addProjectComment (state, payload) {
			return state.currentProject.comments.push(payload);
		},
		// Remove a comment from the current project
		deleteProjectComment (state, payload) {
			// Find comment to delete
			state.currentProject.comments.forEach(function(comment){
				if(comment.id == payload) {
					var index = state.currentProject.comments.indexOf(comment); 
					state.currentProject.comments.splice(index, 1);					
				}
			});
		},
		// Push a freshly added crew member into the current project
		addProjectCrew (state, payload) {
			return state.currentProject.users.push(payload);
		},
		// Remove a crew member from the current project
		deleteProjectCrew (state, payload) {
			state.currentProject.users.forEach(function(user){
				if(user.id == payload) {
					var index = state.currentProject.users.indexOf(user); 
					state.currentProject.users.splice(index, 1);					
				}
			});
		},
		// Update the current project timline
		updateCurrentProjectTimeline (state, payload) {
			return state.currentProject.timeline = payload;
		},

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
			// Find item in current store cache, then find the index of that item
			var item = state.currentInvoice.work_items.find(elem => elem.id === payload.id),
					itemIndex = state.currentInvoice.work_items.indexOf(item);
			// Replace item in store cache
			return state.currentInvoice.work_items[itemIndex] = payload;
		},

		deleteWorkItem (state, payload) {
			// Find item in current store cache, then find the index of that item
			var item = state.currentInvoice.work_items.find(elem => elem.id === payload),
					itemIndex = state.currentInvoice.work_items.indexOf(item);
			// Remove item in store cache
			return state.currentInvoice.work_items.splice(itemIndex, 1);		
		},

		// Update users
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

		// Update clients
		updateClients (state, payload) {
			return state.clients = payload;
		}
	},

	actions: {

		getNotifications (context, payload) {
			return api.getAction(context, '/notifications', 'updateNotifications');
		},

		deleteNotification (context, payload) {
			return api.deleteAction(context, '/notifications/delete/'+payload.id, 'deleteNotification');
		},
		/* 
			PROJECT ACTIONS
		*/

		// Use api to retrieve all projects and set them in the state
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
		// Use api to retrieve a project and set it in the state
		getProject (context, payload) {
			// Use api to send request
			return api.getAction(context, '/projects/'+payload, 'updateCurrentProject');
		},

		// Use api to add a new project to the db and update the current project in the state
		addProject (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/start', 'updateCurrentProject');
		},
		// Use api to edit a project field in the db and update the current project in the state
		updateProjectField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/update-field', 'updateCurrentProject');
		},
		// Use api to add a comment to specific project and update the current project comments in the state
		addProjectComment (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/add-comment', 'addProjectComment');
		},
		// Use api to delete a comment and update the current project comments in the state
		deleteProjectComment (context, payload) {
			// Use api to send request
			return api.deleteAction(context, '/projects/delete-comment/'+payload.id, 'deleteProjectComment');
		},
		// Use api to add a crew member to a specific project and update the current project users in the state
		addProjectCrew (context, payload) {
			return api.postAction(context, payload, '/projects/add-crew', 'addProjectCrew');
		},
		// Use api to delete a crew member from a specific project and update the current project crew in the state
		deleteProjectCrew (context, payload) {
			return api.deleteAction(context, '/projects/'+payload.project_id+'/delete-crew/'+ payload.id, 'deleteProjectCrew');
		},

		updateTimelineField (context, payload) {
			return api.postAction(context, payload, '/timelines/update-field', 'updateCurrentProjectTimeline');
		},
		// Use api to retrieve all of a users projects and set them in the state
		getUsersProjects (context, payload) {
			 return api.getAction(context, '/projects/auth-users', 'updateProjects');
		},

		// Use api to retrieve all invocies
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

		// Use api to retrieve all of a users invoices and set them in the state
		getUsersInvoices (context, payload) {
			 return api.getAction(context, '/invoices/auth-users', 'updateInvoices');
		},
	
		// Use api to add an invoice
		addInvoice (context, payload) {
			return api.postAction(context, payload, '/invoices/add', 'updateCurrentInvoice');
		},

		getInvoice (context, payload) {
			return api.getAction(context, '/invoices/'+payload, 'updateCurrentInvoice');
		},

		publishInvoice (context, payload) {
			return api.postAction(context, payload, '/invoices/publish', 'publishInvoice');
		},

		markInvoicesPaid (context, payload) {
			return api.postAction(context, payload, 'invoices/mark-paid', 'markInvoicesPaid');
		},

		// Use api to delete an invoice
		deleteInvoice (context, payload) {
			return api.deleteAction(context, '/invoices/delete/'+ payload, 'deleteInvoice');
		},	

		// Use api to add hours to a timesheet
		addWorkItem (context, payload) {
			return api.postAction(context, payload, '/invoices/add-item', 'addWorkItem');
		},
		// Use api to add hours to a timesheet
		updateWorkItem (context, payload) {
			return api.postAction(context, payload, '/invoices/edit-item', 'updateWorkItem');
		},	
		// Use api to delete an entire timesheet
		deleteWorkItem (context, payload) {
			return api.deleteAction(context, '/invoices/delete-item/'+ payload, 'deleteWorkItem');
		},				

		/* 
			USER ACTIONS
		*/

		// Use api to retrieve all users and set them in the store
		getUsers (context, payload) {
			return api.getAction(context, '/users', 'updateUsers');
		},

		// Use api to retrieve a user and set it in the state
		getUser (context, payload) {
			// Use api to send request
			return api.getAction(context, '/users/'+payload, 'updateCurrentUser');
		},
		// Use api to add a new user to the db
		addUser (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/add', 'addUser');
		},		
		// Use api to edit a user field in the db and update the current user in the state
		updateUserField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/update-field', 'updateCurrentUser');
		},
		// Use api to change a users password
		changeUserPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-password');
		},
		// Use api to change the logged in users password
		changePersonalPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-personal-password');
		},
		/*
			MISC ACTIONS
		*/

		// Use api to retrieve all the unique clients (from projects)
		getClients (context, payload) {
			// Use api to send request
			return api.getAction(context, '/projects/clients', 'updateClients');
		},



	},

	getters: {
		// Return the debug state
		debug (state) {
			return state.debug;
		},

		notifications (state) {
			return state.notifications;
		},

		// Return the loaded projects
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

		// Return all users
		users (state) {
			return state.users;
		},

		// Return the logged in user
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

		// Return the clients (from projects table)
		clients (state) {
			return state.clients;
		},

		// Return the clients formatted for a vuetify select list
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