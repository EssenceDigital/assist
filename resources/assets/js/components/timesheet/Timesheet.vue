<template>
 <v-layout row>
    <v-flex xs12 sm10 offset-sm1>
      <v-card>
	  		<v-layout row class="pr-2">		        	
        	<v-flex xs1 offset-xs11>
		        <!-- Edit button -->
		        <v-btn 
		        	v-if="!readonly"
		        	icon 
		        	v-tooltip:top="{ html: 'Edit Timesheet' }"
		        	@click.native.stop="editTimesheetDialog = true"
		        >
		          <v-icon>settings</v-icon>
		        </v-btn>	        		
        	</v-flex>
	  		</v-layout>

      	<!-- Timesheet title, date -->
	  		<v-layout row>
	  			<v-flex xs12 class="text-xs-center pt-3">
	  				<p class="subheading grey--text">TIMESHEET</p>	
	  				<p class="title">{{ timesheet.date | date}}</p>
	  			</v-flex>
	  		</v-layout><!-- /Timesheet title, date -->

	  		<v-divider></v-divider>

	  		<!-- Timesheet action buttons -->
        <v-card-actions v-if="!readonly">
        	<v-spacer></v-spacer>
          <v-btn flat class="orange--text"
          	@click.native.stop="hoursDialog = true"
          >
          	<v-icon left class="orange--text">add_circle</v-icon>
          	Hours
          </v-btn>
          <v-btn flat class="orange--text"
          	@click.native.stop="travelDialog = true"
          >
          	<v-icon left class="orange--text">add_circle</v-icon>
          	Travel
          </v-btn>
          <v-btn flat class="orange--text"
          	@click.native.stop="equipmentDialog = true"
          >
          	<v-icon left class="orange--text">add_circle</v-icon>
          	Equipment
          </v-btn> 
          <v-btn flat class="orange--text"
          	@click.native.stop="otherDialog = true"
          >
          	<v-icon left class="orange--text">add_circle</v-icon>
          	Other
          </v-btn>      
          <v-spacer></v-spacer>             
        </v-card-actions><!-- /Timesheet action buttons -->

        <v-divider v-if="!readonly"></v-divider>

        <v-card-text>
        	<!-- Timesheet basics -->
        	<v-layout row>        		
        		<v-flex xs12>
        			<div>
	        			<p class="body-2 mb-1">Per Diem</p>
	        			<p class="grey--text">${{ timesheet.per_diem }}</p>        				
        			</div>
        			<div v-if="timesheet.comment">
	        			<p class="body-2 mb-1">Comment</p>
	        			<p class="grey--text">{{ timesheet.comment }}</p>         				
        			</div>
        		</v-flex>
        	</v-layout><!-- /Timesheet basics -->

        	<v-divider class="mb-3"></v-divider>

        	<!-- Travel hours -->
        	<v-container v-if="timesheet.travel_jobs.length > 0"
        		fluid 
        		class="pt-0 pl-0 pr-0"
        	>
        		<v-layout row class="mb-3">
        			<v-chip class="primary white--text ml-0">Travel Hours</v-chip>
        		</v-layout>
        		<!-- Travel jobs -->
        		<div v-for="job in timesheet.travel_jobs" :key="job.id">
	        		<v-layout row>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Distance</p>
			        			<p class="grey--text mb-1">{{ job.travel_distance }} kms</p>        				
		        			</div>	        		
			        	</v-flex>	        		
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Hours</p>
			        			<p class="grey--text mb-0">{{ job.travel_time }}</p>        				
		        			</div>	        		
			        	</v-flex>	 
			        	<v-spacer></v-spacer>
			        	<v-flex xs1>
					        <!-- Delete button -->
					        <v-btn
					        	v-if="!readonly" 
					        	icon 
					        	class="mr-0 red--text" 
					        	v-tooltip:top="{ html: 'Remove' }"
					        	@click.native.stop="openDeleteDialog('deleteTimesheetTravel', job.id)"
					        >
					          <v-icon>close</v-icon>
					        </v-btn>
					      </v-flex>			        	
			        	<v-flex xs1>
					        <!-- Edit button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	v-tooltip:top="{ html: 'Edit Job' }"
					        	@click.native.stop="editDialog('travel', 'travel_jobs', job.id)"
					        >
					          <v-icon>settings</v-icon>
					        </v-btn>	        		
			        	</v-flex>         			
	        		</v-layout><!-- /Travel jobs -->
	        		<v-layout row v-if="job.comment">
	        			<v-flex xs12>
		        			<p class="body-2 mb-1">Comment</p>
		        			<p class="grey--text mb-0">{{ job.comment }}</p>           				
	        			</v-flex>
	        		</v-layout> 
	        		<v-divider class="mt-2 mb-2"></v-divider>     			
        		</div>
        	</v-container><!-- /Travel hours -->

        	<!-- Work hours -->
        	<v-container v-if="timesheet.work_jobs.length > 0"
        		fluid 
        		class="pt-0 pl-0 pr-0"
        	>
        		<v-layout row class="mb-3">
        			<v-chip class="primary white--text ml-0">Work Hours</v-chip>
        		</v-layout>
        		<!-- Work jobs -->
        		<div v-for="job in timesheet.work_jobs" :key="job.id">
	        		<v-layout row>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Job Type</p>
			        			<p class="grey--text mb-0">{{ job.job_type }}</p>        				
		        			</div>	        		
			        	</v-flex>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Hours</p>
			        			<p class="grey--text mb-1">{{ job.hours_worked }}</p>        				
		        			</div>	        		
			        	</v-flex>	 
			        	<v-spacer></v-spacer>
			        	<v-flex xs1>
					        <!-- Delete button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	class="mr-0 red--text" 
					        	v-tooltip:top="{ html: 'Remove' }"
					        	@click.native.stop="openDeleteDialog('deleteTimesheetHours', job.id)"
					        >
					          <v-icon>close</v-icon>
					        </v-btn>
					      </v-flex>			        	
			        	<v-flex xs1>			        	
					        <!-- Edit button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	v-tooltip:top="{ html: 'Edit Job' }"
					        	@click.native.stop="editDialog('hours', 'work_jobs', job.id)"
					        >
					          <v-icon>settings</v-icon>
					        </v-btn>	        		
			        	</v-flex>         			
	        		</v-layout><!-- /Work jobs -->
	        		<v-layout row v-if="job.comment">
	        			<v-flex xs12>
		        			<p class="body-2 mb-1">Comment</p>
		        			<p class="grey--text mb-0">{{ job.comment }}</p>           				
	        			</v-flex>
	        		</v-layout> 
	        		<v-divider class="mt-2 mb-2"></v-divider>     			
        		</div>
        	</v-container><!-- /Work hours -->        	

        	<!--Equipment rentals -->
        	<v-container v-if="timesheet.equipment_rentals.length > 0"
        		fluid 
        		class="pt-0 pl-0 pr-0"
        	>
        		<v-layout row class="mb-3">
        			<v-chip class="primary white--text ml-0">Equipment Rentals</v-chip>
        		</v-layout>
        		<!-- Work jobs -->
        		<div v-for="job in timesheet.equipment_rentals" :key="job.id">
	        		<v-layout row>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Equipment Type</p>
			        			<p class="grey--text mb-0">{{ job.equipment_type }}</p>        				
		        			</div>	        		
			        	</v-flex>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Rental Fee</p>
			        			<p class="grey--text mb-1">${{ job.rental_fee }}</p>        				
		        			</div>	        		
			        	</v-flex>	 
			        	<v-spacer></v-spacer>
			        	<v-flex xs1>
					        <!-- Delete button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	class="mr-0 red--text" 
					        	v-tooltip:top="{ html: 'Remove' }"
					        	@click.native.stop="openDeleteDialog('deleteTimesheetEquipment', job.id)"
					        >
					          <v-icon>close</v-icon>
					        </v-btn>
					      </v-flex>			        	
			        	<v-flex xs1>
					        <!-- Edit button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	v-tooltip:top="{ html: 'Edit Job' }"
					        	@click.native.stop="editDialog('equipment', 'equipment_rentals', job.id)"
					        >
					          <v-icon>settings</v-icon>
					        </v-btn>	        		
			        	</v-flex>         			
	        		</v-layout><!-- /Work jobs -->
	        		<v-layout row v-if="job.comment">
	        			<v-flex xs12>
		        			<p class="body-2 mb-1">Comment</p>
		        			<p class="grey--text mb-0">{{ job.comment }}</p>           				
	        			</v-flex>
	        		</v-layout> 
	        		<v-divider class="mt-2 mb-2"></v-divider>     			
        		</div>
        	</v-container><!-- /Equipment rentals--> 

        	<!-- Other costs -->
        	<v-container v-if="timesheet.other_costs.length > 0"
        		fluid 
        		class="pt-0 pl-0 pr-0"
        	>
        		<v-layout row class="mb-3">
        			<v-chip class="primary white--text ml-0">Other Costs</v-chip>
        		</v-layout>
        		<!-- Work jobs -->
        		<div v-for="job in timesheet.other_costs" :key="job.id">
	        		<v-layout row>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Cost Name</p>
			        			<p class="grey--text mb-0">{{ job.cost_name }}</p>        				
		        			</div>	        		
			        	</v-flex>
			        	<v-flex xs4>
		        			<div>
			        			<p class="body-2 mb-1">Cost</p>
			        			<p class="grey--text mb-1">${{ job.cost }}</p>        				
		        			</div>	        		
			        	</v-flex>	 
			        	<v-spacer></v-spacer>
			        	<v-flex xs1>
					        <!-- Delete button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	class="mr-0 red--text" 
					        	v-tooltip:top="{ html: 'Remove' }"
					        	@click.native.stop="openDeleteDialog('deleteTimesheetOther', job.id)"
					        >
					          <v-icon>close</v-icon>
					        </v-btn>
					      </v-flex>			        	
			        	<v-flex xs1>
					        <!-- Edit button -->
					        <v-btn 
					        	v-if="!readonly"
					        	icon 
					        	v-tooltip:top="{ html: 'Edit Job' }"
					        	@click.native.stop="editDialog('other', 'other_costs', job.id)"
					        >
					          <v-icon>settings</v-icon>
					        </v-btn>	        		
			        	</v-flex>         			
	        		</v-layout><!-- /Work jobs -->
	        		<v-layout row v-if="job.comment">
	        			<v-flex xs12>
		        			<p class="body-2 mb-1">Comment</p>
		        			<p class="grey--text mb-0">{{ job.comment }}</p>           				
	        			</v-flex>
	        		</v-layout> 
	        		<v-divider class="mt-2 mb-2"></v-divider>     			
        		</div>
        	</v-container><!-- /Other costs--> 
        </v-card-text>
      </v-card>
    </v-flex>

    <!-- Add work hours dialog -->
		<v-layout v-if="!readonly" row justify-center style="position: relative;">
	    <v-dialog v-model="hoursDialog" width="765" lazy absolute persistent>    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Add work hours</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <!-- Container for tips -->
	        <v-container>
									        	
	        </v-container><!-- /Container for tips -->
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs6>
				        <v-select
				        	class="pb-0"
				          v-model="hoursForm.job_type.val"
				          :items="[
				          	{ text: 'Fieldwork', value: 'Fieldwork' },
				          	{ text: 'Documentation', value: 'Documentation' },
				          	{ text: 'Report Writting', value: 'Report Writting' }
				          ]"
				          label="Job Type..."
				          single-line
				          bottom
				          :error="hoursForm.job_type.err"
				        ></v-select>       
				      </v-flex>	
				      <v-spacer></v-spacer>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="hoursForm.hours_worked.val"
		              label="Hours"
		              type="number"
		              step="0.25"
		              min="0"
		              suffix="Hrs"
		            ></v-text-field>												      	
				      </v-flex>							        		
	        	</v-layout>
	        	<v-layout row>
	        		<v-flex xs12>
		            <v-text-field
		            	v-model="hoursForm.comment.val"
		              label="Comment"
									multi-line
		            ></v-text-field>									        			
	        		</v-flex>
	        	</v-layout>
		        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeDialog('hours')">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click="saveForm('hours')"
	          	:loading="hoursSaving"
	          >
	          	Save Hours
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- / Add work hours dialog -->

    <!-- Add travel hours dialog -->
		<v-layout v-if="!readonly" row justify-center style="position: relative;">
	    <v-dialog v-model="travelDialog" width="765" lazy absolute persistent>    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Add travel hours</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <!-- Container for tips -->
	        <v-container>
									        	
	        </v-container><!-- /Container for tips -->
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="travelForm.travel_distance.val"
		              label="Distance"
		              type="number"
		              step="10"
		              min="0"
		              suffix="kms"
		            ></v-text-field>     
				      </v-flex>	
				      <v-spacer></v-spacer>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="travelForm.travel_time.val"
		              label="Hours"
		              type="number"
		              step="0.25"
		              min="0"
		              suffix="Hrs"
		            ></v-text-field>												      	
				      </v-flex>							        		
	        	</v-layout>
	        	<v-layout row>
	        		<v-flex xs12>
		            <v-text-field
		            	v-model="travelForm.comment.val"
		              label="Comment"
									multi-line
		            ></v-text-field>									        			
	        		</v-flex>
	        	</v-layout>
		        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeDialog('travel')">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click="saveForm('travel')"
	          	:loading="travelSaving"
	          >
	          	Save Travel
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add travel hours dialog -->	 

    <!-- Add equipment dialog -->
		<v-layout v-if="!readonly" row justify-center style="position: relative;">
	    <v-dialog v-model="equipmentDialog" width="765" lazy absolute persistent>    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Add equipment rental</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <!-- Container for tips -->
	        <v-container>
									        	
	        </v-container><!-- /Container for tips -->
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="equipmentForm.equipment_type.val"
		              label="Type"
		            ></v-text-field>      
				      </v-flex>	
				      <v-spacer></v-spacer>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="equipmentForm.rental_fee.val"
		              label="Rental Fee"
		              prepend-icon="attach_money"
		            ></v-text-field>												      	
				      </v-flex>							        		
	        	</v-layout>
	        	<v-layout row>
	        		<v-flex xs12>
		            <v-text-field
		            	v-model="equipmentForm.comment.val"
		              label="Comment"
									multi-line
		            ></v-text-field>									        			
	        		</v-flex>
	        	</v-layout>
		        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeDialog('equipment')">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click="saveForm('equipment')"
	          	:loading="equipmentSaving"
	          >
	          	Save Equipment
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add equipment dialog -->	   	

    <!-- Add other cost dialog -->
		<v-layout v-if="!readonly" row justify-center style="position: relative;">
	    <v-dialog v-model="otherDialog" width="765" lazy absolute persistent>    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Add other cost</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <!-- Container for tips -->
	        <v-container>
									        	
	        </v-container><!-- /Container for tips -->
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="otherForm.cost_name.val"
		              label="Cost Name"
		            ></v-text-field>       
				      </v-flex>	
				      <v-spacer></v-spacer>
				      <v-flex xs6>
		            <v-text-field
		            	v-model="otherForm.cost.val"
		              label="Cost"
		              prepend-icon="attach_money"
		            ></v-text-field>													      	
				      </v-flex>							        		
	        	</v-layout>
	        	<v-layout row>
	        		<v-flex xs12>
		            <v-text-field
		            	v-model="otherForm.comment.val"
		              label="Comment"
									multi-line
		            ></v-text-field>									        			
	        		</v-flex>
	        	</v-layout>
		        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeDialog('other')">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click="saveForm('other')"
	          	:loading="otherSaving"
	          >
	          	Save Cost
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add other cost dialog -->  

    <!-- Remove asset dialog and button -->
		<v-layout v-if="!readonly" row justify-center 
			class="mr-0" 
			style="position: relative;"
		>
	    <v-dialog v-model="deleteDialog" width="365" lazy absolute persistent>	    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Delete this?</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <v-card-text>
	        	Are you sure you want to delete this?
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <!-- Cancel delete button-->
	          <v-btn outline 
	          	class="red--text darken-1" 
	          	flat="flat" 
	          	@click.native.stop="closeDeleteDialog">
	          		Maybe not
	          </v-btn>
	          <!-- Confirm delete button -->
	          <v-btn outline 
	          class="green--text darken-1" 
	          flat="flat" 
	          :loading="deletingAsset" 
	          :disable="deletingAsset" 
	          @click.native.stop="deleteAsset">
	          	Do it
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- Remove asset dialog and button -->

    <!-- Edit timesheet dialog -->
		<v-layout v-if="!readonly" row justify-center style="position: relative;">
	    <v-dialog v-model="editTimesheetDialog" width="765" lazy absolute persistent>	    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Edit a timesheet</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs6>
								<v-menu
								  lazy
								  :close-on-content-click="false"
								  v-model="dateMenu"
								  transition="scale-transition"
								  offset-y
								  full-width
								  :nudge-left="40"
								  max-width="290px"
								>
								  <v-text-field
								  	slot="activator"
								    v-model="form.date.val"
								    label="Date..."
								    prepend-icon="event"
								    readonly
								  ></v-text-field>
								  <v-date-picker v-model="form.date.val" no-title scrollable actions>
								    <template scope="{ save, cancel }">
								      <v-card-actions>
								        <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
								        <v-btn flat primary @click.native="save()">Save</v-btn>
								      </v-card-actions>
								    </template>
								  </v-date-picker>
								</v-menu>        
				      </v-flex>	
				      <v-spacer></v-spacer>
				      <v-flex xs6>
								<v-text-field
									v-model="form.per_diem.val"
								  label="Per Diem"
								  single-line
								  prepend-icon="attach_money"
								></v-text-field>												      	
				      </v-flex>							        		
	        	</v-layout>
	        	<v-layout row>
	        		<v-flex xs12>
								<v-text-field
									v-model="form.comment.val"
								  label="Comment"
								  multi-line
								></v-text-field>										        			
	        		</v-flex>
	        	</v-layout>
		        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="editTimesheetDialog = false">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click.native="editTimesheet" 
	          	:loading="timesheetSaving"
	          >
	          	Save Timesheet
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- / edit timesheet dialog -->		  

  </v-layout>	
</template>

<script>
	import Helpers from './../../store/helpers';

	export default {
		props: ['timesheet', 'readonly'],

		data () {
			return {
				// The edit timesheet form	
				editTimesheetDialog: false,		
				timesheetSaving: false,
				form: {				
					id: {val: '', err: false, dflt: ''},
					project_id: {val: '', err: false, dflt: ''},
					date: {val: '', err: false, dflt: ''},	
					per_diem: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				},	
				// For date picker
				dateMenu: false,								
				// For the delete dialog
				deleteDialog: false,
				deleteDispatchAction: '',
				deletingAsset: false,
				assetId: '',
				// The asset forms
				hoursDialog: false,
				hoursSaving: false,
				hoursDispatchAction: 'addTimesheetHours',
				hoursForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: this.timesheet.id},
					job_type: {val: '', err: false, dflt: ''},	
					hours_worked: {val: '0', err: false, dflt: '0'},
					comment: {val: '', err: false, dflt: ''}
				},
				travelDialog: false,
				travelSaving: false,
				travelDispatchAction: 'addTimesheetTravel',
				travelForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: this.timesheet.id},
					travel_distance: {val: '0', err: false, dflt: '0'},	
					travel_time: {val: '0', err: false, dflt: '0'},
					comment: {val: '', err: false, dflt: ''}
				},
				equipmentDialog: false,
				equipmentSaving: false,
				equipmentDispatchAction: 'addTimesheetEquipment',
				equipmentForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: this.timesheet.id},
					equipment_type: {val: '', err: false, dflt: ''},	
					rental_fee: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				},
				otherDialog: false,
				otherSaving: false,
				otherDispatchAction: 'addTimesheetOther',
				otherForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: this.timesheet.id},
					cost_name: {val: '', err: false, dflt: ''},	
					cost: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				}												
			}
		},

		methods: {

			closeDialog (dialog) {
				// Close dialog
				this[dialog + 'Dialog'] = false;				
				// Clear form
				for(var key in this[dialog + 'Form']){
					if(key != 'timesheet_id') {
						// Reset field value
						this[dialog + 'Form'][key].val = this[dialog + 'Form'][key].dflt;						
					}
				}
				// Reset the dispatch action
				this[dialog + 'DispatchAction'] = 'addTimesheet' + dialog.charAt(0).toUpperCase() + dialog.slice(1);
			},

			editDialog (dialog, fieldKey, id) {
				var data = this.timesheet[fieldKey].find(elem => elem.id === id);
				// Populate form
				for(var key in this[dialog + 'Form']) {
					this[dialog + 'Form'][key].val = data[key];
				}
				// Change the dispatch action
				this[dialog + 'DispatchAction'] = 'updateTimesheet' + dialog.charAt(0).toUpperCase()+ dialog.slice(1);				
				// Toggle dialog
				this[dialog + 'Dialog'] = true;
			},

			openDeleteDialog (dispatchAction, assetId) {
				// Set the delete dispatch action
				this.deleteDispatchAction = dispatchAction;
				// Toggle dialog
				this.deleteDialog = true;
				// Set asset id
				this.assetId = assetId;
			},

			closeDeleteDialog () {
				// Set the delete dispatch action
				this.deleteDispatchAction = '';
				// Toggle dialog
				this.deleteDialog = false;
				// Reset asset id
				this.assetId = '';				
			},

			saveForm (formPrefix) {
				// Toggle loader
				this[formPrefix + 'Saving'] = true;
				// Use helper to create post object then dispatch event to add hours
				Helpers.populatePostData(this[formPrefix + 'Form']).then( (data) => {
					// Dispatch event
					this.$store.dispatch(this[formPrefix + 'DispatchAction'], data).then( () => {
						// Toggle dialog
						this[formPrefix + 'Dialog'] = false;
						// Toggle loader
						this[formPrefix + 'Saving'] = false;	
						// Reset form
						Helpers.resetForm(this[formPrefix + 'Form']);
					});
				});
			},

			editTimesheet () {
				// Toggle loader
				this.timesheetSaving = true;
				// Use helper to create post object
				Helpers.populatePostData(this.form).then( (data) => {
					// Dispatch event
					this.$store.dispatch('updateTimesheet', data).then( () => {
						// Toggle loader
						this.timesheetSaving = false;
						// Close dialog
						this.editTimesheetDialog = false;
					});					
				});

			},


			deleteAsset () {
				// Toggle loader
				this.deletingAsset = true;
				// Dispatch event
				this.$store.dispatch(this.deleteDispatchAction, this.assetId).then( () => {
					// Toggle loader
					this.deletingAsset = false;
					// Toggle dialog
					this.deleteDialog = false;
					// Reset asset id
					this.assetId = '';
				});
			}

		},

		created () {
			Helpers.populateForm(this.form, this.timesheet);
		}

	}
</script>

<style scoped>
	.chip {
		height: auto;
		white-space: normal;
		padding: 8px 16px 8px 16px;
	}
</style>