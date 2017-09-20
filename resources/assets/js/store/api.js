import axios from 'axios';

/** 
 * Several helper methods to assist in sending AJAX requests.
 * Uses the axios library to send requests.
*/
export default {

	/** 
	 * Sends a GET request to the server.
	 * Uses axios to send AJAX request and resolves a promise with the server payload response.
	 *
	 * @param url (String) - the URL to send the request to.
	*/
	get (url) {
		return axios.get(url)
			.then((response) => Promise.resolve(response.data.payload))
			.catch((error) => Promise.reject(error));
	},

	/**
	 * Used by actions within the store and returns a promise that needs to be resolved the calling action.
	 * Uses the get method above and then commits a mutation to the store.
	 *
	 * @param context (Object) - the store action context.
	 * @param url (String) - the URL to send the request to.
	 * @param mutation (String) - the mutation to commit after a successfull request.
	*/
	getAction (context, url, mutation) {
		// Return a promise to be resolved by the calling action
		return new Promise((resolve, reject) => {
			// Use method above to send GET request
			this.get(url)
				// On success
				.then((response) => {
					// Commit mutation to store
					context.commit(mutation, response);					
					// Resolve promise
					resolve();
				})
				.catch((error) => reject(error));				
		});				
	},

	/**
	 * Sends a POST request to the server.
	 * Uses axios to send AJAX request and resolves a promise with the server payload response.
	 *
	 * @param url (String) - the URL to send the request to.
	 * @param payload (Object) - a JS object that contains the POST data to send to server.
	*/
	post (url, payload) {
		return axios.post(url, payload)
			.then((response) => Promise.resolve(response.data.payload))
			.catch((error) => Promise.reject(error));
	},

	/**
	 * Used by actions within the store and returns a promise that needs to be resolved the calling action.
	 * Uses the post method above and then commits a mutation to the store.
	 *
	 * @param context (Object) - the store action context.
	 * @param payload (Object) - a JS object that contains the POST data to send to server.
	 * @param url (String) - the URL to send the request to.
	 * @param mutation (String) - the mutation to commit after a successfull request.
	*/
	postAction (context, payload, url, mutation) {
		// Return a promise to be resolved by the calling action
		return new Promise((resolve, reject) => {
			// Use method above to send POST request
			this.post(url, payload)
				// On success
				.then((response) => {
					// For debug
					if(context.state.debug) console.log(response);
					// Commit mutation to store					
					if(mutation) context.commit(mutation, response);
					// Resolve promise
					resolve(response);
				})
				.catch((error) => reject(error));				
		});		
	},

	/**
	 * Sends a DELETE request to the server.
	 * Uses axios to send AJAX request and resolves a promise with the server payload response.
	 *
	 * @param url (String) - the URL to send the request to.
	*/
	delete (url) {
		return axios.delete(url)
			.then((response) => Promise.resolve(response.data.payload))
			.catch((error) => Promise.reject(error));
	},

	/**
	 * Used by actions within the store and returns a promise that needs to be resolved the calling action.
	 * Uses the delete method above and then commits a mutation to the store.
	 *
	 * @param context (Object) - the store action context.
	 * @param url (String) - the URL to send the request to.
	 * @param mutation (String) - the mutation to commit after a successfull request.
	*/
	deleteAction (context, url, mutation) {
		// Return a promise to be resolved by the calling action
		return new Promise((resolve, reject) => {
			// Use method above to send DELETE request
			this.delete(url)
				// On success
				.then((response) => {
					// Commit mutation to store
					context.commit(mutation, response);
					// Resolve promise
					resolve();
				});
		});
	}

}