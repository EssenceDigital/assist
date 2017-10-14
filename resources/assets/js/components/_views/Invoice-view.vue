<template>
	<card-layout
		v-if="currentInvoice && invoice_state"
	>
		<div slot="title">
    	Invoice 
    	<small>
      	<span class="grey--text">
      		({{ currentInvoice.from_date | date }} - {{ currentInvoice.to_date | date }})
      	</span>
    	</small>			
		</div>
		<div slot="description" v-if="invoice_state == 'full'">
			This is where you can add your hours and costs to the invoice.
		</div>
		<div slot="additional" v-if="invoice_state == 'full'">
      <v-btn
      	v-if="!currentInvoice.is_paid" 
      	flat 
      	class="success--text" 
      	v-tooltip:top="{ html: 'Add Work Item' }"
      	@click.native.stop="workItemDialog = true"
      >
      	<v-icon left class="success--text">add_circle</v-icon>
      	Work Item
      </v-btn>			
		</div>
		<div slot="content">
      <!-- Work items (Hours and desc) -->
      <v-container
      	v-for="item in currentInvoice.work_items" :key="item.id"
      	class="mt-3"
      >
      	<div v-if="invoice_state === 'full'" >
	      	<!-- Edit button container -->
	      	<v-layout
	      		v-if="!currentInvoice.is_paid || invoice_state === 'full'" 
	      		row 
	      		class="mb-2"
	      	>
	      		<v-spacer></v-spacer>
	      		<v-flex xs1 class="text-xs-right">
	       			<!-- Edit button -->
			        <v-btn 
			        	class="mr-0"
			        	icon 
			        	v-tooltip:top="{ html: 'Edit Item' }"
			        	@click.native.stop="editDialog(item.id)"
			        >
			          <v-icon right>settings</v-icon>
			        </v-btn>					        		
	      		</v-flex>
	      		<v-flex xs1 class="text-xs-right">					        						        		
			        <!-- Delete button -->
			        <v-btn 
			        	icon 
			        	class="mr-0 red--text" 
			        	v-tooltip:top="{ html: 'Remove' }"
			        	@click.native.stop="openDeleteDialog('deleteWorkItem', item.id)"
			        >
			          <v-icon>close</v-icon>
			        </v-btn>					        			
	      		</v-flex>      										        
	      	</v-layout>
	      </div>
      	<!-- Work Item -->
        <v-layout row>
        	<v-flex xs3 class="mr-2">
        		<p>
        			{{ item.from_date | dateMinusYear }} - {{ item.to_date | dateMinusYear }}
        		</p>
        	</v-flex>
        	<v-flex xs5>
        		<p class="mb-1">
        			<strong>{{ item.project.client_company_name }}</strong>
        		</p>
        		<p>
        			{{ item.desc }}
        		</p>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs1>
        		<p>
        			{{ item.hours }} Hrs
        		</p>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs1 class="text-xs-right">
	        	<p>
	        		{{ tallySingleWorkItemHoursPay(item) | money }}
	        	</p>						        		
        	</v-flex>
        </v-layout><!-- / Work Item -->
      </v-container><!-- / Work items (Hours and desc) -->

      <!-- Sub total for hours -->
      <v-container>
        <v-divider class="mb-2"></v-divider>		
        <v-layout row>
        	<v-flex xs2>
        		<span class="title">SUBTOTAL:</span>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs2 class="text-xs-right">
        		<span class="title">{{ workHoursTotal | money }}</span>
        	</v-flex>
        </v-layout>
        <v-divider class="mt-2"></v-divider>		        	
      </v-container><!-- / Sub total for hours -->

      <!-- Work items (Travel and Per diem) -->
      <v-container
      	v-for="item in currentInvoice.work_items" :key="item.id"
      	class="mt-3"
      >
      	<div v-if="invoice_state === 'full'">
	      	<!-- Edit button container -->
	      	<v-layout 
	      		v-if="!currentInvoice.is_paid"
	      		row 
	      		class="mb-2"
	      	>
	      		<v-spacer></v-spacer>
	      		<v-flex xs1 class="text-xs-right">
	       			<!-- Edit button -->
			        <v-btn 
			        	class="mr-0"
			        	icon 
			        	v-tooltip:top="{ html: 'Edit Item' }"
			        	@click.native.stop="editDialog(item.id)"
			        >
			          <v-icon right>settings</v-icon>
			        </v-btn>					        		
	      		</v-flex>
	      		<v-flex xs1 class="text-xs-right">					        						        		
			        <!-- Delete button -->
			        <v-btn 
			        	icon 
			        	class="mr-0 red--text" 
			        	v-tooltip:top="{ html: 'Remove' }"
			        	@click.native.stop="openDeleteDialog('deleteWorkItem', item.id)"
			        >
			          <v-icon>close</v-icon>
			        </v-btn>					        			
	      		</v-flex>								        
	      	</v-layout>      		
      	</div>
      	<!-- Work Item -->
        <v-layout row>
        	<v-flex xs3 class="mr-2">
        		<p>
        			{{ item.from_date | dateMinusYear }} - {{ item.to_date | dateMinusYear }}
        		</p>
        	</v-flex>

        	<v-flex xs5>
        		<p class="mb-1">
        			<strong>Mileage:</strong>
        		</p>
        		<p class="mb-1">
        			<strong>Per Diem:</strong> {{ item.per_diem_desc }}
        		</p>
        		<p v-if="item.lodging_cost" class="mb-1">
        			<strong>Lodging:</strong> {{ item.lodging_desc }}
        		</p>
        		<p v-if="item.equipment_cost">
        			<strong>Equipment:</strong> {{ item.equipment_desc }}
        		</p>        		
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs1>
        		<p>
        			{{ item.travel_mileage }} kms
        		</p>
        		
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs1 class="text-xs-right">
	        	<p class="mb-1">
	        		{{ tallySingleWorkItemMileagePay(item) | money }}
	        	</p>
	        	<p class="mb-1">
	        		{{ item.per_diem | money }}
	        	</p>	
	        	<p v-if="item.lodging_cost" class="mb-1">
	        		{{ item.lodging_cost | money }}
	        	</p>
	        	<p v-if="item.equipment_cost">
	        		{{ item.equipment_cost | money }}
	        	</p>	        							        							        		
        	</v-flex>
        </v-layout><!-- / Work Item -->
      </v-container><!-- Work items (Travel and Per diem) -->

      <!-- Sub total for travel and per diem -->
      <v-container>
        <v-divider class="mb-2"></v-divider>		
        <v-layout row>
        	<v-flex xs2>
        		<span class="title">SUBTOTAL:</span>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs2 class="text-xs-right">
        		<span class="title">{{ extraCostsTotal | money }}</span>
        	</v-flex>
        </v-layout>
        <v-divider class="mt-2"></v-divider>		        	
      </v-container><!-- / Sub total for travel and per diem -->

      <!-- Total container -->
      <v-container class="mt-3">
      	<v-divider class="black"></v-divider>	
        <v-layout row class="mt-3">
        	<v-flex xs2>
        		<span class="title">GST TOTAL:</span>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs2 class="text-xs-right">
        		<span class="title">{{ gstTotal | money }}</span>
        	</v-flex>
        </v-layout>					        	
      	<v-layout row class="mt-5">
        	<v-flex xs2>
        		<span class="title">TOTAL:</span>
        	</v-flex>
        	<v-spacer></v-spacer>
        	<v-flex xs2 class="text-xs-right">
        		<span class="headline">{{ invoiceTotal | money }}</span>
        	</v-flex>					        		
      	</v-layout>
      	<v-layout row class="mt-3">
      		<v-flex xs12>
      			<p>
      				<strong>Cheque payable to:</strong> {{ $store.getters.user.company }}
      			</p>
      		</v-flex>
      	</v-layout>
      	<!-- Publish dialog trigger -->
      	<v-layout row class="mt-5">
      		<v-flex xs4 offset-xs4 class="text-xs-center"> 
      			<v-btn
      				v-if="!currentInvoice.is_published && currentInvoice.work_items.length > 0" 
      				@click="publishDialog = true"
      				block 
      				class="info"
      			>
      				<v-icon left>assignment_turned_in</v-icon>
      				Publish
      			</v-btn>
      			<p v-if="currentInvoice.is_published"class="subheading info--text mt-2">
      				<v-icon left class="info--text">assignment_turned_in</v-icon></v-icon> <span>Invoice is published!</span>
      			</p>
      			<v-btn
      				v-if="currentInvoice.work_items.length == 0" 
      				@click="openDeleteDialog('deleteInvoice', currentInvoice.id)"
      				block 
      				class="error mt-3"
      			>
      				<v-icon left>close</v-icon>
      				Remove Invoice
      			</v-btn>
      			<p v-if="currentInvoice.is_paid" class="subheading green--text mt-2">
      				<v-icon left class="green--text">check_circle</v-icon></v-icon> <span>Invoice is paid!</span>
      			</p>
      			<p v-if="currentInvoice && !currentInvoice.is_paid && currentInvoice.is_published" class="subheading red--text mt-2">
      				<v-icon left class="red--text">priority_high</v-icon></v-icon> <span>Not yet paid!</span>
      			</p>       			      			
      		</v-flex>
      	</v-layout>
			</v-container><!-- / Total container -->	

			<!--
	 		 * Invoice asset DIALOGS
			 -->

	    <!-- Work item dialog -->
			<v-layout 
				v-if="invoice_state != 'readonly'"
				row 
				justify-center 
			>
			  <v-dialog v-model="workItemDialog" fullscreen transition="dialog-bottom-transition" :overlay=false>
			    <v-card>
			      <v-toolbar dark class="primary">
			        <v-btn icon @click.native="closeDialog" dark>
			          <v-icon>close</v-icon>
			        </v-btn>
			        <v-toolbar-title>
			        	Work Item
			        </v-toolbar-title>
			        <v-spacer></v-spacer>
		          <v-toolbar-items>
		            <v-btn dark flat @click.native="saveWorkItem" :loading="workItemSaving" :disabled="workItemSaving">Save</v-btn>
		          </v-toolbar-items>		        
			      </v-toolbar>
			 			<v-card-text>
			 				<v-layout row>
			 					<!-- Work item container -->
			 					<v-flex xs6>
					 				<!-- Work item form card container -->
					 				<v-card>
						        <v-card-title>
						          <div class="headline">Basic Details</div>									          
						        </v-card-title>
						        <v-divider></v-divider>	
										<v-card-text>
						        	<!-- Dates row -->
							        <v-layout row class="mt-3">
								        <v-flex xs5>
								          <v-menu
								            lazy
								            :close-on-content-click="false"
								            v-model="fromDateMenu"
								            transition="scale-transition"
								            offset-y
								            full-width
								            :nudge-left="40"
								            max-width="290px"
								          >
								            <v-text-field
								              slot="activator"
								              v-model="workItemForm.from_date.val"
								              label="From Date..."
								              prepend-icon="event"
								              readonly
								              :error="workItemForm.from_date.err"
								            ></v-text-field>
								            <v-date-picker v-model="workItemForm.from_date.val" no-title scrollable actions>
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
								        <v-flex xs5>
								          <v-menu
								            lazy
								            :close-on-content-click="false"
								            v-model="toDateMenu"
								            transition="scale-transition"
								            offset-y
								            full-width
								            :nudge-left="40"
								            max-width="290px"
								          >
								            <v-text-field
								              slot="activator"
								              v-model="workItemForm.to_date.val"
								              label="To Date..."
								              prepend-icon="event"
								              readonly
								              :error="workItemForm.to_date.err"
								            ></v-text-field>
								            <v-date-picker v-model="workItemForm.to_date.val" no-title scrollable actions>
								              <template scope="{ save, cancel }">
								                <v-card-actions>
								                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
								                  <v-btn flat primary @click.native="save()">Save</v-btn>
								                </v-card-actions>
								              </template>
								            </v-date-picker>
								          </v-menu>               
								        </v-flex>		        	
							        </v-layout><!-- / Dates row -->

							        <!-- Project row -->
							        <v-layout row class="mt-3">
								        <v-flex xs12>
								          <v-select
								            v-model="workItemForm.project_id.val"
								            :items="projectsSelectList"
								            label="Projects..."
								            single-line
								            bottom
								            :error="workItemForm.project_id.err"
								          ></v-select>        
								        </v-flex>		        	
							        </v-layout><!-- / Project row -->

							        <!-- Desc row -->
							        <v-layout row class="mt-3">
							        	<v-flex xs12>
							        		<v-text-field
							        			v-model="workItemForm.desc.val"
							        			label="Very brief description of work..."
							        			multi-line
							        			:error="workItemForm.desc.err"
							        		></v-text-field>
							        	</v-flex>
							        </v-layout><!-- / Desc row -->

							        <!-- Hours row  -->
							        <v-layout row class="mt-3">
							        	<v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.hours.val"
							              label="Total Hours"
							              suffix="Hrs"
							              :error="workItemForm.hours.err"
							            ></v-text-field>
							        	</v-flex>	
							        	<v-spacer></v-spacer>
									      <v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.hourly_rate.val"
							              label="Hourly Rate"
							              prefix="$"
							              :error="workItemForm.hourly_rate.err"
							            ></v-text-field>												      	
									      </v-flex>			        		        	
							        </v-layout><!-- /Hours row  -->
							        	
							        <!-- Per diem row  -->
							        <v-layout row class="mt-3">
							        	<v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.per_diem.val"
							              label="Total Per Diem"
							              prefix="$"
							              :error="workItemForm.per_diem.err"
							            ></v-text-field>
							        	</v-flex>	
							        	<v-spacer></v-spacer>
									      <v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.per_diem_desc.val"
							              label="Per Diem Description"
							              :error="workItemForm.per_diem_desc.err"
							            ></v-text-field>												      	
									      </v-flex>			        		        	
							        </v-layout><!-- /Per diem row  -->										
										</v-card-text>														       
					 				</v-card>	 						
			 					</v-flex><!--/ Work item form card container -->	

			 					<v-spacer></v-spacer>

			 					<!-- Costs container -->
			 					<v-flex xs5>
					 				<!-- Travel item form card container -->
					 				<v-card>
						        <v-card-title>
						          <div class="headline">Travel Mileage</div>									          
						        </v-card-title>
						        <v-divider></v-divider>	
										<v-card-text>
							        <!-- Travel mileage row  -->
							        <v-layout row class="mt-3">
							        	<v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.travel_mileage.val"
							              label="Travel Mileage"
							              suffix="km"
							              :error="workItemForm.travel_mileage.err"
							            ></v-text-field>
							        	</v-flex>	
							        	<v-spacer></v-spacer>
									      <v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.mileage_rate.val"
							              label="Mileage Rate"
							              prefix="$"
							              :error="workItemForm.mileage_rate.err"
							            ></v-text-field>												      	
									      </v-flex>			        		        	
							        </v-layout><!-- /Travel mileage row  -->									
										</v-card-text>					       
					 				</v-card><!--/ Travel item form card container -->		 	

					 				<!-- Lodging cost form card container -->
					 				<v-card class="mt-3">
						        <v-card-title>
						          <div class="headline">Lodging</div>									          
						        </v-card-title>
						        <v-divider></v-divider>	
										<v-card-text>

							        <!-- Lodging row  -->
							        <v-layout row class="mt-3">
							        	<v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.lodging_desc.val"
							              label="Description"
							              :error="workItemForm.lodging_desc.err"
							            ></v-text-field>
							        	</v-flex>	
							        	<v-spacer></v-spacer>
									      <v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.lodging_cost.val"
							              label="Cost"
							              prefix="$"
							              :error="workItemForm.lodging_cost.err"
							            ></v-text-field>												      	
									      </v-flex>			        		        	
							        </v-layout><!-- /Lodging row  -->										
										</v-card-text>					       
					 				</v-card><!--/ Lodging cost form card container -->	

					 				<!-- Equipment cost form card container -->
					 				<v-card class="mt-3">
						        <v-card-title>
						          <div class="headline">Equipment</div>									          
						        </v-card-title>
						        <v-divider></v-divider>	
										<v-card-text>

							        <!-- Equipment row  -->
							        <v-layout row class="mt-3">
							        	<v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.equipment_desc.val"
							              label="Description"
							              :error="workItemForm.equipment_desc.err"
							            ></v-text-field>
							        	</v-flex>	
							        	<v-spacer></v-spacer>
									      <v-flex xs5>
							            <v-text-field
							            	v-model="workItemForm.equipment_cost.val"
							              label="Cost"
							              prefix="$"
							              :error="workItemForm.equipment_cost.err"
							            ></v-text-field>												      	
									      </v-flex>			        		        	
							        </v-layout><!-- /Equipment row  -->										
										</v-card-text>					       
					 				</v-card><!--/ Other cost form card container -->	

			 					</v-flex><!--/ Costs container -->
			 				</v-layout> 


			 			</v-card-text>
			    </v-card>
			  </v-dialog>
			</v-layout>    

	    <!-- Publish invoice dialog -->
	    <v-layout 
	      row 
	      justify-center 
	      class="mr-0" 
	      style="position: relative;"
	    >
	      <v-dialog v-model="publishDialog" width="365" lazy absolute persistent>      
	        <v-card>
	          <v-card-title>
	            <div class="headline grey--text">Publish this invoice?</div>                           
	          </v-card-title>
	          <v-divider></v-divider>
	          <v-card-text>
	            Are you sure you want to publish this invoice?
	          </v-card-text>
	          <v-card-actions>
	            <v-spacer></v-spacer>
	            <!-- Cancel button-->
	            <v-btn outline 
	              class="red--text darken-1" 
	              flat="flat" 
	              @click.native.stop="publishDialog = false">
	                Maybe not
	            </v-btn>
	            <!-- Confirm button -->
	            <v-btn outline 
	            class="green--text darken-1" 
	            flat="flat" 
	            :loading="publishing" 
	            :disable="publishing" 
	            @click.native.stop="publishInvoice">
	              Do it
	            </v-btn>
	          </v-card-actions>
	        </v-card>
	      </v-dialog>
	    </v-layout><!-- / Confirm publish dialog --> 

	    <!-- Remove asset dialog and button -->
			<v-layout 
				v-if="invoice_state != 'readonly'"
				row 
				justify-center 
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
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import Helpers from './../../store/helpers';
	import BusLogic from './../../resources/bus-logic';

	export default {
		props: ['id', 'invoice_state'],

		components: {
			'card-layout': CardLayout
		},

		computed: {
			currentInvoice () {
				return this.$store.getters.currentInvoice;
			},

			workItems () {
				return this.$store.getters.currentInvoice.work_items;
			},

			projectsSelectList () {
				return this.$store.getters.projectsSelectList;
			},

			workHoursTotal () {
				// Tally with helper
				return BusLogic.tallyWorkItemsHoursPay(this.currentInvoice.work_items).toFixed(2);			
			},

			extraCostsTotal () {
				// Tally with helper
				return BusLogic.tallyWorkItemsExtraCosts(this.currentInvoice.work_items).toFixed(2);				
			},

			invoiceSubTotal () {
				return BusLogic.tallyWorkItemsSubTotal(this.currentInvoice.work_items).toFixed(2);
			},

			gstTotal () {
				return BusLogic.tallyWorkItemsGst(this.currentInvoice.work_items).toFixed(2);			
			},

			invoiceTotal () {
				return BusLogic.tallyWorkItemsTotal(this.currentInvoice.work_items).toFixed(2);			
			}
		},

		data () {
			return {
				// Loading
				loading: false,	
				// For the delete dialog
				deleteDialog: false,
				deleteDispatchAction: '',
				deletingAsset: false,
				assetId: '',
				// The work item form and dialog
        fromDateMenu: false,
        toDateMenu: false,				
				workItemDialog: false,
				workItemSaving: false,
				workItemDispatchAction: 'addWorkItem',
				workItemForm: {
					id: {val: '', err: false, errMsg: '', dflt: ''},
					project_id: {val: '', err: false, errMsg: '', dflt: ''},
					invoice_id: {val: this.id, err: false, errMsg: '', dflt: this.id},	
					from_date: {val: '', err: false, errMsg: '', dflt: ''},
					to_date: {val: '', err: false, errMsg: '', dflt: ''},					
					desc: {val: '', err: false, errMsg: '', dflt: ''},
					hours: {val: '', err: false, errMsg: '', dflt: ''},
					hourly_rate: {val: '', err: false, errMsg: '', dflt: ''},
					per_diem: {val: '', err: false, errMsg: '', dflt: ''},
					per_diem_desc: {val: '', err: false, errMsg: '', dflt: ''},
					travel_mileage: {val: '', err: false, errMsg: '', dflt: ''},
					mileage_rate: {val: '', err: false, errMsg: '', dflt: ''},
					lodging_desc: {val: '', err: false, errMsg: '', dflt: ''},
					lodging_cost: {val: '', err: false, errMsg: '', dflt: ''},
					equipment_desc: {val: '', err: false, errMsg: '', dflt: ''},
					equipment_cost: {val: '', err: false, errMsg: '', dflt: ''}					
				},
				// For publish dialog
				publishDialog: false,
				// Publishing loader
				publishing: false
			}
		},

		methods: {

      tallySingleWorkItemHoursPay (item) {
        return BusLogic.tallySingleWorkItemHoursPay(item).toFixed(2);
      },

      tallySingleWorkItemMileagePay (item) {
        return BusLogic.tallySingleWorkItemsMileagePay(item).toFixed(2);
      },

			editDialog (work_item_id) {
				// Find the requested work item
				var data = this.currentInvoice.work_items.find(elem => elem.id === work_item_id);
				// Populate form
				for(var key in this.workItemForm) {
					this.workItemForm[key].val = data[key];
				}		
				// Toggle dialog
				this.workItemDialog = true;
				// Change action
				this.workItemDispatchAction = 'updateWorkItem';
			},

			closeDialog () {
				// Reset the form
				Helpers.resetForm(this.workItemForm);
				// Toggle dialog
				this.workItemDialog = false;
				// Revert action to default
				this.workItemDispatchAction = 'addWorkItem';				
			},

			openDeleteDialog (dispatchAction, assetId) {
				// Set the delete dispatch action
				this.deleteDispatchAction = dispatchAction;			
				// Toggle dialog
				this.deleteDialog = true;
				// Set asset id
				this.assetId = assetId;
				console.log(this.assetId);
			},

			closeDeleteDialog () {
				// Set the delete dispatch action
				this.deleteDispatchAction = '';			
				// Toggle dialog
				this.deleteDialog = false;
				// Reset asset id
				this.assetId = '';				
			},

			saveWorkItem (work_item_id) {
				// Toggle loader
				this.workItemSaving = true;

				/* Ensure work item from date is before to date
				*/ 
				var itemFrom = new Date(this.workItemForm.from_date.val),
						itemTo = new Date(this.workItemForm.to_date.val);
				// Compare dates and return false if to date is before from date
				if(itemTo.getTime() < itemFrom.getTime()){
					// Toggle loader 
					this.workItemSaving = false;					
					// Emit even to show snackbar alert						
					this.$router.app.$emit('show-snackbar', {
						text: "'From date' must be before 'To Date'."
					});					
					// Return false
					return false;
				}

				/* Ensure work item date range is within the invoice date range
				*/ 
				var invoiceFrom = new Date(this.currentInvoice.from_date),
						invoiceTo = new Date(this.currentInvoice.to_date);
				// Compare dates and return false if the work item date range is outside the invoice date range
				if(itemFrom.getTime() < invoiceFrom.getTime() || itemTo.getTime() > invoiceTo.getTime()){
					// Toggle loader 
					this.workItemSaving = false;					
					// Emit even to show snackbar alert						
					this.$router.app.$emit('show-snackbar', {
						text: "The work item must date range be within the invoice date range."
					});						
					// Return false
					return false;					
				}

				/* If everything checks out then use a helper to populate the post data and then dispatch the 
				 * save event
				*/
				Helpers.populatePostData(this.workItemForm)
					.then((data) => {
						// Dispatch event
						this.$store.dispatch(this.workItemDispatchAction, data)
							// Successful request
							.then((response) => {							
								// Toggle dialog
								this.workItemDialog = false;
								// Toggle loader
								this.workItemSaving = false;		
								// Revert action to default
								this.workItemDispatchAction = 'addWorkItem';															
								// Reset form
								Helpers.resetForm(this.workItemForm);
								//
							})
							// Unsuccessful request
							.catch((errors) => {
								if(!errors.response.data.error){
									// Populate formm with errors using helper
									Helpers.populateFormErrors(this.workItemForm, errors.response.data).
										then(() => {
											// Toggle loader
											this.workItemSaving = false;							
										});
								} else {
									// Toggle loader
									this.workItemSaving = false;
									// Emit even to show snackbar alert						
									this.$router.app.$emit('show-snackbar', {
										text: errors.response.data.message
									});																	
								}
							});
					});
			},	

			deleteAsset () {
				// Toggle loader
				this.deletingAsset = true;
				// Dispatch event
				this.$store.dispatch(this.deleteDispatchAction, this.assetId)
					.then(() => {
						// If an entire invoice was deleted then redirect back to my-invoices
						if(this.deleteDispatchAction === 'deleteInvoice'){
							this.$router.push('/my-invoices');
						}
						// Toggle loader
						this.deletingAsset = false;						
						// Toggle dialog
						this.deleteDialog = false;
						// Reset asset id
						this.assetId = '';
					})
					.catch((errors) => {
						// Toggle loader
						this.deletingAsset = false;						
						// Toggle dialog
						this.deleteDialog = false;
						// Reset asset id
						this.assetId = '';
					});
			},

			publishInvoice () {
				// Do not publish if there is zero work items on invoice
				if(this.currentInvoice.work_items.length === 0){
					// Toggle dialog
					this.publishDialog = false;					
					return false;
				}
				// Toggle loader
				this.publishing = true;
				// Dispatch event
				this.$store.dispatch('publishInvoice', { id: this.id })
					.then(() => {
						// Toggle loader 
						this.publishing = false;
						// Toggle dialog
						this.publishDialog = false;
					});
			}						

		},

		created () {
			// Toggle loader
			this.loading = true;
			// Dispatch event to retrieve invoice
			this.$store.dispatch('getInvoice', this.id)
				.then(() => {
					this.$store.dispatch('getUsersProjects')
						.then(() => {
							// Toggle loader
							this.loading = false;
						});
				});
			
		}
	}
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>