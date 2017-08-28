export default {
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

	findTimesheet (timesheets, timesheetId) {
		return new Promise((resolve, reject) => {
			// Find timesheet
			timesheets.forEach(function(timesheet){
				if(timesheet.id === timesheetId) {
					resolve(timesheet);				
				}
			});	
		});
	},

	findTimesheetAsset (timesheets, timesheetId, assetName, assetId) {
		return new Promise((resolve, reject) => {
			this.findTimesheet(timesheets, timesheetId).then( (timesheet) => {
				timesheet[assetName].forEach(function(asset){
					if(asset.id === assetId){
						resolve(asset);
					}
				});
			});
		});
	},

	deleteTimesheetAsset (timesheets, timesheetId, assetName, assetId) {
		return new Promise((resolve, reject) => {
			this.findTimesheet(timesheets, timesheetId).then( (timesheet) => {
				timesheet[assetName].forEach(function(asset){
					if(asset.id === assetId){
						var index = timesheet[assetName].indexOf(asset); 
						timesheet[assetName].splice(index, 1);	
						resolve();					
					}
				});
			});
		});
	},

	calcTimesheetHours (workJobs) {
		var totalHours = 0;
		// Calculate hours
		workJobs.forEach( (job) => {
			totalHours += parseFloat(job.hours_worked);
		});
		return totalHours;
	},

	calcTimesheetTravel (travelJobs) {

	},

	calcTimesheetEquipment (equipmentRentals) {

	},

	calcTimesheetOther (otherCosts) {

	}

}