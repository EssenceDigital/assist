<template>
  <!-- Totals row -->
  <v-container fluid class="pl-0 pr-0">
    <!-- Data table -->
    <v-data-table
      :headers="[
        { text: 'Total', align: 'center', sortable: false },
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
	export default {
		props: ['timesheets'],

		computed: {
      totalPerDiem () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.per_diem);
        });
        return total.toFixed(2);
      },

      totalHours () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.total_hours);
        });
        return total;
      },

      totalHoursPay () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.total_hours_pay);
        });
        return total.toFixed(2);
      },

      totalTravelDistance () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.total_travel_distance);
        });
        return total.toFixed(2);
      },

      totalEquipmentCost () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.total_equipment_cost);
        });
        return total.toFixed(2);
      },

      totalOtherCosts () {
        var total = 0;
        this.timesheets.forEach((timesheet) => {
          total += parseFloat(timesheet.total_other_costs);
        });
        return total.toFixed(2);
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