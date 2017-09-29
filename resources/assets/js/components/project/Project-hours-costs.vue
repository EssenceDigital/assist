<template>
	<div>
		<v-container class="mt-5">
			<v-divider class="mb-4"></v-divider>	
			<v-layout row>
				<v-flex xs12>
      		<p class="mb-1">
      			<span class="title">HOURS</span>
      		</p>					
				</v-flex>
			</v-layout>			 
		</v-container>
	  <!-- Work items (Hours and desc) -->
	  <v-container
	  	v-for="item in workItems" :key="item.id"
	  	class="mt-3"
	  >
	  	<!-- Work Item -->
	    <v-layout row>
	    	<v-flex xs3>
	    		<p>
	    			{{ item.from_date | dateMinusYear }} - {{ item.to_date | dateMinusYear }}
	    		</p>
	    	</v-flex>
	    	<v-spacer></v-spacer>
	    	<v-flex xs2>
	    		<strong>User Name</strong>
	    	</v-flex>	    	
	    	<v-spacer></v-spacer>
	    	<v-flex xs4>
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
      <v-layout row>
      	<v-flex xs3>
      		<p class="mb-1">
      			<span class="title"><em>SUBTOTAL:</em></span>
      		</p>
      		<p>
      			<small>(Not including GST)</small>
      		</p>      		
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="title red--text">{{ totalWorkHoursPay | money }}</span>
      	</v-flex>
      </v-layout>
      <v-divider class="mt-2"></v-divider>		        	
    </v-container><!-- / Sub total for hours -->  

		<v-container class="mt-2">
			<v-divider class="mb-4"></v-divider>	
			<v-layout row>
				<v-flex xs12>
      		<p class="mb-1">
      			<span class="title">Travel, Per Diem, &amp; Other</span>
      		</p>					
				</v-flex>
			</v-layout>			 
		</v-container>
    <!-- Work items (Travel and Per diem) -->
    <v-container
    	v-for="item in this.workItems" :key="item.id"
    	class="mt-3"
    >
    	<!-- Work Item -->
      <v-layout row>
      	<v-flex xs3>
      		<p>
      			{{ item.from_date | dateMinusYear }} - {{ item.to_date | dateMinusYear }}
      		</p>
      	</v-flex>
	    	<v-spacer></v-spacer>
	    	<v-flex xs2>
	    		<strong>User Name</strong>
	    	</v-flex>	      	
      	<v-spacer></v-spacer>
      	<v-flex xs4>
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
        		{{ tallySingleWorkItemsMileagePay(item) | money }}
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
      <v-layout row>
      	<v-flex xs3>
      		<span class="subheading"><em>MILEAGE (SUBTOTAL):</em></span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">{{ totalTravelMileageCost | money }}</span>
      	</v-flex>
      </v-layout>	
      <v-layout row class="mt-3">
      	<v-flex xs3>
      		<span class="subheading"><em>PER DIEM (SUBTOTAL):</em></span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">{{ totalPerDiemCost | money }}</span>
      	</v-flex>
      </v-layout>	 
      <v-layout row class="mt-3">
      	<v-flex xs3>
      		<span class="subheading"><em>LODGING (SUBTOTAL):</em></span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">{{ totalLodgingCost | money}}</span>
      	</v-flex>
      </v-layout> 
      <v-layout row class="mt-3">
        <v-flex xs3>
          <span class="subheading"><em>EQUIPMENT (SUBTOTAL):</em></span>
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex xs2 class="text-xs-right">
          <span class="subheading">{{ totalEquipmentCost | money }}</span>
        </v-flex>
      </v-layout>                      
      <v-layout row class="mt-4">
      	<v-flex xs3>
	      	<p class="mb-1">
	      		<span class="title"><em>SUBTOTAL:</em></span>
	      	</p>
      		<p>
      			<small>(Not including GST)</small>
      		</p>      		
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="title red--text">{{ totalExtraCosts | money }}</span>
      	</v-flex>
      </v-layout>
      <v-divider class="mt-2"></v-divider>		        	
    </v-container><!-- / Sub total for travel and per diem -->

    <!-- Total container -->
    <v-container class="mt-2">
    	<v-divider class="black"></v-divider>	

    	<v-layout row class="mt-5">
      	<v-flex xs3>
      		<span class="title">TOTAL COST:</span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="headline red--text">{{ totalProjectCost | money }}</span>
      	</v-flex>					        		
    	</v-layout>

    	<v-layout row class="mt-3">
      	<v-flex xs3>
      		<p class="mb-1">
      			<span class="title">CLIENT INVOICE:</span>
      		</p>
      		<p v-if="!invoicePaidDate">
      			<small class="warning--text">
      				<v-icon left class="warning--text">clear</v-icon>
      				(NOT PAID)
      			</small>
      		</p> 
      		<p v-else>
      			<small class="green--text">
      				<v-icon left class="green--text">done</v-icon>
      				(PAID)
      			</small>
      		</p>      		
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 
      		v-if="clientInvoicedAmount != 0"
      		class="text-xs-right"       		
      	>
      		<span v-if="invoicePaidDate" class="headline green--text">{{ clientInvoicedAmount | money }}</span>
      		<span v-else class="headline">{{ clientInvoicedAmount | money }}</span>
      	</v-flex>	
      	<v-flex xs2 
      		v-else
      		class="text-xs-right"
      	>
					<v-chip class="info white--text">
			      NOT INVOICED
			    </v-chip>	      		
      	</v-flex>				        		
    	</v-layout>     	   	
		</v-container><!-- / Total container -->	  

    <!-- Profit or loss container -->
    <v-container class="mt-0">
    	<v-divider class="black"></v-divider>	
    	<v-layout row class="mt-3">
      	<v-flex xs3>
      		<p class="mb-1">
      			<span class="title">BOTTOM LINE:</span>
      		</p>       		
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span v-if="totalBottomLine > 0" class="headline green--text">+{{ totalBottomLine | money }}</span>
      		<span v-else class="headline red--text">-{{ (totalBottomLine * -1).toFixed(2) | money }}</span>
      	</v-flex>					        		
    	</v-layout>     	   	
		</v-container><!-- / Profit or loss container -->			         	
	</div>	
</template>

<script>
	import Helpers from './../../store/helpers';
  import BusLogic from './../../resources/bus-logic';

	export default {
		props: ["workItems", "clientInvoicedAmount", "invoicePaidDate"],

		computed: {
			totalInvoicesNum () {
				if(this.workItems){
					return BusLogic.tallyProjectInvoices(this.workItems);
				}				
			},

			totalWorkHours () {
				if(this.workItems){
					return BusLogic.tallyWorkItemsHours(this.workItems).toFixed(2);
				}
			},

			totalWorkHoursPay () {
				if(this.workItems) {
					return BusLogic.tallyWorkItemsHoursPay(this.workItems).toFixed(2);
				}
			},

			totalTravelMileageCost () {
				if(this.workItems) {
					return BusLogic.tallyWorkItemsTravelMileageCost(this.workItems).toFixed(2);
				}
			},

			totalPerDiemCost () {
				if(this.workItems) {
					return BusLogic.tallyWorkItemsPerDiemCost(this.workItems).toFixed(2);
				}
			},

			totalLodgingCost () {
				if(this.workItems) {
					return BusLogic.tallyWorkItemsLodgingCost(this.workItems).toFixed(2);
				}
			},

      totalEquipmentCost () {
        if(this.workItems) {
          return BusLogic.tallyWorkItemsEquipmentCost(this.workItems).toFixed(2);
        }
      },

			totalExtraCosts () {
				if(this.workItems){
					return BusLogic.tallyWorkItemsExtraCosts(this.workItems).toFixed(2);
				}
			},

			totalProjectCost () {
				return (parseFloat(this.totalWorkHoursPay) + parseFloat(this.totalExtraCosts)).toFixed(2);
			},

			totalBottomLine () {
				var invoiceAmount = 0;
				// Determine invoice amount
				if(this.invoicePaidDate){
					invoiceAmount = this.clientInvoicedAmount;
				} 
				return (parseFloat(invoiceAmount) - parseFloat(this.totalProjectCost)).toFixed(2);
			}
		},

    methods: {
      tallySingleWorkItemHoursPay (item) {
        return BusLogic.tallySingleWorkItemHoursPay(item).toFixed(2);
      },

      tallySingleWorkItemsMileagePay (item) {
        return BusLogic.tallySingleWorkItemsMileagePay(item).toFixed(2);
      }
    }
	}
</script>