<template>
	<div>
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
	      		${{ (parseFloat(item.hours) * parseFloat(item.hourly_rate)).toFixed(2) }}
	      	</p>						        		
	    	</v-flex>
	    </v-layout><!-- / Work Item -->
  	</v-container><!-- / Work items (Hours and desc) -->		

    <!-- Sub total for hours -->
    <v-container>
      <v-divider class="mb-2"></v-divider>		
      <v-layout row>
      	<v-flex xs3>
      		<span class="title">TOTAL HOURS COST:</span>
      		<small>(Not including GST)</small>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="title">${{ totalWorkHoursPay }}</span>
      	</v-flex>
      </v-layout>
      <v-divider class="mt-2"></v-divider>		        	
    </v-container><!-- / Sub total for hours -->  

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
      		<p v-if="item.lodging_cost">
      			<strong>Lodging:</strong> {{ item.lodging_desc }}
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
        		${{ (parseFloat(item.travel_mileage) * parseFloat(item.mileage_rate)).toFixed(2) }}
        	</p>
        	<p class="mb-1">
        		${{ item.per_diem }}
        	</p>	
        	<p v-if="item.lodging_cost">
        		${{ item.lodging_cost }}
        	</p>						        							        		
      	</v-flex>
      </v-layout><!-- / Work Item -->
    </v-container><!-- Work items (Travel and Per diem) --> 

    <!-- Sub total for travel and per diem -->
    <v-container>
      <v-divider class="mb-2"></v-divider>	
      <v-layout row>
      	<v-flex xs3>
      		<span class="subheading">TRAVEL MILEAGE COST:</span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">${{ totalTravelMileageCost }}</span>
      	</v-flex>
      </v-layout>	
      <v-layout row class="mt-3">
      	<v-flex xs3>
      		<span class="subheading">PER DIEM COST:</span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">${{ totalPerDiemCost }}</span>
      	</v-flex>
      </v-layout>	 
      <v-layout row class="mt-3">
      	<v-flex xs3>
      		<span class="subheading">LODGING COST:</span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="subheading">${{ totalLodgingCost }}</span>
      	</v-flex>
      </v-layout>                 
      <v-layout row class="mt-4">
      	<v-flex xs3>
      		<span class="title">TOTAL OTHER COSTS:</span>
      		<small>(Not including GST)</small>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="title">${{ totalExtraCosts }}</span>
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
      		<span class="title">${{ gstTotal }}</span>
      	</v-flex>
      </v-layout>					        	
    	<v-layout row class="mt-5">
      	<v-flex xs2>
      		<span class="title">TOTAL:</span>
      	</v-flex>
      	<v-spacer></v-spacer>
      	<v-flex xs2 class="text-xs-right">
      		<span class="headline">${{ invoiceTotal }}</span>
      	</v-flex>					        		
    	</v-layout>
    	<v-layout row class="mt-3">
    		<v-flex xs12>
    			<p>
    				<strong>Cheque payable to:</strong> {{ $store.getters.user.company }}
    			</p>
    		</v-flex>
    	</v-layout>
		</v-container><!-- / Total container -->	           	
	</div>	
</template>

<script>
	import Helpers from './../../store/helpers';

	export default {
		props: ["workItems"],

		computed: {
			totalInvoicesNum () {
				if(this.workItems){
					return Helpers.tallyProjectInvoices(this.workItems);
				}				
			},

			totalWorkHours () {
				if(this.workItems){
					return Helpers.tallyWorkItemsHours(this.workItems).toFixed(2);
				}
			},

			totalWorkHoursPay () {
				if(this.workItems) {
					return Helpers.tallyWorkItemsHoursPay(this.workItems).toFixed(2);
				}
			},

			totalTravelMileageCost () {
				if(this.workItems) {
					return Helpers.tallyWorkItemsTravelMileageCost(this.workItems).toFixed(2);
				}
			},

			totalPerDiemCost () {
				if(this.workItems) {
					return Helpers.tallyWorkItemsPerDiemCost(this.workItems).toFixed(2);
				}
			},

			totalLodgingCost () {
				if(this.workItems) {
					return Helpers.tallyWorkItemsLodgingCost(this.workItems).toFixed(2);
				}
			},

			totalExtraCosts () {
				if(this.workItems){
					return Helpers.tallyWorkItemsExtraCosts(this.workItems).toFixed(2);
				}
			}
		}
	}
</script>