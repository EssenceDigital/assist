/**
 * Is a central point for all money calculations within the app. 
 *
 * All calculations are done in cents and then converted back to dollars for precision.
*/
export default {
	/**
	 * Counts the number of unique projects within a set of work items.
	 *
	 * @param Array workItems - The work items to search and find unique projects.
	 * @return Int - The amount of unique invoices within the set of work items.
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

	/** 
	 * Returns the work hours pay for a single work item.
	 *
	 * @param Obj workItem - WorkItem model
	 * @return Float
	*/
	tallySingleWorkItemHoursPay (workItem) {
		// Tally, calculating in cents not dollars
		var total = parseFloat(workItem.hours) * (parseFloat(workItem.hourly_rate) * 100) ;
		// Convert back to dollars
		return total / 100;
	},

	/** 
	 * Returns the travel mileage pay for a single work item.
	 *
	 * @param Obj workItem - WorkItem model
	 * @return Float
	*/
	tallySingleWorkItemsMileagePay (workItem) {
		// Tally, calculating in cents not dollars
		var total = parseFloat(workItem.travel_mileage) * (parseFloat(workItem.mileage_rate) * 100);
		// Convert back to dollars
		return total / 100;
	},

	/** 
	 * Returns the amount of hours in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Int
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
	 * Returns the total hours pay in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
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

	/** 
	 * Returns the total travel mileage pay in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
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

	/** 
	 * Returns the total per diem amount in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
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

	/** 
	 * Returns the total lodging cost in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
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

	/** 
	 * Returns the total equipment cost in a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
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

	/** 
	 * Returns the total extra costs amount in a set up work items. (Mileage, per diem, lodging, and equipment).
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
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

	/** 
	 * Returns the sub total for work hours and extra costs together for a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
	tallyWorkItemsSubTotal (workItems) {
		// Tally
		var hoursPayTotal = this.tallyWorkItemsHoursPay(workItems),
				extraCostsTotal = this.tallyWorkItemsExtraCosts(workItems);
		// Calculate in cents then convert back to dollars
		return ((hoursPayTotal * 100) + (extraCostsTotal * 100)) / 100;
	},

	/** 
	 * Returns the total amount of GST for a set of work items. Work hours and extra costs included together.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
	tallyWorkItemsGst (workItems) {
		// Get the subtotal
		var subtotal = this.tallyWorkItemsSubTotal(workItems);
		// Calculate in cents then convert back to dollars
		return ((subtotal * 100) * 0.05) / 100; 
	},

	/** 
	 * Returns the total amount (subtotal + GST) for a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/	
	tallyWorkItemsTotal (workItems) {
		var subtotal = this.tallyWorkItemsSubTotal(workItems),
				gst = this.tallyWorkItemsGst(workItems);
		// Calculate in cents then convert back to dollars
		return ((subtotal * 100) + (gst * 100)) / 100;
	},

	/** 
	 * Returns the total amount of pay for a set of work items that are on an invoice which is paid.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
	tallyPaidWorkItemsTotal (workItems) {
		// Seperate the work items that are on a paid invoice
		var paidWorkItems = [];
		workItems.forEach((item) => {
			if(item.invoice.is_paid){
				paidWorkItems.push(item);
			}
		});
		var subtotal = this.tallyWorkItemsSubTotal(paidWorkItems),
				gst = this.tallyWorkItemsGst(paidWorkItems);
		// Calculate in cents then convert back to dollars
		return ((subtotal * 100) + (gst * 100)) / 100;
	},

	/** 
	 * Returns the total amount of pay still oustanding for a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @return Float
	*/
  tallyOwingProjectCost (workItems) {
    var total = (parseFloat(this.tallyWorkItemsTotal(workItems)) * 100) 
    						- (parseFloat(this.tallyPaidWorkItemsTotal(workItems)) * 100);
    return total / 100;
  },

	/** 
	 * Returns the total bottom line (profit or loss) for a set of work items.
	 *
	 * @param Array workItems - an array of WorkItem models
	 * @param String invoicePaidDate - The date (or not) the parent invoice was paid
	 * @param Float clinetInvoicedAmount - The amount of the client invoice (could be zero)
	 * @return Float
	*/
	tallyWorkItemsBottomLine (workItems, invoicePaidDate, clientInvoicedAmount) {
		var invoiceAmount = 0;
		// Determine invoice amount
		if(invoicePaidDate){
			invoiceAmount = clientInvoicedAmount;
		} 
		var total = (parseFloat(invoiceAmount) * 100) 
								- (parseFloat(this.tallyWorkItemsTotal(workItems)) * 100)
		return total / 100;
	},

	/** 
	 * Returns the total amount ($) for invoices that have been paid in a set of projects.
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsInvoicesPaidTotal (projects) {
    var total = 0;
    projects.forEach((project) => {
      if(project.invoice_paid_date != null) {
        total += (parseFloat(project.invoice_amount) * 100);
      }
    });
    return total / 100;		
	},

	/** 
	 * Returns the total outstanding amount ($) for invoices that have not been paid in a set of projects.
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsInvoicesOutstandingTotal (projects) {
    var total = 0;
    projects.forEach((project) => {
      if(project.invoice_paid_date === null) {
        total += (parseFloat(project.invoice_amount) * 100);
      }
    });
    return total / 100;		
	},

	/** 
	 * Returns the total crew cost in a set of projects.
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsTotalCrewCost (projects) {
    var total = 0;
    projects.forEach((project) => {
      if(project.work_items.length != 0){
        total += (this.tallyWorkItemsTotal(project.work_items) * 100);
      }            
    });
    return total / 100;		
	},

	/** 
	 * Returns the total paid crew cost in a set of projects.
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsTotalPaidCrewCost (projects) {
    var total = 0;
    projects.forEach((project) => {
      if(project.work_items.length != 0){
        total += (this.tallyPaidWorkItemsTotal(project.work_items) * 100);
      }            
    });
    return total / 100; 		
	},

	/** 
	 * Returns the total crew cost still owed in a set of projects.
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsOwingCrewCost (projects) {
    var total = (parseFloat(this.tallyProjectsTotalCrewCost(projects)) * 100) 
    						- (parseFloat(this.tallyProjectsTotalPaidCrewCost(projects)) * 100);

    return total / 100;		
	},

	/** 
	 * Returns the total bottom line in a set of projects (Invoices paid - crew cost)
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsTotalBottomLine (projects) {
    var total = (parseFloat(this.tallyProjectsInvoicesPaidTotal(projects)) * 100) 
    						- (parseFloat(this.tallyProjectsTotalCrewCost(projects)) * 100);

    return total / 100;		
	},

	/** 
	 * Returns the 'sub bottom line'. (Bottom line - outstanding invoices total)
	 *
	 * @param Array projects - an array of Project models
	 * @return Float
	*/
	tallyProjectsSubBottomLine (projects) {
    var total = (parseFloat(this.tallyProjectsTotalBottomLine(projects)) * 100) 
    						- (parseFloat(this.tallyProjectsInvoicesOutstandingTotal(projects)) * 100);

    return total / 100;		
	}

}