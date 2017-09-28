/** 
 * Basic card page layout that most views within the app will make use of. 
 *
 * Uses Notify-wrapper.
*/
<template>
	<notify-wrapper>
		<v-container 
			fluid
			slot="content"
		>
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
					      	<v-container fluid>
						      	<!-- Card toolbar -->
						        <v-toolbar card class="white" prominent>

						        	<!-- 
											 * The title of the page (prop)
						        	-->
						          <v-toolbar-title class="display-1">				         
						          	<slot name="title"></slot>	          	
						          </v-toolbar-title>	

						          <v-spacer></v-spacer>

						        	<!-- 
											 * A possible dialog
						        	-->
						          <slot name="dialog"></slot>

						        </v-toolbar><!-- /Card toolbar -->	
						      </v-container>			        
						      <v-container fluid>
						      	<v-layout row>

						        	<!-- 
											 * The description of the page (prop)
						        	-->	
						        	<v-flex xs9>
						      			<p class="subheading pl-4">						          
		 											<slot name="description"></slot>  		
							        	</p>						        		
						        	</v-flex>		

						        	<!-- 
											 * An optional additional slot (prop)
						        	-->	
						        	<v-flex xs3>
						        		<slot name="additional"></slot>
						        	</v-flex>			      	

						      	</v-layout>
						      </v-container>
					        <v-divider></v-divider>
					        <!-- Container for helpful tips -->
					        <v-container fluid class="mt-4">				        	
						        <v-layout row>
						        	<v-flex xs12>	

							        	<!-- 
												 * Any tips for page usage (prop)
							        	-->						        	
				        				<p 
				        					v-for="tip in tips"
				        					class="subheading info--text pl-4"
				        				>
													<v-icon left class="info--text">help_outline</v-icon>
								         	{{ tip.text }}     			
						        		</p>	

						        	</v-flex>				        	
						        </v-layout>		        	
					        </v-container><!-- / Container for helpful tips -->	
					        <!-- Card body -->
					        <v-card-text>				 
					        	<v-layout row>
					        		<v-flex xs12>

					        			<!-- Slot for page content -->
					        			<slot name="content"></slot>

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
	</notify-wrapper>	
</template>

<script>
	import NotifyWrapper from './_Notify-wrapper';

	export default{
		props: ['tips'],

		components: {
			'notify-wrapper': NotifyWrapper
		}
	}
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>