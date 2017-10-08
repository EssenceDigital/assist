<template>
  <!-- -->
  <v-container fluid>
    <v-divider class="mb-2 mt-2"></v-divider>    
    <v-layout row>
      <v-flex xs4>
        <span class="title">OUTSTANDING INVOICES:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title red--text">{{ invoicesOutstandingTotal | money }}</span>
      </v-flex>
    </v-layout>
    <v-divider class="mb-2 mt-2"></v-divider>

    <v-layout row class="mt-4">
      <v-flex xs4>
        <span class="title">PAID INVOICES TOTAL:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title green--text">{{ invoicesPaidTotal | money }}</span>
      </v-flex>
    </v-layout>

    <v-layout row class="mt-4">
      <v-flex xs4>
        <span class="title">TOTAL CREW COST:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title">{{ totalCrewCost | money }}</span>
      </v-flex>
    </v-layout>    
    <v-divider class="mt-2"></v-divider> 
    <v-layout row class="mt-4">
      <v-flex xs4>
        <span class="title">BOTTOM LINE:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">

        <span v-if="totalBottomLine > 0" class="title green--text">
          <p>
            +{{ totalBottomLine.toFixed(2) | money }}
          </p>
          <!-- Sub bottom line - The bottom line minus outstanding invoices -->
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
          <!-- Sub bottom line - The negative bottom line plus the outstanding invoices -->
          <p class="mt-2 mb-1">(-{{ ((totalBottomLine * -1) + invoicesOutstandingTotal).toFixed(2) | money }})</p>
          <p class="caption black--text">
            <small>(Including outstanding invoices)</small>
          </p>
        </span>
      </v-flex>
    </v-layout>                  
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

      totalBottomLine () {
        var total = (parseFloat(this.invoicesPaidTotal) * 100) - parseFloat(this.totalCrewCost * 100);

        return total / 100;
      },

      subBottomLine () {
        var total = (parseFloat(this.totalBottomLine) * 100) - (parseFloat(this.invoicesOutstandingTotal) * 100);

        return total / 100;
      }         
    }
  }
</script>