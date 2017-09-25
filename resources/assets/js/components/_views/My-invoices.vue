<template>
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			Invoices You've Created	
		</div>
		<div slot="dialog">
      <!-- Add invoice dialog -->
			<v-layout row justify-center style="position: relative;" class="mr-0">
		    <v-dialog v-model="addInvoiceDialog" width="765" lazy absolute>
	        <!-- Add project button -->
					<v-btn slot="activator" flat class="success--text" v-tooltip:top="{ html: 'Add Invoice' }">
					<v-icon left class="success--text">add_circle</v-icon>
						Invoice
					</v-btn>   
		      <v-card>
		        <v-card-title>
		          <div class="headline grey--text">Start an invoice</div>									          
		        </v-card-title>
		        <v-divider></v-divider>
		        <!-- Container for tips -->
		        <v-container>
      				<p class="subheading info--text pl-2">
								<v-icon left class="info--text">help_outline</v-icon>
			          After starting the invoice you will be able to add your hours and other costs.        			
	        		</p>									        	
		        </v-container><!-- /Container for tips -->
		        <v-card-text>
							<v-layout row>
								<v-flex xs12>
									<v-layout row>
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
						              v-model="form.from_date.val"
						              label="From Date..."
						              prepend-icon="event"
						              readonly
						              :error="form.from_date.err"
						            ></v-text-field>
						            <v-date-picker v-model="form.from_date.val" no-title scrollable actions>
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
						              v-model="form.to_date.val"
						              label="To Date..."
						              prepend-icon="event"
						              readonly
						              :error="form.to_date.err"
						            ></v-text-field>
						            <v-date-picker v-model="form.to_date.val" no-title scrollable actions>
						              <template scope="{ save, cancel }">
						                <v-card-actions>
						                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
						                  <v-btn flat primary @click.native="save()">Save</v-btn>
						                </v-card-actions>
						              </template>
						            </v-date-picker>
						          </v-menu>               
						        </v-flex>												
									</v-layout>
								</v-flex>
							</v-layout>	
			        	
		        </v-card-text>
		        <v-card-actions>
		          <v-spacer></v-spacer>
		          <v-btn outline class="red--text darken-1" flat="flat" @click.native="addInvoiceDialog = false">Cancel</v-btn>
		          <v-btn outline class="green--text darken-1" flat="flat" 
		          	@click.native="startInvoice"
		          	:loading="startingInvoice"
		          	:disabled="startingInvoice"
		          >Start Invoice</v-btn>
		        </v-card-actions>
		      </v-card>
		    </v-dialog>
		  </v-layout><!-- / Add project dialog -->			
		</div>
		<div slot="description">
			This is where you can create invoices for the work you've done. 
		</div>
		<div slot="content">
			<invoices-table :table_state="'user'"></invoices-table>
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import Helpers from './../../store/helpers';
	import InvoicesTable from './../invoice/Invoices-table';

  export default {
  	components: {
  		'card-layout': CardLayout,
  		'invoices-table': InvoicesTable
  	},

  	data () {
  		return {
  			// Tips for card layout
  			tips: [
  			 { text: "Use the view button on each project row to view your timesheets for that project or add new ones." }
  			],
  			// For invoice dialog and loading
  			addInvoiceDialog: false,
  			startingInvoice: false,
        // For the date menus
        fromDateMenu: false,
        toDateMenu: false,
        // The start invoice form  			
  			form: {
  				from_date: { val: '', err: false, errMsg: '', dflt: '' },
  				to_date: {val: '', err: false, errMsg: '', dflt: ''}
  			}
  		}
  	},

  	methods: {
  		startInvoice () {
				// Toggle loader
				this.startingInvoice = true;
				// Dispatch event to store
				this.$store.dispatch('addInvoice', {
					from_date: this.form.from_date.val,
					to_date: this.form.to_date.val
				})
					.then((response) => {				
						// Toggle loader
						this.startingInvoice = false;
						this.addInvoiceDialog = false;
						// Forward view
						this.$router.push('/invoices/'+response.id+'/view');
					})  
					.catch((errors) => {
						Helpers.populateFormErrors(this.form, errors.response.data).then(() => {
							// Toggle loader
							this.startingInvoice = false;							
						});
					});		
  		}
  	}

  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>