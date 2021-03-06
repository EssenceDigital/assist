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
			<!-- For showing user alerts and feedback -->
	    <v-snackbar
	    	v-model="snackbar"
	      :timeout="timeout"
	      :absolute="true"
	      :multi-line="true"
	      class="absolute-center"
	    >
	      {{ snackBarText }}
	      <v-btn flat class="pink--text" @click.native.stop="snackbar = false">Close</v-btn>
	    </v-snackbar>

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
						        	<v-flex xs3 class="text-xs-right">
						        		<slot name="additional"></slot>
						        	</v-flex>			      	

						      	</v-layout>
						      </v-container>
					        <v-divider></v-divider>
					        <!-- Container for helpful tips -->
					        <v-container v-if="tips" fluid class="mt-2 pb-0">				        	
						        <v-layout row>
						        	<v-flex xs11>	

							        	<!-- 
												 * Any tips for page usage (prop)
							        	-->						        	
				        				<p 
				        					v-if="!hideTips"
				        					v-for="tip in tips"
				        					class="subheading info--text pl-4"
				        				>
													<v-icon left class="info--text">help_outline</v-icon>
								         	{{ tip.text }}     			
						        		</p>	

						        	</v-flex>
						        	<v-flex xs1 class="nmt-2">
						        		<v-btn
						        			@click="hideTips = !hideTips"
						        			flat 
						        			icon
						        			v-tooltip:top="{ html: 'Toggle Tips' }"
						        		>						        			
						        			<v-icon v-if="hideTips" class="display-2 info--text">expand_less</v-icon>
						        			<v-icon v-else class="display-2 info--text">expand_more</v-icon>
						        		</v-btn>
						        	</v-flex>				        	
						        </v-layout>		        	
					        </v-container><!-- / Container for helpful tips -->	
					        <!-- Card body -->
					        <v-card-text class="pt-0">				 
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

		data() {
			return {
				hideTips: false,
    		snackbar: false,
        mode: '',
        timeout: 6000,
        snackBarText: 'Hello, I\'m a snackbar'
      }
		},

		components: {
			'notify-wrapper': NotifyWrapper
		},

		created () {
			this.$router.app.$on('show-snackbar', (config) => {
				this.snackbar = true;
				this.snackBarText = config.text;		
			});	
		}
	}
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
  .nmt-2{
  	margin-top: -20px;
  }
  .absolute-center{
  	top: 40%;
  }
</style>