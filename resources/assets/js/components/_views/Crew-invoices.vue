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
					          	Crew Invoices			          	
					          </v-toolbar-title>				          				          
					          <v-spacer></v-spacer>
					        </v-toolbar><!-- /Card toolbar -->	
					      </v-container>			        
					      <v-container>
					      	<v-layout row>
				      			<p class="subheading pl-4">						          
 											This is where you can view and interact with your crew's invoices. 		
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
							          Use the view button on each invoice row to view the full invoice.   			
					        		</p>					        						        							        							        				        		
					        	</v-flex>				        	
					        </v-layout>		        	
				        </v-container><!-- / Container for helpful tips -->	
				        <!-- Card body -->
				        <v-card-text>				 
				        	<v-layout row>
				        		<v-flex xs12>
				        			<invoices-table :table_state="'admin'"></invoices-table>
				        		</v-flex>
				        	</v-layout>
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
	import Helpers from './../../store/helpers';
	import InvoicesTable from './../invoice/Invoices-table';

  export default {
  	components: {
  		'invoices-table': InvoicesTable
  	},

  	data () {
  		return {

  		}
  	},

  	methods: {

  	}

  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>