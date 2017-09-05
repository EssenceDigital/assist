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

                    <!-- Add user dialog -->
                    <v-layout row justify-center style="position: relative;">
                      <v-dialog v-model="addUserDialog" width="765" lazy absolute>
                        <!-- Add User button -->
                        <v-btn slot="activator" icon v-tooltip:top="{ html: 'Add User' }">
                          <v-icon>add_circle</v-icon>
                        </v-btn>      
                        <v-card>
                          <v-card-title>
                            <div class="headline grey--text">Add a user</div>                            
                          </v-card-title>
                          <v-divider></v-divider>
                          <v-card-text>

                            <v-layout row>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.first.val"
                                  label="First Name..."
                                ></v-text-field>                                
                              </v-flex>
                              <v-spacer></v-spacer>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.last.val"
                                  label="Last Name..."
                                ></v-text-field>                                
                              </v-flex>                              
                            </v-layout>
                            <v-layout row>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.email.val"
                                  label="Email (used as username)..."
                                ></v-text-field>                                
                              </v-flex>
                              <v-spacer></v-spacer>
                              <v-flex xs6>
                                <v-select
                                  v-model="form.permissions.val"
                                  label="Permissions..."
                                  :items="[
                                    { text: 'Select...', value: '' },
                                    { text: 'User', value: 'user' },
                                    { text: 'Admin', value: 'admin' }                                    
                                  ]"
                                ></v-select>                                
                              </v-flex>                              
                            </v-layout>
                            <v-layout row>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.company_name.val"
                                  label="Company Name..."
                                ></v-text-field>                                
                              </v-flex>
                              <v-spacer></v-spacer>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.gst_number.val"
                                  label="GST Number..."
                                ></v-text-field>                                
                              </v-flex>                              
                            </v-layout>                            
                            <v-layout row>
                              <v-flex xs6>
                                <v-text-field
                                  v-model="form.hourly_rate_one.val"
                                  label="Hourly Rate..."
                                  prefix="$"
                                ></v-text-field>                                
                              </v-flex>                              
                            </v-layout>
                          </v-card-text>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn outline class="red--text darken-1" flat="flat" @click.native="addUserDialog = false">Cancel</v-btn>
                            <v-btn outline class="green--text darken-1" flat="flat" 
                              @click.native="addUser"
                              :loading="addingUser"
                              :disabled="addingUser"
                            >Add User</v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                    </v-layout><!-- / Add user dialog -->

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
    },

    data () {
      return {
        // For the add users dialog
        addUserDialog: false,
        // User is saving
        addingUser: false,
        form: {
          first: {val: '', err: false, errMsg: '', dflt: ''},
          last: {val: '', err: false, errMsg: '', dflt: ''},
          email: {val: '', err: false, errMsg: '', dflt: ''},
          permissions: {val: '', err: false, errMsg: '', dflt: ''},
          password: {val: '', err: false, errMsg: '', dflt: ''},
          password_confirmation: {val: '', err: false, errMsg: '', dflt: ''},
          company_name: {val: '', err: false, errMsg: '', dflt: ''},
          gst_number: {val: '', err: false, errMsg: '', dflt: ''},
          hourly_rate_one: {val: '0.00', err: false, errMsg: '', dflt: '0.00'},
        }        
      }
    },

    methods: {
      addUser () {

      }
    }

  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>