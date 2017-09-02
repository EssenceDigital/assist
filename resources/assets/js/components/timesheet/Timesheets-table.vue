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

      </v-layout><!-- /Row for timesheets filter -->

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
          <td>${{ props.item.per_diem }}</td>
          <td><small>{{ timesheetHours(props.item.work_jobs) }} hr</small></td>
          <td>{{ timesheetTravel(props.item.travel_jobs) }} km</td>
          <td>${{ timesheetEquipment(props.item.equipment_rentals) }}</td>
          <td>${{ timesheetOther(props.item.other_costs) }}</td>
          <td>
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'View timesheet'}"
              class="success--text"
              @click.native.stop=""
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
  </v-container><!-- /Container -->

</template>

<script>
  import Helpers from './../../store/helpers';  

  export default {

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
          { text: 'Per Diem', value: 'per_diem', align: 'left' },
          { text: 'Hours', value: '', align: 'left' },          
          { text: 'Travel', value: 'project_id', align: 'left' },
          { text: 'Equipment', value: '', align: 'left' },
          { text: 'Other', value: 'project_id', align: 'left' },
          { text: 'Actions', value: '', align: 'left' }
        ]
      }
    },

    computed: {
      timesheets () {
        return this.$store.getters.timesheets;
      },

      users () {
        return this.$store.getters.usersSelectList;
      }
    },

    methods: {
      filterTimesheets () {

      },

      timesheetHours (workJobs) {
        return Helpers.calcTimesheetHours(workJobs);
      },

      timesheetTravel (travelJobs) {
        return Helpers.calcTimesheetTravel(travelJobs);
      },

      timesheetEquipment (equipmentRentals) {
        return Helpers.calcTimesheetEquipment(equipmentRentals);
      },

      timesheetOther (otherCosts) {
        return Helpers.calcTimesheetOther(otherCosts);
      }
    },

    created () {
      // Toggle loader
      this.loading = true;

      var payload = false;

      // Tell store to load projects
      this.$store.dispatch('getAllTimesheets', payload).then( () => {
        // Tell store to update unique clients
        this.$store.dispatch('getUsers').then( () => {
          // Toggle loader
          this.loading = false;           
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