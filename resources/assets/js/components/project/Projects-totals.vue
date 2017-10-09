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
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date != null) {
            total += (parseFloat(project.invoice_amount) * 100);
          }
        });
        return total / 100;
      },

      invoicesOutstandingTotal () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date === null) {
            total += (parseFloat(project.invoice_amount) * 100);
          }
        });
        return total / 100;
      },

      totalCrewCost () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.work_items.length != 0){
            total += (BusLogic.tallyWorkItemsTotal(project.work_items) * 100);
          }            
        });
        return total / 100;
      },

      totalPaidCrewCost () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.work_items.length != 0){
            total += (BusLogic.tallyPaidWorkItemsTotal(project.work_items) * 100);
          }            
        });
        return total / 100;        
      },

      totalOwingCrewCost () {
        var total = (parseFloat(this.totalCrewCost) * 100) - (parseFloat(this.totalPaidCrewCost) * 100);

        return total / 100;
      },

      totalBottomLine () {
        var total = (parseFloat(this.invoicesPaidTotal) * 100) - (parseFloat(this.totalCrewCost) * 100);

        return total / 100;
      },

      subBottomLine () {
        var total = (parseFloat(this.totalBottomLine) * 100) - (parseFloat(this.invoicesOutstandingTotal) * 100);

        return total / 100;
      }         
    }
  }
</script>