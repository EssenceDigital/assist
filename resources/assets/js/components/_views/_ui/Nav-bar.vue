/** Primary navigation for the app. 
  * Includes mobile drawer style navigation.
*/
<template>
	<!-- Component container -->
	<v-layout>
		<!-- Medium and large screen menu -->
		<v-toolbar dark class="default">
			<!-- Mobile drawer toggle -->
      <v-toolbar-side-icon
        @click.stop="navDrawer = !navDrawer"
        class="hidden-sm-and-up ">  	
      </v-toolbar-side-icon>
			<!-- Title -->
			<v-toolbar-title>
				<router-link to="/" tag="span" style="cursor: pointer">
					Arrow Assist
				</router-link>
			</v-toolbar-title>
			<!-- Vuetify spacer -->
			<v-spacer></v-spacer>
			<!-- Holds menu items -->
			<v-toolbar-items class="hidden-xs-only">
				<!-- Create nav menu items from data prop -->
				<v-btn 
					flat 
					v-for="item in menuItems" 
					:key="item.title"
					router
					:to="item.link"
					>
						<v-icon left dark>{{ item.icon }}</v-icon>
						{{ item.title }}
				</v-btn>	
				<!-- User options -->
				<v-menu offset-y>
					<v-btn 
						class="pr-0 mr-0"
						flat 
						slot="activator"
						>
							<v-icon right dark class="mt-2">arrow_drop_down_circle</v-icon>
					</v-btn>	
		      <v-list>
		        <v-list-tile @click="$router.push('/user-settings')">
		          <v-list-tile-title>Settings</v-list-tile-title>
		        </v-list-tile>
		        <v-list-tile @click="logout">
		          <v-list-tile-title>Logout</v-list-tile-title>
		        </v-list-tile>		        
		      </v-list>
		    </v-menu>					
			</v-toolbar-items><!-- /Holds menu items -->
		</v-toolbar><!-- /Medium and large screen menu -->

		<!-- Small screen menu side drawer -->
    <v-navigation-drawer temporary v-model="navDrawer">
    	<!-- Menu item list -->
      <v-list>
      	<!-- Create drawer menu items from data prop -->
        <v-list-tile
          v-for="item in menuItems"
          :key="item.title"
          :to="item.link">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>{{ item.title }}</v-list-tile-content>
        </v-list-tile>
        <v-list-tile>
					<v-menu offset-y>
						<v-layout row slot="activator">
		          <v-list-tile-action>
		            <v-icon>arrow_drop_down_circle</v-icon>
		          </v-list-tile-action>
		          <v-list-tile-content >User</v-list-tile-content>							
						</v-layout>

			      <v-list>
			        <v-list-tile>
			          
			        </v-list-tile>
			      </v-list>
			    </v-menu>        	
        </v-list-tile>
      </v-list><!-- /Menu item list -->
    </v-navigation-drawer><!-- /Small screen menu side drawer -->
	</v-layout><!-- /Component container -->
</template>

<script>
	export default {

		computed: {
			authUser () {
				return this.$store.getters.user
			},

			menuItems () {
				if(this.authUser.permissions === 'admin') {
					var menuItems = [
						{ icon: 'dashboard', title: 'Dashboard', link: '/dashboard' },
						{ icon: 'receipt', title: 'My Invoices', link: '/my-invoices' },
						{ icon: 'receipt', title: 'Crew Invoices', link: '/crew-invoices' },
						{ icon: 'assignment', title: 'Projects', link: '/projects' },
						{ icon: 'group', title: 'Users', link: '/users' }
					];
				}

				if(this.authUser.permissions === 'user') {
					var menuItems = [
						{ icon: 'dashboard', title: 'Dashboard', link: '/dashboard' },
						{ icon: 'receipt', title: 'Your Invoices', link: '/your-invoices' }
					];					
				}

				return menuItems;
			}
		},

		data () {
			return{
				navDrawer: false			
			}
		},

		methods: {
			logout () {
				// Logout
				axios.post('/logout')
					.then( (response) => {
						console.log(response);
						// Clear auth user
						this.$store.commit('clearAuthUser');
						// Redirect
						this.$router.go('/login');
					})
					.catch( (error) => {
						console.log(error);
					});
			}
		}
	}
</script>