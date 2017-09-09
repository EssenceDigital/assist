<template>
  <!-- Totals row -->
  <v-container fluid class="pl-0 pr-0">
    <!-- Data table -->
    <v-data-table
      :headers="[
        { text: 'Grand Total', align: 'center', sortable: false },
        { text: 'Total Per Diem', align: 'center', sortable: false },
        { text: 'Total Hours Cost', align: 'center', sortable: false },
        { text: 'Total Equipment Cost', align: 'center', sortable: false },
        { text: 'Total Other Costs', align: 'center', sortable: false }
      ]"
      :items="[
        { 
          timesheets_total: timesheetsTotal,
          total_per_diem: totalPerDiem,
          total_hours: totalHours ,
          total_hours_pay: totalHoursPay,
          total_equipment_cost: totalEquipmentCost,
          total_other_costs: totalOtherCosts
        }
      ]"        
      class="elevation-1 mt-1 mb-4"
      hide-actions
    >    
      <template slot="items" scope="props">
        <td class="text-xs-center">
          <v-chip class="success white--text">
            ${{ props.item.timesheets_total }}
          </v-chip>
        </td> 
        <td class="text-xs-center">
          <v-chip class="info white--text">
            ${{ props.item.total_per_diem }}
          </v-chip>
        </td>                     
        <td class="text-xs-center">
          <v-chip class="info white--text">
            ${{ props.item.total_hours_pay }}&nbsp;<small>({{ props.item.total_hours }} hrs)</small>
          </v-chip>
        </td>
        <td class="text-xs-center">
          <v-chip class="info white--text">
            ${{ props.item.total_equipment_cost }}
          </v-chip>
        </td>
        <td class="text-xs-center">
          <v-chip class="info white--text">
            ${{ props.item.total_other_costs }}
          </v-chip>
        </td>                                    
      </template>  
    </v-data-table><!-- /Data table -->    
  
  </v-container>	
</template>

<script>
  import Helpers from './../../store/helpers';

	export default {
		props: ['timesheets'],

		computed: {
      totalPerDiem () {
        return Helpers.timesheetsTotalPerDiem(this.timesheets);
      },

      totalHours () {
        return Helpers.timesheetsTotalHours(this.timesheets);
      },

      totalHoursPay () {
        var pay = parseFloat(this.totalHours) * (parseFloat(this.$store.getters.user.hourly) * 100);
        return (pay / 100).toFixed(2);
      },

      totalTravelDistance () {
        return Helpers.timesheetsTotalTravelDistance(this.timesheets);
      },

      totalEquipmentCost () {
        return Helpers.timesheetsTotalEquipmentCost(this.timesheets);
      },

      totalOtherCosts () {
        return Helpers.timesheetsTotalOtherCosts(this.timesheets);
      },

      timesheetsTotal () {
        var total = parseFloat(this.totalPerDiem) +
                    parseFloat(this.totalHoursPay) + 
                    parseFloat(this.totalEquipmentCost) + 
                    parseFloat(this.totalOtherCosts);
        return total.toFixed(2);
      } 			
		}
	}
</script>