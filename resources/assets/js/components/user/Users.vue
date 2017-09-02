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
                      Find Users                   
                    </v-toolbar-title>                                    
                    <v-spacer></v-spacer>
                  </v-toolbar><!-- /Card toolbar -->  
                </v-container>              
                <v-container>
                  <v-layout row>
                    <p class="subheading pl-4">
                      This is where you can find and view all of the users in the system.           
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
                        Click on the view full user arrow in the action column to see the full user profile.                  
                      </p>                                                                                  
                    </v-flex>                 
                  </v-layout>             
                </v-container><!-- / Container for helpful tips --> 
                <!-- Card body -->
                <v-card-text>        
                  <v-layout row>
                    <v-flex xs12>
                      <!-- Users table component -->
                      <users-table 
                        class="mt-2 mb-5"
                      ></users-table>
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
  import UsersTable from './Users-table';

  export default {
    components: {
      'users-table': UsersTable
    }

  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>