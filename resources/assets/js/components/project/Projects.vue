/**
 * Main projects page. Shows the add project button and projects table.
*/
<template>
	<v-container>
		<v-layout row class="mt-5">
			<v-flex xs10 offset-xs1>
				<!-- Top most card -->
				<v-card class="grey lighten-5" flat>
					<!-- Red top toolbar -->
				  <v-toolbar dark class="primary elevation-0" extended>
				  	<!-- Back button -->
				    <v-btn icon v-tooltip:top="{ html: 'Go Back' }">
				    	<v-icon dark>arrow_back</v-icon>
				    </v-btn>
				  </v-toolbar><!-- / Red top toolbar -->
				  <!-- Main card container -->
				  <v-layout row>
				    <v-flex xs10 offset-xs1>
				      <v-card class="card--flex-toolbar">
				      	<!-- Card toolbar -->
				        <v-toolbar card class="white" prominent>
				          <v-toolbar-title class="body-2 grey--text">
				          	Projects
				          </v-toolbar-title>
				          <v-spacer></v-spacer>
				          <!-- Add project button -->
				          <v-btn icon v-tooltip:top="{ html: 'Add Project' }">
				            <v-icon>add_circle</v-icon>
				          </v-btn>
				        </v-toolbar><!-- /Card toolbar -->
				        <v-divider></v-divider>
				        <!-- Card body -->
				        <v-card-text>				 
									<v-layout row>
										<v-flex xs12>
											<!-- Projects table component -->
											<projects-table 
												:table_state="'admin'"
												class="mt-5 mb-5"
											></projects-table>
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
	import ProjectsTable from './Projects-table';

  export default {
  	components: {
  		'projects-table': ProjectsTable
  	},

  	created () {
  		// For debug
  		if(this.$store.getters.debug) console.log("Projects component created");
  	}
  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>