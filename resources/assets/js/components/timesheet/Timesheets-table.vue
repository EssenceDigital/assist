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
        <v-flex xs3>
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


      <!-- Data table -->
      <v-data-table
        :headers="headers"
        :items="timesheets"
        :rows-per-page-items="perPage"
        class="elevation-1 mt-2"
        >    
        <template slot="items" scope="props">

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
                readonly="true"
              ></timesheet>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn outline class="red--text darken-1" flat="flat" @click.native="closeFullTimesheetDialog">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout><!-- / view full timesheet dialog --> 

  </v-container><!-- /Container -->

</template>

<script>
  import Helpers from './../../store/helpers';  
  import Totals from './Timesheets-totals';
  import Timesheet from './Timesheet'; 

  export default {

    components: {
      'totals': Totals,
      'timesheet': Timesheet
    },

    data () {
      return {
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
        currentTimesheet: false     
      }
    },

    computed: {
      timesheets () {
        return this.$store.getters.timesheets;
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
          user_id: this.userIdFilter
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
        this.currentTimesheet = timesheet;
      },

      closeFullTimesheetDialog (){
        // Toggle dialog
        this.fullTimesheetDialog = false;
        // Reset current timesheet
        this.currentTimesheet = false;
      }
    },

    created () {
      // Toggle loader
      this.loading = true;

      var payload = false;

      // Tell store to load timesheets
      this.$store.dispatch('getAllTimesheets', payload).then( () => {
        // Tell store to update unique clients
        this.$store.dispatch('getUsers').then( () => {
          // Tell store to update all projects
          this.$store.dispatch('getProjects').then( () => {
            // Toggle loader
            this.loading = false;             
          });
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