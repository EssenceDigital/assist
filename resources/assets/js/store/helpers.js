export default {
	
	pluckObjectById (arrayToSearch, idToMatch){
		// Search array and match object id
		var item = arrayToSearch.find(elem => elem.id === idToMatch),
				index = arrayToSearch.indexOf(item);
		// Return the plucked index
		return index;
	},

	populatePostData (form) {
		return new Promise((resolve, reject) => {
			var data = {};
			for(var key in form){
				data[key] = form[key].val;
			}
			resolve(data);
		});
	},

	populateForm (form, data) {
		return new Promise((resolve, reject) => {
			// Populate form
			for(var key in form) {
				form[key].val = data[key];
			}
			resolve(form);
		});
	},

	resetForm (form) {
		return new Promise((resolve, reject) => {
			// Populate form
			for(var key in form) {
				form[key].val = form[key].dflt;
			}
			resolve(form);
		});		
	},

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

	clearFormErrors (form) {
		return new Promise((resolve, reject) => {
			// Clear form errors
			for(var key in form) {
				form[key].err = false;
				form[key].errMsg = '';
			}
			resolve(form);			
		});
	},

	tallyProjectInvoices (workItems) {
		if(workItems.length != 0){
			// Cache total
			var total = 0,
					invoiceIds = [];
			// Itterate and calculate
			workItems.forEach((item) => {
				// Push this items invoice id to the cached array
				invoiceIds.push(item.invoice.id);
			});
			// Return unique invoice IDs in array
			return Array.from(new Set(invoiceIds)).length;			
		}
	},

	tallyWorkItemsHours (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			total += parseFloat(item.hours);
		});
		return total;		
	},

	tallyWorkItemsHoursPay (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			total += (parseFloat(item.hours) * parseFloat(item.hourly_rate));
		});
		return total;		
	},

	tallyWorkItemsTravelMileageCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			if(item.travel_mileage) total += (parseFloat(item.travel_mileage) * parseFloat(item.mileage_rate));
		});
		return total;		
	},

	tallyWorkItemsPerDiemCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			if(item.per_diem) total += parseFloat(item.per_diem);
	
		});
		return total;		
	},

	tallyWorkItemsLodgingCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			if(item.lodging_cost) total += parseFloat(item.lodging_cost);						
		});
		return total;			
	},

	tallyWorkItemsEquipmentCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			if(item.equipment_cost) total += parseFloat(item.equipment_cost);						
		});
		return total;			
	},

	tallyWorkItemsExtraCosts (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			if(item.travel_mileage) total += (parseFloat(item.travel_mileage) * parseFloat(item.mileage_rate));
			if(item.per_diem) total += parseFloat(item.per_diem);
			if(item.lodging_cost) total += parseFloat(item.lodging_cost);		
			if(item.equipment_cost) total += parseFloat(item.equipment_cost);					
		});
		return total;			
	}
}