export default {

	/**
	 * Counts the number of unique projects within a set of work items.
	 *
	 * @param workItems (Array) - The work items to search and find unique projects.
	 * @return (Int) The amount of unique invoices within the set of work items.
	*/
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

	tallySingleWorkItemHoursPay (workItem) {
		// Tally, calculating in cents not dollars
		var total = parseFloat(workItem.hours) * (parseFloat(workItem.hourly_rate) * 100) ;
		// Convert back to dollars
		return total / 100;
	},

	tallySingleWorkItemsMileagePay (workItem) {
		// Tally, calculating in cents not dollars
		var total = parseFloat(workItem.travel_mileage) * (parseFloat(workItem.mileage_rate) * 100);
		// Convert back to dollars
		return total / 100;
	},

	/** 
	 * Tallies the amount of hours in a set of work items.
	*/
	tallyWorkItemsHours (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			total += parseFloat(item.hours);
		});
		return total;		
	},

	/** 
	 * Tallies the amount of pay in a set of work items (Based on hours and hourly rate).
	*/
	tallyWorkItemsHoursPay (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			total += ((parseFloat(item.hours) * parseFloat(item.hourly_rate)) * 100);
		});
		// Convert back to dollars
		return total / 100;		
	},

	tallyWorkItemsTravelMileageCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			if(item.travel_mileage)
				total += ((parseFloat(item.travel_mileage) * parseFloat(item.mileage_rate)) * 100);
		});
		// Convert back to dollars
		return total / 100;		
	},

	tallyWorkItemsPerDiemCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			if(item.per_diem) 
				total += (parseFloat(item.per_diem) * 100);
	
		});
		// Convert back to dollars
		return total / 100;		
	},	

	tallyWorkItemsLodgingCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			if(item.lodging_cost) 
				total += (parseFloat(item.lodging_cost) * 100);						
		});
		// Convert back to dollars
		return total / 100;			
	},

	tallyWorkItemsEquipmentCost (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			if(item.equipment_cost) 
				total += (parseFloat(item.equipment_cost) * 100);						
		});
		// Convert back to dollars
		return total / 100;			
	},

	tallyWorkItemsExtraCosts (workItems) {
		// Cache total
		var total = 0;
		// Itterate and calculate
		workItems.forEach((item) => {
			// Tally, calculating in cents not dollars
			if(item.travel_mileage) 
				total += ((parseFloat(item.travel_mileage) * parseFloat(item.mileage_rate)) * 100);
			// Tally, calculating in cents not dollars
			if(item.per_diem) 
				total += (parseFloat(item.per_diem) * 100);
			// Tally, calculating in cents not dollars
			if(item.lodging_cost) 
				total += (parseFloat(item.lodging_cost) * 100);	
			// Tally, calculating in cents not dollars	
			if(item.equipment_cost) 
				total += (parseFloat(item.equipment_cost) * 100);					
		});
		// Convert back to dollars
		return total / 100;			
	},

	tallyWorkItemsGst (workItems) {
		// Get the subtotal
		var subtotal = this.tallyWorkItemsSubTotal(workItems);
		// Calculate in cents then convert back to dollars
		return ((subtotal * 100) * 0.05) / 100; 
	},

	tallyWorkItemsSubTotal (workItems) {
		// Tally
		var hoursPayTotal = this.tallyWorkItemsHoursPay(workItems),
				extraCostsTotal = this.tallyWorkItemsExtraCosts(workItems);
		// Calculate in cents then convert back to dollars
		return ((hoursPayTotal * 100) + (extraCostsTotal * 100)) / 100;
	},

	tallyWorkItemsTotal (workItems) {
		var subtotal = this.tallyWorkItemsSubTotal(workItems),
				gst = this.tallyWorkItemsGst(workItems);
		// Calculate in cents then convert back to dollars
		return ((subtotal * 100) + (gst * 100)) / 100;
	}

}