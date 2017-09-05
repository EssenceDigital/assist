<template>
	
	<v-container fluid>
		<v-layout row class="mt-5">
			<v-flex xs12 xl10 offset-xl1>
				<!-- Top most card -->
				<v-card class="grey lighten-5" flat>
					<!-- Red top toolbar -->
				  <v-toolbar dark class="primary elevation-0" extended>
				  	<!-- Back button -->
				    <v-btn 
				    	icon 
				    	v-tooltip:top="{ html: 'Go Back' }"
				    	@click="$router.go(-1)"
				    >
				    	<v-icon dark>arrow_back</v-icon>
				    </v-btn>
				  </v-toolbar><!-- / Red top toolbar -->
				  <!-- Main card container -->
				  <v-layout row>
				    <v-flex xs12 lg10 offset-lg1>
				      <v-card class="card--flex-toolbar">
				      	<v-container>
					      	<!-- Card toolbar -->
					        <v-toolbar card class="white" prominent>
					          <v-toolbar-title class="display-1">				         
					          	Project Timesheets ({{ id }})			          	
					          </v-toolbar-title>				          				          
					          <v-spacer></v-spacer>

					          <!-- Add timesheet dialog -->
										<v-layout row justify-center style="position: relative;">
									    <v-dialog v-model="addTimesheetDialog" width="765" lazy absolute>
								        <!-- Add project button -->
								        <v-btn slot="activator" icon v-tooltip:top="{ html: 'Add Timesheet' }">
								          <v-icon>add_circle</v-icon>
								        </v-btn>	    
									      <v-card>
									        <v-card-title>
									          <div class="headline grey--text">Add a timesheet</div>									          
									        </v-card-title>
									        <v-divider></v-divider>
									        <!-- Container for tips -->
									        <v-container>
						        				<p class="subheading info--text pl-2">
															<v-icon left class="info--text">help_outline</v-icon>
										          Once the timesheet is started you can add hours.        			
								        		</p>									        	
									        </v-container><!-- /Container for tips -->
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
									          <v-btn outline class="red--text darken-1" flat="flat" @click.native="addTimesheetDialog = false">Cancel</v-btn>
									          <v-btn outline class="green--text darken-1" flat="flat" 
									          	@click.native="addTimesheet" 
									          	:loading="timesheetAdding"
									          >
									          	Add Timesheet
									          </v-btn>
									        </v-card-actions>
									      </v-card>
									    </v-dialog>
									  </v-layout><!-- / Add timesheet dialog -->	

					        </v-toolbar><!-- /Card toolbar -->	
					      </v-container>			        
					      <v-container>
					      	<v-layout row>
				      			<p class="subheading pl-4">
 											Now showing all of your timesheets for this project.       		
					        	</p>
					      	</v-layout>
					      </v-container>
				        <v-divider></v-divider>
				        <!-- Container for helpful tips -->
				        <v-container class="mt-4">				        	
					        <v-layout row>
					        	<v-flex xs12>	
			        				<p class="subheading info--text pl-4">
												<v-icon left class="info--text">help_outline</v-icon>
							          Once you add a timesheet you can then add travel hours, work hours, equipment rentals, and other costs.       			
					        		</p>					        						        							        							        				        		
					        	</v-flex>				        	
					        </v-layout>		        	
				        </v-container><!-- / Container for helpful tips -->	
				        <!-- Card body -->
				        <v-card-text>	
				        	<v-container fluid>
					        	<!-- Totals row -->
					        	<v-layout row>
					        		<totals :timesheets="timesheets"></totals>
					        	</v-layout>	

										<!-- No timesheets alert -->
										<v-layout row v-if="timesheets.length === 0" class="mt-5">
											<v-alert info value="true">
									      You have not added any timesheets to this project yet.
									    </v-alert>			
										</v-layout><!-- / No timesheets alert -->

										<v-layout class="mt-5"
											row
											v-for="timesheet in timesheets" 
											:key="timesheet.id"
										>
											<v-flex xs12>
												<timesheet :timesheet="timesheet"></timesheet>
											</v-flex>
										</v-layout>				        		
				        	</v-container>					        	
				        </v-card-text><!-- /Card body -->
				      </v-card>
				    </v-flex>
				  </v-layout><!-- / Main card container -->
				</v-card><!-- / Top most card -->
			</v-flex>
		</v-layout>
	</v-container>

</template>

<script>
	import Timesheet from './Timesheet';
	import Helpers from './../../store/helpers';	
	import Totals from './Timesheets-totals';

	export default {
		props: ['id'],

		components: {
			'timesheet': Timesheet,
			'totals': Totals
		},

		data () {
			return {
				// For the ad timesheet dialog 
				addTimesheetDialog: false,
				// For date picker
				dateMenu: false,	
				timesheetAdding: false,
				// The add timesheet form			
				form: {				
					id: {val: '', err: false, dflt: ''},
					date: {val: '', err: false, dflt: ''},	
					per_diem: {val: '0.00', err: false, dflt: '0.00'},
					comment: {val: '', err: false, dflt: ''}
				}
			
			}
		},

		computed: {
			timesheets () {
				return this.$store.getters.timesheets;
			}
		},

		methods: {
			addTimesheet () {
				// Toggle loader
				this.timesheetAdding = true;
				console.log(this.form);
				// Dispatch event to store
				this.$store.dispatch('addTimesheet', {
					project_id: this.id,
					user_id: this.$store.getters.user.id,
					date: this.form.date.val,
					per_diem: this.form.per_diem.val,
					comment: this.form.comment.val
				}).then( () => {
					// Toggle loader
					this.timesheetAdding = false;
					this.addTimesheetDialog = false;
					// Reset form
					Helpers.resetForm(this.form);
				});
			}
		},

		created () {

			// Dispatch event to store that retrieves all of the users timesheets for the selected project
			this.$store.dispatch('getProjectTimesheets', {
				project_id: this.id,
				user_id: this.$store.getters.user.id
			}).then( () => {

			});
		}

	}
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>