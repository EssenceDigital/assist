export default {
	
	formatDate (yyyMMdd) {
		return new Date(Date.parse(yyyMMdd + 'T00:00:00')).toDateString();
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

	findTimesheet (timesheets, timesheetId) {
		return new Promise((resolve, reject) => {
			// Find timesheet
			timesheets.forEach(function(timesheet){
				if(timesheet.id === timesheetId) {
					var index = timesheets.indexOf(timesheet); 
					resolve(index);				
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
		return +totalHours.toFixed(2);
	},

	calcTimesheetTravel (travelJobs) {
		var totalTravel = 0;
		// Calculate hours
		travelJobs.forEach( (job) => {
			totalTravel += parseFloat(job.travel_distance);
		});
		return totalTravel;		
	},

	calcTimesheetEquipment (equipmentRentals) {
		var totalEquipment = 0;
		// Calculate total
		equipmentRentals.forEach( (rental) => {
			totalEquipment += parseFloat(rental.rental_fee);
		});
		return totalEquipment.toFixed(2);			
	},

	calcTimesheetOther (otherCosts) {
		var totalOther = 0;
		// Calculate total
		otherCosts.forEach( (cost) => {
			totalOther += parseFloat(cost.cost);
		});
		return totalOther.toFixed(2);	
	},



  timesheetsTotalPerDiem (timesheets) {
    var total = 0;
    timesheets.forEach((timesheet) => {
      total += parseFloat(timesheet.per_diem);
    });
    return total.toFixed(2);
  },

  timesheetsTotalHours (timesheets) {
    var total = 0;
    timesheets.forEach((timesheet) => {
      total += this.calcTimesheetHours(timesheet.work_jobs);
    });
    console.log(total);
    return total;
  },

  timesheetsTotalHoursPay (timesheets, wage) {
  	var hours = this.timesheetsTotalHours(timesheets),
  	 		pay = parseFloat(hours) * parseFloat(wage);
  	 		console.log(pay + 'pay');
    return pay.toFixed(2);
  },

  timesheetsTotalTravelDistance (timesheets) {
    var total = 0;
    timesheets.forEach((timesheet) => {
      total += parseFloat(this.calcTimesheetTravel(timesheet.travel_jobs));
    });
    return total.toFixed(2);
  },

  timesheetsTotalEquipmentCost (timesheets) {
    var total = 0;
    timesheets.forEach((timesheet) => {
      total += parseFloat(this.calcTimesheetEquipment(timesheet.equipment_rentals));
    });
    return total.toFixed(2);
  },

  timesheetsTotalOtherCosts (timesheets) {
    var total = 0;
    timesheets.forEach((timesheet) => {
      total += parseFloat(this.calcTimesheetOther(timesheet.other_costs));
    });
    return total.toFixed(2);
  },

  timesheetsTotal (timesheets) {
    var total = parseFloat(this.totalPerDiem(timesheets)) +
                parseFloat(this.totalHoursPay(timesheets)) + 
                parseFloat(this.totalEquipmentCost(timesheets)) + 
                parseFloat(this.totalOtherCosts(timesheets));
    return total.toFixed(2);
  } 

}