/**
 * Contains some useful methods that can be used by components to do some mundane and repetitive tasks. 
 *
*/
export default {
	/** 
	 * Searches an array of objects for an 'id' and returns the matched object's index.
	 * Each object in the array should have an
	 *
	 * @param Array arrayToSearch - The array of objects to search (haystack)
	 * @param String idKey - The field on each object that identifies it (Ex. 'id' or 'project_id')
	 * @param Int idToMatch - The value of the id to match (needle)
	 * @return Int - The index of the matched object in the array
	*/
	pluckObjectById (arrayToSearch, idKey, idToMatch){
		// Search array and match object id
		var item = arrayToSearch.find(elem => elem[idKey] === idToMatch),
				index = arrayToSearch.indexOf(item);
		// Return the plucked index
		return index;
	},

	/** 
	 * Populates an object with data from the supplied form.
	 *
	 * @param Obj form - An object that contains objects for each form field. { val: , error: , errorMsg: , dflt: }.
	 * @return Promise - Resolves a function with the populated data array.
	*/
	populatePostData (form) {
		return new Promise((resolve, reject) => {
			var data = {};
			for(var key in form){
				data[key] = form[key].val;
			}
			resolve(data);
		});
	},

	/** 
	 * Populates the supplied form with the supplied data
	 *
	 * @param Obj form - An object that contains objects for each form field. { val: , error: , errorMsg: , dflt: }.
	 * @param Obj data - An object with fields that correspond to the form.
	 * @return Promise - Resolves a function with the populated form.
	*/
	populateForm (form, data) {
		return new Promise((resolve, reject) => {
			// Populate form
			for(var key in form) {
				form[key].val = data[key];
			}
			resolve(form);
		});
	},

	/** 
	 * Resets the supplied form fields to their default state, including removing error messages.
	 *
	 * @param Obj form - An object that contains objects for each form field. { val: , error: , errorMsg: , dflt: }.
	 * @return Promise - Resolves a function with the now cleared form.
	*/
	resetForm (form) {
		return new Promise((resolve, reject) => {
			// Populate form
			for(var key in form) {
				form[key].val = form[key].dflt;
			}
			// Clear errors
			this.clearFormErrors(form);
			// Resolve promise
			resolve(form);
		});		
	},

	/** 
	 * Sets all form fields to an error state if they are present in the errors prop.
	 *
	 * @param Obj form - An object that contains objects for each form field. { val: , error: , errorMsg: , dflt: }.
	 * @param Obj errors - Each field in error state will have an object here. Returned by Laravel validate method.
	 * @param Promise - Resolves a function
	*/
	populateFormErrors (form, errors) {
		return new Promise((resolve, reject) => {
			// Populate form errors
			for(var key in errors) {
				form[key].err = true;
				form[key].errMsg = errors[key][0]; 
			}
			resolve();
		});
	},

	/**
	 * Clears the supplied form fields of any possible error state.
	 * @param Obj form - An object that contains objects for each form field. { val: , error: , errorMsg: , dflt: }.	 
	*/
	clearFormErrors (form) {
		return new Promise((resolve, reject) => {
			// Clear form errors
			for(var key in form) {
				form[key].err = false;
				form[key].errMsg = '';
			}
			resolve(form);			
		});
	}
	
}