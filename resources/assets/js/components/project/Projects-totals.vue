<template>
  <!-- -->
  <v-container fluid>
    <v-divider class="mb-2 mt-2"></v-divider>   
    <!--<v-layout row>
      <v-flex xs4>
        <span class="title">PAID INVOICES TOTAL:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title">
          <p class="mt-2 mb-1">
            {{ invoicesPaidTotal | money }}
          </p>
          <p class="caption black--text">
            <small>(Payments from clients)</small>
          </p>         
        </span>
      </v-flex>
    </v-layout>-->     
    <v-layout row class="mt-2">
      <v-flex xs4>
        <span class="title">OUTSTANDING INVOICES:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title">
          <p class="mt-0 mb-1">
            {{ invoicesOutstandingTotal | money }}
          </p>
          <p class="caption black--text mb-0">
            <small>(Clients owe you)</small>
          </p>          
        </span>
      </v-flex>
    </v-layout>
    <v-divider class="mb-2 mt-2"></v-divider>

    <v-layout row class="mt-2">
      <v-flex xs4>
        <span class="title">TOTAL CREW COSTS:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title">
          <p class="mt-2 mb-1">
            {{ totalCrewCost | money }}
          </p>
          <p class="caption black--text">
            <small>(Total crew invoices)</small>
          </p>          
          <p class="mt-2 mb-1">
            ({{ totalPaidCrewCost | money }})
          </p>
          <p class="caption black--text">
            <small>(Paid so far)</small>
          </p> 
          <p class="mt-2 mb-1">
            <v-divider class="mt-1 mb-1"></v-divider>          
            {{ totalOwingCrewCost | money }}
          </p>
          <p class="caption black--text">
            <small>(Still owing )</small>
          </p>                   
        </span>
      </v-flex>
    </v-layout>    
    <v-divider class="mt-2"></v-divider> 
    <!--<v-layout row class="mt-4">
      <v-flex xs4>
        <span class="title">BOTTOM LINE:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">

        <span v-if="totalBottomLine > 0" class="title green--text">
          <p>
            +{{ totalBottomLine.toFixed(2) | money }}
          </p>

          <p v-if="subBottomLine > 0" class="mt-2 mb-1">
            (+{{ subBottomLine.toFixed(2) | money }})
          </p>
          <p v-if="subBottomLine < 0" class="red--text mt-2 mb-1">
            (-{{ (subBottomLine * -1).toFixed(2) | money }})
          </p>
          <p class="caption black--text">
            <small>(Including outstanding invoices)</small>
          </p>          
        </span>

        <span v-else class="title red--text">
          <p>-{{ (totalBottomLine * -1).toFixed(2) | money }}</p>

          <p class="mt-2 mb-1">(-{{ ((totalBottomLine * -1) + invoicesOutstandingTotal).toFixed(2) | money }})</p>
          <p class="caption black--text">
            <small>(Including outstanding invoices)</small>
          </p>
        </span>
      </v-flex>
    </v-layout>-->               
  </v-container><!--  -->  
</template>

<script>
  import BusLogic from './../../resources/bus-logic';

	export default {
    props: ['projects'],

    computed: {
      invoicesPaidTotal () {
        return BusLogic.tallyProjectsInvoicesPaidTotal(this.projects).toFixed(2);
      },

      invoicesOutstandingTotal () {
        return BusLogic.tallyProjectsInvoicesOutstandingTotal(this.projects).toFixed(2);
      },

      totalCrewCost () {
        return BusLogic.tallyProjectsTotalCrewCost(this.projects).toFixed(2);
      },

      totalPaidCrewCost () {
        return BusLogic.tallyProjectsTotalPaidCrewCost(this.projects).toFixed(2);       
      },

      totalOwingCrewCost () {
        return BusLogic.tallyProjectsOwingCrewCost(this.projects).toFixed(2);
      },

      totalBottomLine () {
        return BusLogic.tallyProjectsTotalBottomLine(this.projects).toFixed(2);
      },
      
      // Bottom line minus invoices outstanding
      subBottomLine () {
        return BusLogic.tallyProjectsSubBottomLine(this.projects).toFixed(2);
      }         
    }
  }
</script>