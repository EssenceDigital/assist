<template>
 <v-layout row>
    <v-flex xs12 sm6 offset-sm3>
      <v-card>
      	<!-- Timesheet title, date -->
	  		<v-layout row>
	  			<v-flex xs12 class="text-xs-center pt-3">
	  				<p class="subheading grey--text">TIMESHEET</p>	
	  				<p class="title">{{ timesheet.date }}</p>
	  			</v-flex>
	  		</v-layout><!-- /Timesheet title, date -->

	  		<v-divider></v-divider>

	  		<!-- Timesheet action buttons -->
        <v-card-actions>
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

        <v-divider></v-divider>

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
			        	<v-flex xs5>
		        			<div>
			        			<p class="body-2 mb-1">Job Type</p>
			        			<p class="grey--text mb-0">{{ job.job_type }}</p>        				
		        			</div>	        		
			        	</v-flex>
			        	<v-flex xs5>
		        			<div>
			        			<p class="body-2 mb-1">Hours</p>
			        			<p class="grey--text mb-1">{{ job.hours_worked }}</p>        				
		        			</div>	        		
			        	</v-flex>	 
			        	<v-spacer></v-spacer>
			        	<v-flex xs1>
					        <!-- Edit button -->
					        <v-btn 
					        	icon 
					        	v-tooltip:top="{ html: 'Edit Job' }"
					        	@click.native=""
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

        </v-card-text>
      </v-card>
    </v-flex>

    <!-- Add work hours dialog -->
		<v-layout row justify-center style="position: relative;">
	    <v-dialog v-model="hoursDialog" width="765" lazy absolute>    
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
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="hoursDialog = false">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 
	          	@click="addHours"
	          	:loading="hoursAdding"
	          >
	          	Add Hours
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- / Add work hours dialog -->

    <!-- Add travel hours dialog -->
		<v-layout row justify-center style="position: relative;">
	    <v-dialog v-model="travelDialog" width="765" lazy absolute>    
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
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="travelDialog = false">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 

	          >
	          	Add Travel
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add travel hours dialog -->	 

    <!-- Add equipment dialog -->
		<v-layout row justify-center style="position: relative;">
	    <v-dialog v-model="equipmentDialog" width="765" lazy absolute>    
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
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="equipmentDialog = false">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 

	          >
	          	Add Equipment
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add equipment dialog -->	   	

    <!-- Add other cost dialog -->
		<v-layout row justify-center style="position: relative;">
	    <v-dialog v-model="otherDialog" width="765" lazy absolute>    
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
	          <v-btn outline class="red--text darken-1" flat="flat" @click.native="otherDialog = false">Cancel</v-btn>
	          <v-btn outline class="green--text darken-1" flat="flat" 

	          >
	          	Add Cost
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add other cost dialog -->  

  </v-layout>	
</template>

<script>
	import Helpers from './../../store/helpers';

	export default {
		props: ['timesheet'],

		data () {
			return {
				hoursDialog: false,
				hoursAdding: false,
				hoursForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: ''},
					job_type: {val: '', err: false, dflt: ''},	
					hours_worked: {val: '0', err: false, dflt: '0'},
					comment: {val: '', err: false, dflt: ''}
				},
				travelDialog: false,
				travelForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: ''},
					travel_distance: {val: '0', err: false, dflt: '0'},	
					travel_time: {val: '0', err: false, dflt: '0'},
					comment: {val: '', err: false, dflt: ''}
				},
				equipmentDialog: false,
				equipmentForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: ''},
					equipment_type: {val: '', err: false, dflt: ''},	
					rental_fee: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				},
				otherDialog: false,
				otherForm: {
					id: {val: '', err: false, dflt: ''},
					timesheet_id: {val: this.timesheet.id, err: false, dflt: ''},
					cost_name: {val: '', err: false, dflt: ''},	
					cost: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				}												
			}
		},

		methods: {


			addHours () {
				// Use helper to create post object then dispatch event to add hours
				Helpers.populatePostData(this.hoursForm).then( (data) => {
					// Dispatch event
					this.$store.dispatch('addTimesheetHours', data).then( () => {
						// Toggle dialog
						this.hoursDialog = false;
					});
				});
			}
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