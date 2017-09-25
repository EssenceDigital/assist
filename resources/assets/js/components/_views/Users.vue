<template>
  <card-layout
    :tips="tips"
  >
    <div slot="title">
      Find Users 
    </div>
    <div slot="dialog">
      <!-- Add user dialog -->
      <v-layout row justify-center style="position: relative;">
        <v-dialog v-model="addUserDialog" width="765" lazy absolute persistent>
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
                    :error="form.first.err"
                  ></v-text-field>                                
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.last.val"
                    label="Last Name..."
                    :error="form.last.err"
                  ></v-text-field>                                
                </v-flex>                              
              </v-layout>
              <v-layout row>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.email.val"
                    label="Email (used as username)..."
                    :error="form.email.err"
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
                    :error="form.permissions.err"
                  ></v-select>                                
                </v-flex>                              
              </v-layout>
              <v-layout row>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.company_name.val"
                    label="Company Name..."
                    :error="form.company_name.err"
                  ></v-text-field>                                
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.gst_number.val"
                    label="GST Number..."
                    :error="form.gst_number.err"
                  ></v-text-field>                                
                </v-flex>                              
              </v-layout>                            
              <v-layout row>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.hourly_rate_one.val"
                    label="Hourly Rate..."
                    :error="form.hourly_rate_one.err"
                    prefix="$"
                  ></v-text-field>                                
                </v-flex>                              
              </v-layout>
              <v-divider class="mt-2 mb-2"></v-divider>
              <v-layout row>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.password.val"
                    label="Temporary Password..."
                    type="password"
                    :error="form.password.err"
                  ></v-text-field>                                
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs6>
                  <v-text-field
                    v-model="form.password_confirmation.val"
                    label="Confirm Password..."
                    type="password"
                    :error="form.password_confirmation.err"
                  ></v-text-field>                                
                </v-flex>                              
              </v-layout>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeAddUserDialog">Cancel</v-btn>
              <v-btn outline class="green--text darken-1" flat="flat" 
                @click.native="addUser"
                :loading="addingUser"
                :disabled="addingUser"
              >Add User</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout><!-- / Add user dialog -->      
    </div>
    <div slot="description">
      This is where you can find and view all of the users in the system.
    </div>
    <div slot="content">
      <!-- Users table component -->
      <users-table 
        class="mt-2 mb-5"
      ></users-table>      
    </div>
  </card-layout>
</template>

<script>
  import CardLayout from './_Card-layout';
  import UsersTable from './../user/Users-table';
  import Helpers from './../../store/helpers'; 

  export default {
    components: {
      'card-layout': CardLayout,
      'users-table': UsersTable
    },

    data () {
      return {
        // For card layout tips
        tips: [

        ],
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
      closeAddUserDialog () {
        Helpers.clearFormErrors(this.form).then( () => this.addUserDialog = false);
      },

      addUser () {
        //Toggle loader
        this.addingUser = true;
        // Populate the data for post
        Helpers.populatePostData(this.form)
          .then( (data) => {
            // Dispatch even to store user
            this.$store.dispatch('addUser', data)
              .then( () => {
                // Toggle loader and dialog
                this.addingUser = false;
                this.addUserDialog = false;
              })
              .catch( (errors) => {
                // Set form errors
                Helpers.populateFormErrors(this.form, errors.response.data).then( () => this.addingUser = false);
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