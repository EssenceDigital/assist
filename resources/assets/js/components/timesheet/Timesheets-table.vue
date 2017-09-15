<template>
  <v-container fluid>
    <!-- Loading container -->
    <v-layout v-if="loading" row class="mt-5">
      <v-progress-linear v-bind:indeterminate="true"></v-progress-linear>
    </v-layout>

    <!-- Container for table and filter -->
    <v-container v-if="!loading" fluid >

      <!-- Row for timesheet filter -->
      <v-layout row>
        <v-spacer></v-spacer> 
        <v-flex xs2>
          <v-menu
            lazy
            :close-on-content-click="false"
            v-model="fromDateMenu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-left="40"
            max-width="290px"
          >
            <v-text-field
              slot="activator"
              v-model="fromDateFilter"
              label="From Date..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker v-model="fromDateFilter" no-title scrollable actions>
              <template scope="{ save, cancel }">
                <v-card-actions>
                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
                  <v-btn flat primary @click.native="save()">Save</v-btn>
                </v-card-actions>
              </template>
            </v-date-picker>
          </v-menu>               
        </v-flex>  
        <v-spacer></v-spacer>       
        <v-flex xs2>
          <v-menu
            lazy
            :close-on-content-click="false"
            v-model="toDateMenu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-left="40"
            max-width="290px"
          >
            <v-text-field
              slot="activator"
              v-model="toDateFilter"
              label="To Date..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker v-model="toDateFilter" no-title scrollable actions>
              <template scope="{ save, cancel }">
                <v-card-actions>
                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
                  <v-btn flat primary @click.native="save()">Save</v-btn>
                </v-card-actions>
              </template>
            </v-date-picker>
          </v-menu>               
        </v-flex> 
        <v-spacer></v-spacer>
        <v-flex xs3 v-if="table_state === 'admin'">
          <v-select
            v-model="userIdFilter"
            :items="users"
            label="User..."
            single-line
            bottom
          ></v-select>        
        </v-flex>   
        <v-spacer></v-spacer>
        <v-flex xs3>
          <v-select
            v-model="projectIdFilter"
            :items="projectSelectList"
            label="Projects..."
            single-line
            bottom
          ></v-select>        
        </v-flex>
        <v-spacer></v-spacer> 
        <!-- Filter button -->        
        <v-flex xs1>
          <v-btn 
            icon
            class="mt-3" 
            v-tooltip:top="{ html: 'Filter Timesheets' }"
            @click="filterTimesheets"
          >
            <v-icon>search</v-icon>
          </v-btn>
        </v-flex>                                  
      </v-layout><!-- /Row for timesheets filter -->

      <v-layout row>
        <totals :timesheets="timesheets"></totals>
      </v-layout>

      <v-layout row>
        <v-btn
          @click.native.stop="generateInvoice"
        >
          Generate Invoice
          <v-icon right>input</v-icon>          
        </v-btn>
      </v-layout>

      <!-- Data table -->
      <v-data-table
        :headers="headers"
        :items="timesheets"
        :rows-per-page-items="perPage"
        v-model="selected"
        select-all
        selected-key="id"
        class="elevation-1 mt-2"
      >   


        <template slot="items" scope="props">
          <tr>
            <td>
              <v-checkbox
                primary
                hide-details
                v-model="props.selected"
              ></v-checkbox>
            </td>
            <td>{{ props.item.project_id }}</td>
            <td>{{ props.item.date }}</td>
            <td>${{ props.item.timesheet_total }}</td>
            <td>${{ props.item.per_diem }}</td>
            <td>
              ${{ props.item.total_hours_pay }}
              <small>({{ props.item.total_hours }} hrs)</small>
            </td>
            <td>{{ props.item.total_travel_distance }} km</td>
            <td>${{ props.item.total_equipment_cost }}</td>
            <td>${{ props.item.total_other_costs }}</td>
            <td>
              <v-btn 
                icon 
                v-tooltip:top="{ html: 'View timesheet'}"
                class="success--text"
                @click.native.stop="showFullTimesheetDialog(props.item.id)"
              >
                <v-icon>subdirectory_arrow_right</v-icon>
              </v-btn>            
            </td>            
          </tr>

        </template>
        <template slot="pageText" scope="{ pageStart, pageStop }">
          From {{ pageStart }} to {{ pageStop }}
        </template>    
      </v-data-table><!-- /Data table -->  

    </v-container><!-- /Container for table and filter -->

    <!-- View full timesheet dialog -->
    <v-layout row justify-center style="position: relative;">
      <v-dialog v-model="fullTimesheetDialog" width="765" lazy absolute persistent>     
        <v-card>
          <v-card-text>
            <v-container fluid class="mt-3">
              <timesheet
                v-if="currentTimesheet"
                :timesheet="currentTimesheet"
                :readonly="timesheetReadonly"
                @dialog-opened="fullTimesheetDialog = false"
                @dialog-closed="fullTimesheetDialog = true"
              ></timesheet>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeFullTimesheetDialog">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout><!-- / view full timesheet dialog --> 

    <!-- Invoice dialog -->
    <v-layout row justify-center>
      <v-dialog v-model="invoiceDialog" fullscreen transition="dialog-bottom-transition" :overlay=false>
        <v-btn primary dark slot="activator">Open Dialog</v-btn>
        <v-card>
          <v-toolbar dark class="primary">
            <v-btn icon @click.native="invoiceDialog = false" dark>
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Create Invoice</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark flat @click.native="invoiceDialog = false">Create</v-btn>
            </v-toolbar-items>
          </v-toolbar>



        </v-card>
      </v-dialog>
    </v-layout><!-- Invoice dialog -->

  </v-container><!-- /Container -->

</template>

<script>
  import Helpers from './../../store/helpers';  
  import Totals from './Timesheets-totals';
  import Timesheet from './Timesheet'; 

  export default {

    props: ['table_state'],

    components: {
      'totals': Totals,
      'timesheet': Timesheet
    },

    data () {
      return {
        invoiceDialog: false,
        // Loader
        loading: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
        // The headers the data table will use
        headers: [
          { text: 'Project', value: 'project_id', align: 'left' },
          { text: 'Date', value: 'date', align: 'left' },
          { text: 'Total', value: 'timesheet_total', align: 'left' },
          { text: 'Per Diem', value: 'per_diem', align: 'left' },
          { text: 'Hours', value: 'total_hours_pay', align: 'left' },          
          { text: 'Travel', value: 'total_travel_distance', align: 'left' },
          { text: 'Equipment', value: 'total_equipment_cost', align: 'left' },
          { text: 'Other', value: 'total_other_costs', align: 'left' },
          { text: 'Actions', value: '', align: 'left' }
        ],
        pagination: {
          sortBy: 'name'
        },
        selected: [],        
        // For the date menus
        fromDateMenu: false,
        toDateMenu: false,
        // For the filter models
        fromDateFilter: '',
        toDateFilter: '',
        projectIdFilter: '',
        userIdFilter: '',
        // For the view full timesheet dialog
        fullTimesheetDialog: false ,
        // Depending on table state
        authUserOnly: false,
        timesheetReadonly: true        
      }
    },

    computed: {
      timesheets () {
        return this.$store.getters.timesheets;
      },

      currentTimesheet () {
        return this.$store.getters.currentTimesheet;
      },

      users () {
        return this.$store.getters.usersSelectList;
      },

      projectSelectList () {
        return this.$store.getters.projectsSelectList;
      }                     
    },

    methods: {
      filterTimesheets () {
        // Toggle loader
        this.loading = true;
        // Dispatch action to find projects
        this.$store.dispatch('getAllTimesheets', {
          from_date: this.fromDateFilter,
          to_date: this.toDateFilter,
          project_id: this.projectIdFilter,
          user_id: this.userIdFilter,
          auth_user_only: this.authUserOnly
        }).then( () => {
          // Toggle loader
          this.loading = false;
        });
      },

      showFullTimesheetDialog (timesheetId) {
        // Toggle dialog
        this.fullTimesheetDialog = true;
        // Find requested timesheet
        var timesheet = this.timesheets.find(elem => elem.id === timesheetId);
        // Set current timesheet
        this.$store.commit('updateCurrentTimesheet', timesheet);
      },

      closeFullTimesheetDialog (){
        // Toggle dialog
        this.fullTimesheetDialog = false;
        // Reset current timesheet
        this.$store.commit('updateCurrentTimesheet', false);
      },

      generateInvoice () {
        // Will hold the timesheet ids for the invoice
        var selectedIds = [];
        // Populate the Ids
        this.selected.forEach((timesheet) => {
          selectedIds.push(timesheet.id);
        });
        // Open invoice dialog
        if(selectedIds.length > 0){
          this.invoiceDialog = true;
        }
        console.log(selectedIds);
      }   
    },

    created () {
      // Toggle loader
      this.loading = true;
      // Cache
      var initialAction = '',
          secondAction = '',
          payload = false;
      // Set the action based on table state
      if(this.table_state === 'admin'){
        initialAction = 'getAllTimesheets';
        secondAction = 'getProjects';
      } else if(this.table_state === 'user'){
        initialAction = 'getUsersTimesheets'
        secondAction = 'getUsersProjects';
        // Turn on flag for filter use
        this.authUserOnly = true;
        this.timesheetReadonly = false;
      }

      // Tell store to load timesheets
      this.$store.dispatch(initialAction, payload).then( () => {
        // Tell store to update projects
        this.$store.dispatch(secondAction).then( () => {
          // If the state is admin then dispatch another action
          if(this.table_state == 'admin'){
            // Tell store to update all users
            this.$store.dispatch('getUsers').then( () => {
              // Toggle loader
              this.loading = false;             
            });            
          } else {
            // Toggle loader
            this.loading = false;             
          }

        }); 
      });

    }

  }
</script>

<style scoped>
  .center{
    margin-left: auto;
    margin-right: auto; 
  }
</style>